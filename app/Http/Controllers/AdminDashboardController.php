<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_users' => User::count(),
            'clients_count' => User::where('role', 'client')->count(),
            'techniciens_count' => User::where('role', 'technicien')->count(),
            'total_properties' => Property::count(),
            'pending_properties' => Property::where('status', 'en_attente')->count(),
            'validated_properties' => Property::where('status', 'validee')->count(),
            'total_services' => Service::count(),
            'total_service_requests' => ServiceRequest::count(),
            'pending_service_requests' => ServiceRequest::where('status', 'en_attente')->count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'planifie')->count(),
        ];
        
        // Annonces en attente de validation
        $pendingProperties = Property::with(['user'])
            ->where('status', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Demandes de services en attente d'attribution
        $pendingServiceRequests = ServiceRequest::with(['client', 'service'])
            ->where('status', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Rendez-vous planifiés
        $pendingAppointments = Appointment::with(['client', 'serviceRequest.service'])
            ->where('status', 'planifie')
            ->orderBy('scheduled_at', 'asc')
            ->limit(10)
            ->get();
            
        // Utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboards.admin', compact(
            'stats',
            'pendingProperties',
            'pendingServiceRequests', 
            'pendingAppointments',
            'recentUsers'
        ));
    }
}
