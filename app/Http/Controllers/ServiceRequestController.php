<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Service;
use App\Models\User;
use App\Http\Requests\StoreServiceRequestRequest;
use App\Http\Requests\UpdateServiceRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin voit toutes les demandes actives (non archivées)
            $query = ServiceRequest::active()->with(['client', 'service', 'technicien']);
            
            if (request('status')) {
                $query->where('status', request('status'));
            }
            
            $serviceRequests = $query->latest()->paginate(15);
        } elseif ($user->role === 'technicien') {
            // Technicien voit ses demandes assignées actives
            $serviceRequests = ServiceRequest::where('technicien_id', $user->id)
                ->active()
                ->with(['client', 'service'])
                ->latest()
                ->paginate(15);
        } else {
            // Client voit ses demandes actives
            $serviceRequests = ServiceRequest::where('client_id', $user->id)
                ->active()
                ->with(['service', 'technicien'])
                ->latest()
                ->paginate(15);
        }
        
        return view('service-requests.index', compact('serviceRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('service-requests.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequestRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = Auth::id();
        $data['status'] = 'en_attente';
        
        ServiceRequest::create($data);
        
        return redirect()->route('service-requests.index')
            ->with('success', 'Votre demande de service a été soumise avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        // Vérification des permissions
        if ($serviceRequest->client_id !== Auth::id() && 
            $serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $serviceRequest->load(['client', 'service', 'technicien']);
        
        return view('service-requests.show', compact('serviceRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceRequest $serviceRequest)
    {
        // Seul le client propriétaire ou l'admin peut modifier
        if ($serviceRequest->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $services = Service::where('is_active', true)->get();
        return view('service-requests.edit', compact('serviceRequest', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequestRequest $request, ServiceRequest $serviceRequest)
    {
        // Vérification des permissions
        if ($serviceRequest->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $data = $request->validated();
        
        // Si c'est un client qui modifie et que la demande était assignée, la remettre en attente
        if (Auth::user()->role !== 'admin' && in_array($serviceRequest->status, ['assignee', 'en_cours'])) {
            $data['status'] = 'en_attente';
            $data['technicien_id'] = null;
        }
        
        $serviceRequest->update($data);
        
        return redirect()->route('service-requests.index')
            ->with('success', 'Demande de service mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        // Seul le client propriétaire ou l'admin peut supprimer
        if ($serviceRequest->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Ne pas permettre la suppression si en cours ou terminée
        if (in_array($serviceRequest->status, ['en_cours', 'terminee'])) {
            return back()->with('error', 'Impossible de supprimer une demande en cours ou terminée.');
        }
        
        $serviceRequest->delete();
        
        return redirect()->route('service-requests.index')
            ->with('success', 'Demande de service supprimée avec succès.');
    }

    /**
     * Assigner un technicien (Admin seulement)
     */
    public function assign(Request $request, ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'technicien_id' => 'required|exists:users,id',
        ]);
        
        // Vérifier que l'utilisateur sélectionné est bien un technicien
        $technicien = User::findOrFail($request->technicien_id);
        if ($technicien->role !== 'technicien') {
            return back()->with('error', 'L\'utilisateur sélectionné n\'est pas un technicien.');
        }
        
        $serviceRequest->update([
            'technicien_id' => $request->technicien_id,
            'status' => 'assignee',
            'assigned_at' => now(),
        ]);
        
        return back()->with('success', 'Technicien assigné avec succès.');
    }

    /**
     * Commencer le travail (Technicien seulement)
     */
    public function start(ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->technicien_id !== Auth::id()) {
            abort(403);
        }
        
        $serviceRequest->update([
            'status' => 'en_cours',
            'started_at' => now(),
        ]);
        
        return back()->with('success', 'Service commencé.');
    }

    /**
     * Terminer le travail (Technicien seulement)
     */
    public function complete(Request $request, ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->technicien_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
            'final_notes' => 'nullable|string|max:1000',
        ]);
        
        $serviceRequest->update([
            'status' => 'terminee',
            'completed_at' => now(),
            'completion_notes' => $request->completion_notes,
            'final_notes' => $request->final_notes,
            'technicien_notes' => $request->completion_notes, // Notes du technicien
            'is_archived' => true, // Archive automatiquement
            'archived_at' => now(),
        ]);

        // Marquer tous les rendez-vous associés comme terminés
        $serviceRequest->appointments()->update([
            'status' => 'termine',
            'completed_at' => now(),
        ]);
        
        return back()->with('success', 'Service terminé et archivé avec succès.');
    }

    /**
     * Ajouter une note admin (Admin seulement)
     */
    public function addAdminNote(Request $request, ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);
        
        $serviceRequest->update([
            'admin_notes' => $request->admin_notes,
        ]);
        
        return back()->with('success', 'Note ajoutée avec succès.');
    }

    /**
     * Voir les demandes de service terminées
     */
    public function completed()
    {
        $query = ServiceRequest::where('status', 'terminee')
            ->with(['client', 'service', 'technicien', 'appointments']);

        // Filtrer par rôle
        if (Auth::user()->role === 'client') {
            $query->where('client_id', Auth::id());
        } elseif (Auth::user()->role === 'technicien') {
            $query->where('technicien_id', Auth::id());
        }

        $completedRequests = $query->orderBy('completed_at', 'desc')->paginate(10);

        return view('service-requests.completed', compact('completedRequests'));
    }

    /**
     * Évaluer un service (Client seulement)
     */
    public function rate(Request $request, ServiceRequest $serviceRequest)
    {
        // Vérifier que c'est le client du service et que le service est terminé
        if ($serviceRequest->client_id !== Auth::id() || $serviceRequest->status !== 'terminee') {
            abort(403);
        }

        $request->validate([
            'client_rating' => 'required|integer|min:1|max:5',
            'client_feedback' => 'nullable|string|max:1000',
        ]);

        $serviceRequest->update([
            'client_rating' => $request->client_rating,
            'client_feedback' => $request->client_feedback,
        ]);

        return back()->with('success', 'Merci pour votre évaluation !');
    }

    /**
     * Voir les services archivés (Admin seulement)
     */
    public function archived()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $archivedRequests = ServiceRequest::archived()
            ->with(['client', 'service', 'technicien'])
            ->latest('archived_at')
            ->paginate(15);
        
        return view('service-requests.archived', compact('archivedRequests'));
    }

    /**
     * Obtenir les techniciens disponibles pour un service
     */
    public function getAvailableTechnicians(Service $service)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $technicians = User::where('role', 'technicien')
            ->where('is_active', true)
            ->where(function($query) use ($service) {
                $query->whereNull('speciality')
                      ->orWhere('speciality', 'like', '%' . $service->name . '%');
            })
            ->get(['id', 'name', 'speciality']);
        
        return response()->json($technicians);
    }

    /**
     * Generate PDF for service request
     */
    public function pdf(ServiceRequest $serviceRequest)
    {
        // Vérification des permissions
        if ($serviceRequest->client_id !== Auth::id() && 
            $serviceRequest->technicien_id !== Auth::id() && 
            Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Charger les relations nécessaires
        $serviceRequest->load(['client', 'service', 'technicien']);
        
        // Générer le PDF
        $pdf = Pdf::loadView('service-requests.pdf', compact('serviceRequest'));
        
        // Nom du fichier
        $filename = 'demande-service-' . $serviceRequest->id . '.pdf';
        
        // Retourner le PDF pour téléchargement
        return $pdf->download($filename);
    }

    /**
     * Show all client evaluations (Admin only)
     */
    public function evaluations()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = ServiceRequest::with(['client', 'service', 'technicien'])
            ->whereNotNull('client_rating')
            ->orderBy('updated_at', 'desc');

        // Filtres
        if (request('technicien_id')) {
            $query->where('technicien_id', request('technicien_id'));
        }

        if (request('min_rating')) {
            $query->where('client_rating', '>=', request('min_rating'));
        }

        if (request('service_id')) {
            $query->where('service_id', request('service_id'));
        }

        $evaluatedServices = $query->paginate(10);

        // Statistiques
        $allEvaluations = ServiceRequest::whereNotNull('client_rating')->get();
        $averageRating = $allEvaluations->avg('client_rating') ?? 0;
        $excellentRatings = $allEvaluations->where('client_rating', 5)->count();
        $lowRatings = $allEvaluations->where('client_rating', '<=', 2)->count();

        // Données pour les filtres
        $techniciens = \App\Models\User::where('role', 'technicien')->orderBy('name')->get();
        $services = \App\Models\Service::orderBy('name')->get();

        return view('service-requests.evaluations', compact(
            'evaluatedServices', 
            'averageRating', 
            'excellentRatings', 
            'lowRatings',
            'techniciens',
            'services'
        ));
    }
}
