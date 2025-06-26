<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicienDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques du technicien
        $stats = [
            'total_requests' => $user->assignedServiceRequests()->count(),
            'pending_requests' => $user->assignedServiceRequests()->where('status', 'assignee')->count(),
            'accepted_requests' => $user->assignedServiceRequests()->where('status', 'acceptee')->count(),
            'completed_requests' => $user->assignedServiceRequests()->where('status', 'terminee')->count(),
            'in_progress_requests' => $user->assignedServiceRequests()->where('status', 'en_cours')->count(),
        ];
        
        // Demandes assignées récemment
        $assignedRequests = $user->assignedServiceRequests()
            ->with(['client', 'service'])
            ->where('status', 'assignee')
            ->orderBy('assigned_at', 'desc')
            ->get();
            
        // Demandes acceptées en cours
        $acceptedRequests = $user->assignedServiceRequests()
            ->with(['client', 'service'])
            ->whereIn('status', ['acceptee', 'en_cours'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        // Historique des demandes terminées
        $completedRequests = $user->assignedServiceRequests()
            ->with(['client', 'service'])
            ->where('status', 'terminee')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboards.technicien', compact(
            'stats', 
            'assignedRequests', 
            'acceptedRequests', 
            'completedRequests'
        ));
    }
}
