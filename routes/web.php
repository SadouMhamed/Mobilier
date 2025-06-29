<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyAppointmentController;
use App\Http\Controllers\PropertyContactController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\TechnicienDashboardController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TaskReportController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Test route to check if basic routing works
Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'message' => 'Basic routing works']);
});

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route publique pour les annonces validées
Route::get('/annonces', [PropertyController::class, 'public'])->name('properties.public');

// Route dashboard principal - redirige selon le rôle
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'technicien':
            return redirect()->route('technicien.dashboard');
        case 'client':
        default:
            return redirect()->route('client.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboards spécifiques par rôle
Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
});

Route::middleware(['auth', 'verified', 'role:technicien'])->group(function () {
    Route::get('/technicien/dashboard', [TechnicienDashboardController::class, 'index'])->name('technicien.dashboard');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Routes authentifiées
Route::middleware('auth')->group(function () {
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes des annonces immobilières
    Route::resource('properties', PropertyController::class);
    
    // Routes admin pour validation/rejet des annonces
    Route::middleware('role:admin')->group(function () {
        Route::patch('/properties/{property}/validate', [PropertyController::class, 'validate'])->name('properties.validate');
        Route::patch('/properties/{property}/reject', [PropertyController::class, 'reject'])->name('properties.reject');
        Route::patch('/properties/{property}/reactivate', [PropertyController::class, 'reactivate'])->name('properties.reactivate');
    });
    
    // Routes pour marquer les propriétés comme vendues/louées
    Route::patch('/properties/{property}/mark-as-rented', [PropertyController::class, 'markAsRented'])->name('properties.mark-as-rented');
    Route::patch('/properties/{property}/mark-as-sold', [PropertyController::class, 'markAsSold'])->name('properties.mark-as-sold');
    
    // Routes des demandes de service
    Route::resource('service-requests', ServiceRequestController::class);
    
    // Routes PDF pour demandes de service
Route::get('/service-requests/{serviceRequest}/pdf', [ServiceRequestController::class, 'pdf'])
    ->name('service-requests.pdf');

// Routes PDF pour rendez-vous
Route::get('/appointments/{appointment}/pdf', [AppointmentController::class, 'pdf'])
    ->name('appointments.pdf');

// Routes PDF pour propriétés
Route::get('/properties/{property}/pdf', [PropertyController::class, 'pdf'])
    ->name('properties.pdf');

// Routes pour les rendez-vous d'annonces
Route::post('/properties/{property}/appointment', [PropertyAppointmentController::class, 'store'])
    ->name('properties.appointment.store');
Route::resource('property-appointments', PropertyAppointmentController::class);
Route::patch('/property-appointments/{propertyAppointment}/confirm', [PropertyAppointmentController::class, 'confirm'])
    ->name('property-appointments.confirm')
    ->middleware('role:admin');
Route::patch('/property-appointments/{propertyAppointment}/cancel', [PropertyAppointmentController::class, 'cancel'])
    ->name('property-appointments.cancel');
Route::patch('/property-appointments/{propertyAppointment}/complete', [PropertyAppointmentController::class, 'complete'])
    ->name('property-appointments.complete')
    ->middleware('role:admin');
Route::get('/property-appointments-pending', [PropertyAppointmentController::class, 'pending'])
    ->name('property-appointments.pending')
    ->middleware('role:admin');
Route::get('/property-appointments-today', [PropertyAppointmentController::class, 'today'])
    ->name('property-appointments.today')
    ->middleware('role:admin');
Route::get('/my-property-appointments', [PropertyAppointmentController::class, 'myPropertyAppointments'])
    ->name('property-appointments.my-properties')
    ->middleware('role:client');
Route::get('/admin/property-appointments', [PropertyAppointmentController::class, 'adminIndex'])
    ->name('property-appointments.admin-index')
    ->middleware('role:admin');

// Routes pour les contacts d'annonces
Route::post('/properties/{property}/contact', [PropertyContactController::class, 'store'])
    ->name('properties.contact.store');
Route::resource('property-contacts', PropertyContactController::class);
Route::patch('/property-contacts/{propertyContact}/reply', [PropertyContactController::class, 'reply'])
    ->name('property-contacts.reply');
Route::patch('/property-contacts/{propertyContact}/mark-read', [PropertyContactController::class, 'markAsRead'])
    ->name('property-contacts.mark-read');
Route::get('/property-contacts-received', [PropertyContactController::class, 'received'])
    ->name('property-contacts.received');
Route::get('/property-contacts/unread-count', [PropertyContactController::class, 'unreadCount'])
    ->name('property-contacts.unread-count');
    
    // Routes spéciales pour les demandes de service
    Route::patch('/service-requests/{serviceRequest}/assign', [ServiceRequestController::class, 'assign'])
        ->middleware('role:admin')->name('service-requests.assign');
    Route::patch('/service-requests/{serviceRequest}/start', [ServiceRequestController::class, 'start'])
        ->name('service-requests.start');
    Route::patch('/service-requests/{serviceRequest}/complete', [ServiceRequestController::class, 'complete'])
        ->name('service-requests.complete');

    Route::post('/service-requests/{serviceRequest}/admin-note', [ServiceRequestController::class, 'addAdminNote'])
        ->name('service-requests.admin-note')
        ->middleware('role:admin');

    Route::get('/service-requests-completed', [ServiceRequestController::class, 'completed'])
        ->name('service-requests.completed');
    Route::patch('/service-requests/{serviceRequest}/rate', [ServiceRequestController::class, 'rate'])
        ->name('service-requests.rate')
        ->middleware('role:client');
    Route::get('/service-requests-archived', [ServiceRequestController::class, 'archived'])
        ->name('service-requests.archived')
        ->middleware('role:admin');
    Route::get('/service-requests-evaluations', [ServiceRequestController::class, 'evaluations'])
        ->name('service-requests.evaluations')
        ->middleware('role:admin');
    
    // API pour obtenir les techniciens disponibles
    Route::get('/services/{service}/technicians', [ServiceRequestController::class, 'getAvailableTechnicians'])
        ->middleware('role:admin')->name('services.technicians');

    // Routes des rendez-vous
    Route::resource('appointments', AppointmentController::class);
    
    // Routes spéciales pour les rendez-vous
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])
        ->name('appointments.confirm');
    Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])
        ->name('appointments.complete');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');

    Route::post('/appointments/{appointment}/propose', [AppointmentController::class, 'propose'])
        ->name('appointments.propose');
    Route::patch('/appointments/{appointment}/approve-proposal', [AppointmentController::class, 'approveProposal'])
        ->name('appointments.approve-proposal')
        ->middleware('role:admin');
    Route::patch('/appointments/{appointment}/reject-proposal', [AppointmentController::class, 'rejectProposal'])
        ->name('appointments.reject-proposal')
        ->middleware('role:admin');
    
    // Routes des rapports de tâches (techniciens)
    Route::get('/service-requests/{serviceRequest}/task-reports/create', [TaskReportController::class, 'create'])
        ->name('task-reports.create');
    Route::post('/service-requests/{serviceRequest}/task-reports', [TaskReportController::class, 'store'])
        ->name('task-reports.store');
    Route::get('/task-reports/{taskReport}', [TaskReportController::class, 'show'])
        ->name('task-reports.show');
    Route::get('/task-reports/{taskReport}/edit', [TaskReportController::class, 'edit'])
        ->name('task-reports.edit');
    Route::patch('/task-reports/{taskReport}', [TaskReportController::class, 'update'])
        ->name('task-reports.update');
    
    // Routes des factures
    Route::middleware('role:admin')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/service-requests/{serviceRequest}/invoice/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/service-requests/{serviceRequest}/invoice', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::patch('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        Route::patch('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
        Route::patch('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    });
    
    // Routes factures communes (admin + client)
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    
    // Routes factures clients
    Route::middleware('role:client')->group(function () {
        Route::get('/my-invoices', [InvoiceController::class, 'clientInvoices'])->name('invoices.client');
    });
});

require __DIR__.'/auth.php';
