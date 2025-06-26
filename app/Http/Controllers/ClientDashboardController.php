<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\ServiceRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques du client
        $stats = [
            'properties_count' => $user->properties()->count(),
            'pending_properties' => $user->properties()->where('status', 'en_attente')->count(),
            'validated_properties' => $user->properties()->where('status', 'validee')->count(),
            'service_requests_count' => $user->serviceRequests()->count(),
            'pending_services' => $user->serviceRequests()->where('status', 'en_attente')->count(),
            'appointments_count' => $user->appointments()->count(),
            'upcoming_appointments' => $user->appointments()
                ->where('status', 'planifie')
                ->where('scheduled_at', '>=', now())
                ->count(),
        ];
        
        // Récentes annonces
        $recentProperties = $user->properties()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Récentes demandes de services
        $recentServiceRequests = $user->serviceRequests()
            ->with(['service'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Prochains rendez-vous
        $upcomingAppointments = $user->appointments()
            ->with(['serviceRequest.service'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit(3)
            ->get();
        
        return view('dashboards.client', compact(
            'stats', 
            'recentProperties', 
            'recentServiceRequests', 
            'upcomingAppointments'
        ));
    }
}
