<?php

namespace App\Http\Controllers;

use App\Models\PropertyAppointment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyAppointmentController extends Controller
{
    /**
     * Display a listing of property appointments.
     */
    public function index()
    {
        $query = PropertyAppointment::with(['property', 'client']);

        // Filtrer par rôle
        if (Auth::user()->role === 'client') {
            $query->where('client_id', Auth::id());
        } elseif (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $appointments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('property-appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created appointment request.
     */
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'requested_date' => 'required|date|after:now',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        $appointment = PropertyAppointment::create([
            'property_id' => $property->id,
            'client_id' => Auth::id(),
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'requested_date' => $request->requested_date,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Demande de rendez-vous envoyée avec succès ! Vous recevrez une confirmation par email.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(PropertyAppointment $propertyAppointment)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'client' && $propertyAppointment->client_id !== Auth::id()) {
            abort(403);
        }

        $propertyAppointment->load(['property', 'client']);

        return view('property-appointments.show', compact('propertyAppointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Confirmer un rendez-vous (Admin seulement)
     */
    public function confirm(Request $request, PropertyAppointment $propertyAppointment)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'confirmed_date' => 'required|date|after:now',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $propertyAppointment->update([
            'status' => 'confirmed',
            'confirmed_date' => $request->confirmed_date,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Rendez-vous confirmé avec succès !');
    }

    /**
     * Annuler un rendez-vous
     */
    public function cancel(Request $request, PropertyAppointment $propertyAppointment)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'client' && $propertyAppointment->client_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $propertyAppointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return back()->with('success', 'Rendez-vous annulé.');
    }

    /**
     * Marquer un rendez-vous comme terminé (Admin seulement)
     */
    public function complete(Request $request, PropertyAppointment $propertyAppointment)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $propertyAppointment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->completion_notes,
        ]);

        return back()->with('success', 'Rendez-vous marqué comme terminé.');
    }

    /**
     * Obtenir les rendez-vous en attente pour l'admin
     */
    public function pending()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $pendingAppointments = PropertyAppointment::pending()
            ->with(['property', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('property-appointments.pending', compact('pendingAppointments'));
    }

    /**
     * Obtenir les rendez-vous du jour pour l'admin
     */
    public function today()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $todayAppointments = PropertyAppointment::confirmed()
            ->whereDate('confirmed_date', today())
            ->with(['property', 'client'])
            ->orderBy('confirmed_date')
            ->get();

        return view('property-appointments.today', compact('todayAppointments'));
    }

    /**
     * Voir les rendez-vous pour les annonces du propriétaire
     */
    public function myPropertyAppointments()
    {
        if (Auth::user()->role !== 'client') {
            abort(403);
        }

        // Obtenir les IDs des propriétés du client connecté
        $propertyIds = Auth::user()->properties()->pluck('id');

        $appointments = PropertyAppointment::whereIn('property_id', $propertyIds)
            ->with(['property', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('property-appointments.my-properties', compact('appointments'));
    }

    /**
     * Vue admin organisée pour tous les rendez-vous d'annonces
     */
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Statistiques
        $stats = [
            'pending' => PropertyAppointment::pending()->count(),
            'confirmed' => PropertyAppointment::confirmed()->count(),
            'completed' => PropertyAppointment::completed()->count(),
            'cancelled' => PropertyAppointment::cancelled()->count(),
            'today' => PropertyAppointment::confirmed()
                ->whereDate('confirmed_date', today())
                ->count(),
        ];

        // Rendez-vous récents
        $recentAppointments = PropertyAppointment::with(['property', 'client'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Rendez-vous d'aujourd'hui
        $todayAppointments = PropertyAppointment::confirmed()
            ->whereDate('confirmed_date', today())
            ->with(['property', 'client'])
            ->orderBy('confirmed_date')
            ->get();

        // Rendez-vous en attente
        $pendingAppointments = PropertyAppointment::pending()
            ->with(['property', 'client'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('property-appointments.admin-index', compact(
            'stats',
            'recentAppointments', 
            'todayAppointments',
            'pendingAppointments'
        ));
    }

    /**
     * Modifier les notes admin
     */
    public function updateNotes(Request $request, PropertyAppointment $propertyAppointment)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $propertyAppointment->update([
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Notes mises à jour avec succès.');
    }

    /**
     * Supprimer un rendez-vous
     */
    public function destroy(PropertyAppointment $propertyAppointment)
    {
        // Seuls les admins peuvent supprimer
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Ne pas permettre la suppression si confirmé ou terminé
        if (in_array($propertyAppointment->status, ['confirmed', 'completed'])) {
            return back()->with('error', 'Impossible de supprimer un rendez-vous confirmé ou terminé.');
        }

        $propertyAppointment->delete();

        return back()->with('success', 'Rendez-vous supprimé avec succès.');
    }
}
