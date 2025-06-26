<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ServiceRequest;
use App\Http\Requests\StoreAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Filtrer par statut (actifs par défaut, sauf si spécifié)
        $statusFilter = request('status', 'actifs');
        
        // Base query selon le rôle
        if ($user->role === 'admin') {
            // Admin voit tous les rendez-vous selon le filtre
            $query = Appointment::with(['client', 'serviceRequest.service']);
        } elseif ($user->role === 'technicien') {
            // Technicien voit les rendez-vous de ses services assignés
            $query = Appointment::whereHas('serviceRequest', function($subQuery) use ($user) {
                $subQuery->where('technicien_id', $user->id);
            })->with(['client', 'serviceRequest.service']);
        } else {
            // Client voit ses rendez-vous
            $query = Appointment::where('client_id', $user->id)
                ->with(['serviceRequest.service']);
        }
        
        // Appliquer le filtre de statut
        if ($statusFilter === 'actifs') {
            $query->whereNotIn('status', ['termine', 'annule']);
        } elseif ($statusFilter === 'termines') {
            $query->where('status', 'termine');
        } elseif ($statusFilter === 'annules') {
            $query->where('status', 'annule');
        }
        // Si $statusFilter === 'tous', ne pas filtrer
        
        $appointments = $query->latest('scheduled_at')->paginate(15);
        
        return view('appointments.index', compact('appointments', 'statusFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Récupérer les demandes de service éligibles pour un rendez-vous
        if ($user->role === 'admin') {
            $serviceRequests = ServiceRequest::with(['client', 'service'])
                ->whereIn('status', ['assignee', 'en_cours'])
                ->get();
        } elseif ($user->role === 'technicien') {
            $serviceRequests = ServiceRequest::with(['client', 'service'])
                ->where('technicien_id', $user->id)
                ->whereIn('status', ['assignee', 'en_cours'])
                ->get();
        } else {
            $serviceRequests = ServiceRequest::with(['service'])
                ->where('client_id', $user->id)
                ->whereIn('status', ['assignee', 'en_cours'])
                ->get();
        }
        
        // Récupérer tous les services pour la sélection directe
        $services = \App\Models\Service::orderBy('name')->get();
        
        // Récupérer tous les clients (pour admin seulement)
        $clients = [];
        if ($user->role === 'admin') {
            $clients = \App\Models\User::where('role', 'client')->orderBy('name')->get();
        }
        
        return view('appointments.create', compact('serviceRequests', 'services', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();
        
        // Déterminer le client_id selon le type de rendez-vous et le rôle
        if (Auth::user()->role === 'admin' && $request->appointment_type === 'direct_service' && $request->client_id) {
            $data['client_id'] = $request->client_id;
        } else {
            $data['client_id'] = Auth::id();
        }
        
        $data['status'] = 'planifie';
        $data['is_locked'] = true; // Verrouillage automatique après création
        
        // Si c'est un rendez-vous avec service direct, créer d'abord une demande de service
        if ($request->appointment_type === 'direct_service') {
            $serviceRequest = ServiceRequest::create([
                'client_id' => $data['client_id'],
                'service_id' => $request->service_id,
                'address' => $request->address,
                'city' => $request->city,
                'description' => $request->description,
                'status' => 'en_cours',
                'priority' => 'normale',
            ]);
            
            $data['service_request_id'] = $serviceRequest->id;
        }
        
        $appointment = Appointment::create($data);

        // Mettre à jour le statut de la demande de service si elle existait déjà
        if ($request->appointment_type === 'existing_request') {
            $serviceRequest = $appointment->serviceRequest;
            if ($serviceRequest->status === 'assignee') {
                $serviceRequest->update(['status' => 'en_cours']);
            }
        }
        
        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous planifié avec succès. Seul l\'administrateur peut maintenant le modifier.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Vérification des permissions
        if ($appointment->client_id !== Auth::id() && 
            $appointment->serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $appointment->load(['client', 'serviceRequest.service', 'serviceRequest.technicien']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        // Seul l'admin peut modifier un rendez-vous verrouillé
        if ($appointment->is_locked && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Ce rendez-vous est verrouillé. Seul l\'administrateur peut le modifier.');
        }

        // Vérification des permissions normales pour les rendez-vous non verrouillés
        if (!$appointment->is_locked && 
            $appointment->client_id !== Auth::id() && 
            $appointment->serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        return view('appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAppointmentRequest $request, Appointment $appointment)
    {
        // Vérification des permissions
        if ($appointment->client_id !== Auth::id() && 
            $appointment->serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $appointment->update($request->validated());
        
        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Vérification des permissions et statut
        if ($appointment->client_id !== Auth::id() && 
            $appointment->serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        if ($appointment->status === 'termine') {
            return back()->with('error', 'Impossible de supprimer un rendez-vous terminé.');
        }
        
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }

    /**
     * Confirmer un rendez-vous
     */
    public function confirm(Appointment $appointment)
    {
        if ($appointment->serviceRequest->technicien_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $appointment->update(['status' => 'confirme']);
        
        return back()->with('success', 'Rendez-vous confirmé.');
    }

    /**
     * Marquer un rendez-vous comme terminé
     */
    public function complete(Request $request, Appointment $appointment)
    {
        if ($appointment->serviceRequest->technicien_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'completion_notes' => 'required|string|max:1000',
            'final_notes' => 'nullable|string|max:1000',
        ]);
        
        // Mettre à jour le rendez-vous
        $appointment->update([
            'status' => 'termine',
            'notes' => $request->notes,
            'completed_at' => now(),
        ]);

        // Mettre à jour la demande de service associée
        $serviceRequest = $appointment->serviceRequest;
        $serviceRequest->update([
            'status' => 'terminee',
            'completed_at' => now(),
            'completion_notes' => $request->completion_notes,
            'technicien_notes' => $request->completion_notes,
            'final_notes' => $request->final_notes,
            'is_archived' => true,
            'archived_at' => now(),
        ]);
        
        return back()->with('success', 'Rendez-vous terminé et service archivé avec succès.');
    }

    /**
     * Annuler un rendez-vous
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // Vérification des permissions
        if ($appointment->client_id !== Auth::id() && 
            $appointment->serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);
        
        $appointment->update([
            'status' => 'annule',
            'cancellation_reason' => $request->cancellation_reason,
        ]);
        
        return back()->with('success', 'Rendez-vous annulé.');
    }

    /**
     * Generate PDF for appointment
     */
    public function pdf(Appointment $appointment)
    {
        // Vérification des permissions
        if ($appointment->client_id !== Auth::id() && 
            $appointment->serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Charger les relations nécessaires
        $appointment->load(['client', 'serviceRequest.service', 'serviceRequest.technicien']);

        // Générer le PDF
        $pdf = Pdf::loadView('appointments.pdf', compact('appointment'));
        
        // Nom du fichier
        $filename = 'rendez-vous-' . $appointment->id . '-' . $appointment->scheduled_at->format('Y-m-d') . '.pdf';
        
        // Retourner le PDF pour téléchargement
        return $pdf->download($filename);
    }

    /**
     * Proposer un nouveau rendez-vous (Technicien seulement)
     */
    public function propose(Request $request, Appointment $appointment)
    {
        if ($appointment->serviceRequest->technicien_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'proposed_date' => 'required|date|after:now',
            'proposed_reason' => 'required|string|max:500',
        ]);

        $appointment->update([
            'proposed_by' => 'technicien',
            'proposed_date' => $request->proposed_date,
            'proposed_reason' => $request->proposed_reason,
            'status' => 'proposition_technicien',
        ]);

        return back()->with('success', 'Nouvelle proposition de rendez-vous envoyée à l\'administrateur.');
    }

    /**
     * Approuver une proposition de rendez-vous (Admin seulement)
     */
    public function approveProposal(Appointment $appointment)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (!$appointment->proposed_date) {
            return back()->with('error', 'Aucune proposition de rendez-vous à approuver.');
        }

        $appointment->update([
            'scheduled_at' => $appointment->proposed_date,
            'status' => 'planifie',
            'proposed_by' => null,
            'proposed_date' => null,
            'proposed_reason' => null,
        ]);

        return back()->with('success', 'Proposition de rendez-vous approuvée.');
    }

    /**
     * Rejeter une proposition de rendez-vous (Admin seulement)
     */
    public function rejectProposal(Request $request, Appointment $appointment)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $appointment->update([
            'status' => 'planifie',
            'proposed_by' => null,
            'proposed_date' => null,
            'proposed_reason' => null,
            'cancellation_reason' => 'Proposition rejetée: ' . $request->rejection_reason,
        ]);

        return back()->with('success', 'Proposition de rendez-vous rejetée.');
    }
}
