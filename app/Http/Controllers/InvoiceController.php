<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Afficher toutes les factures (Admin)
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $invoices = Invoice::with(['serviceRequest.service', 'client', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Factures du client connecté
     */
    public function clientInvoices()
    {
        if (Auth::user()->role !== 'client') {
            abort(403);
        }

        $invoices = Invoice::where('client_id', Auth::id())
            ->with(['serviceRequest.service'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invoices.client', compact('invoices'));
    }

    /**
     * Formulaire de création de facture (Admin)
     */
    public function create(ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Vérifier qu'une facture n'existe pas déjà
        if ($serviceRequest->invoice) {
            return redirect()->route('invoices.show', $serviceRequest->invoice)
                ->with('info', 'Une facture existe déjà pour cette demande.');
        }

        // Vérifier qu'il y a des rapports de tâches
        if ($serviceRequest->taskReports->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Aucun rapport de tâche disponible. Le technicien doit d\'abord soumettre son rapport.');
        }

        $serviceRequest->load(['service', 'client', 'technicien', 'taskReports']);
        
        // Calculer les montants suggérés
        $baseAmount = $serviceRequest->service->price;
        $totalMaterialCost = $serviceRequest->taskReports->sum('material_cost');
        
        return view('invoices.create', compact('serviceRequest', 'baseAmount', 'totalMaterialCost'));
    }

    /**
     * Enregistrer la facture (Admin)
     */
    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Vérifier qu'une facture n'existe pas déjà
        if ($serviceRequest->invoice) {
            return redirect()->route('invoices.show', $serviceRequest->invoice)
                ->with('error', 'Une facture existe déjà pour cette demande.');
        }

        $validated = $request->validate([
            'base_amount' => 'required|numeric|min:0|max:9999999',
            'additional_amount' => 'nullable|numeric|min:0|max:9999999',
            'discount_amount' => 'nullable|numeric|min:0|max:9999999',
            'admin_notes' => 'nullable|string|max:1000',
            'due_days' => 'required|integer|min:1|max:90',
        ]);

        $baseAmount = $validated['base_amount'];
        $additionalAmount = $validated['additional_amount'] ?? 0;
        $discountAmount = $validated['discount_amount'] ?? 0;
        $totalAmount = $baseAmount + $additionalAmount - $discountAmount;

        // Vérifier que le total est positif
        if ($totalAmount < 0) {
            return redirect()->back()
                ->with('error', 'Le montant total ne peut pas être négatif.')
                ->withInput();
        }

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'service_request_id' => $serviceRequest->id,
            'client_id' => $serviceRequest->client_id,
            'created_by' => Auth::id(),
            'base_amount' => $baseAmount,
            'additional_amount' => $additionalAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'admin_notes' => $validated['admin_notes'],
            'due_date' => now()->addDays((int) $validated['due_days']),
            'status' => 'draft',
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Afficher une facture
     */
    public function show(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if ($user->role !== 'admin' && $invoice->client_id !== $user->id) {
            abort(403);
        }

        // Marquer comme vue si c'est le client
        if ($user->role === 'client') {
            $invoice->markAsViewed();
        }

        $invoice->load(['serviceRequest.service', 'serviceRequest.taskReports', 'client', 'createdBy']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Envoyer la facture au client (Admin)
     */
    public function send(Invoice $invoice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($invoice->status === 'draft') {
            $invoice->markAsSent();
            return redirect()->back()
                ->with('success', 'Facture envoyée au client avec succès.');
        }

        return redirect()->back()
            ->with('error', 'Cette facture a déjà été envoyée.');
    }

    /**
     * Marquer comme payée (Admin)
     */
    public function markPaid(Invoice $invoice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $invoice->markAsPaid();
        
        return redirect()->back()
            ->with('success', 'Facture marquée comme payée.');
    }

    /**
     * Télécharger la facture en PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if ($user->role !== 'admin' && $invoice->client_id !== $user->id) {
            abort(403);
        }

        $invoice->load(['serviceRequest.service', 'serviceRequest.taskReports', 'client', 'createdBy']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Modifier une facture (Admin, seulement si brouillon)
     */
    public function edit(Invoice $invoice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($invoice->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Seules les factures en brouillon peuvent être modifiées.');
        }

        $serviceRequest = $invoice->serviceRequest->load(['service', 'taskReports']);
        $totalMaterialCost = $serviceRequest->taskReports->sum('material_cost');

        return view('invoices.edit', compact('invoice', 'serviceRequest', 'totalMaterialCost'));
    }

    /**
     * Mettre à jour une facture (Admin)
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($invoice->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Seules les factures en brouillon peuvent être modifiées.');
        }

        $validated = $request->validate([
            'base_amount' => 'required|numeric|min:0|max:9999999',
            'additional_amount' => 'nullable|numeric|min:0|max:9999999',
            'discount_amount' => 'nullable|numeric|min:0|max:9999999',
            'admin_notes' => 'nullable|string|max:1000',
            'due_days' => 'required|integer|min:1|max:90',
        ]);

        $baseAmount = $validated['base_amount'];
        $additionalAmount = $validated['additional_amount'] ?? 0;
        $discountAmount = $validated['discount_amount'] ?? 0;
        $totalAmount = $baseAmount + $additionalAmount - $discountAmount;

        if ($totalAmount < 0) {
            return redirect()->back()
                ->with('error', 'Le montant total ne peut pas être négatif.')
                ->withInput();
        }

        $invoice->update([
            'base_amount' => $baseAmount,
            'additional_amount' => $additionalAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'admin_notes' => $validated['admin_notes'],
            'due_date' => now()->addDays((int) $validated['due_days']),
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture mise à jour avec succès.');
    }

    /**
     * Supprimer une facture (Admin, seulement si brouillon)
     */
    public function destroy(Invoice $invoice)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($invoice->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Seules les factures en brouillon peuvent être supprimées.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès.');
    }
}
