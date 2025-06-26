<?php

namespace App\Http\Controllers;

use App\Models\TaskReport;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskReportController extends Controller
{
    /**
     * Afficher le formulaire d'ajout de rapport de tâche
     */
    public function create(ServiceRequest $serviceRequest)
    {
        // Vérifier que le technicien est assigné à cette demande
        if ($serviceRequest->technicien_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à ajouter un rapport pour cette demande.');
        }

        // Vérifier que la demande est terminée
        if ($serviceRequest->status !== 'terminee') {
            return redirect()->back()->with('error', 'Vous ne pouvez ajouter un rapport que pour une demande terminée.');
        }

        return view('task-reports.create', compact('serviceRequest'));
    }

    /**
     * Enregistrer le rapport de tâche
     */
    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        // Vérifier que le technicien est assigné à cette demande
        if ($serviceRequest->technicien_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string|min:20',
            'duration_minutes' => 'nullable|integer|min:1|max:1440', // Max 24h
            'material_cost' => 'nullable|numeric|min:0|max:999999',
            'materials_used' => 'nullable|string|max:1000',
            'difficulty' => 'required|in:facile,normale,difficile,complexe',
            'before_photos.*' => 'nullable|image|max:2048',
            'after_photos.*' => 'nullable|image|max:2048',
            'observations' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|string|max:1000',
        ]);

        // Traitement des photos avant
        $beforePhotos = [];
        if ($request->hasFile('before_photos')) {
            foreach ($request->file('before_photos') as $photo) {
                $path = $photo->store('task-reports/before', 'public');
                $beforePhotos[] = $path;
            }
        }

        // Traitement des photos après
        $afterPhotos = [];
        if ($request->hasFile('after_photos')) {
            foreach ($request->file('after_photos') as $photo) {
                $path = $photo->store('task-reports/after', 'public');
                $afterPhotos[] = $path;
            }
        }

        TaskReport::create([
            'service_request_id' => $serviceRequest->id,
            'technicien_id' => Auth::id(),
            'task_title' => $validated['task_title'],
            'task_description' => $validated['task_description'],
            'duration_minutes' => $validated['duration_minutes'],
            'material_cost' => $validated['material_cost'] ?? 0,
            'materials_used' => $validated['materials_used'],
            'difficulty' => $validated['difficulty'],
            'before_photos' => $beforePhotos,
            'after_photos' => $afterPhotos,
            'observations' => $validated['observations'],
            'recommendations' => $validated['recommendations'],
        ]);

        return redirect()->route('service-requests.show', $serviceRequest)
            ->with('success', 'Rapport de tâche ajouté avec succès. L\'administrateur peut maintenant créer une facture.');
    }

    /**
     * Afficher un rapport de tâche
     */
    public function show(TaskReport $taskReport)
    {
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role !== 'admin' && 
            $taskReport->technicien_id !== $user->id && 
            $taskReport->serviceRequest->client_id !== $user->id) {
            abort(403);
        }

        $taskReport->load(['serviceRequest', 'technicien']);
        
        return view('task-reports.show', compact('taskReport'));
    }

    /**
     * Modifier un rapport de tâche (technicien seulement)
     */
    public function edit(TaskReport $taskReport)
    {
        if ($taskReport->technicien_id !== Auth::id()) {
            abort(403);
        }

        // Ne pas permettre la modification si une facture a été créée
        if ($taskReport->serviceRequest->invoice) {
            return redirect()->back()->with('error', 'Impossible de modifier le rapport, une facture a déjà été créée.');
        }

        return view('task-reports.edit', compact('taskReport'));
    }

    /**
     * Mettre à jour un rapport de tâche
     */
    public function update(Request $request, TaskReport $taskReport)
    {
        if ($taskReport->technicien_id !== Auth::id()) {
            abort(403);
        }

        // Ne pas permettre la modification si une facture a été créée
        if ($taskReport->serviceRequest->invoice) {
            return redirect()->back()->with('error', 'Impossible de modifier le rapport, une facture a déjà été créée.');
        }

        $validated = $request->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string|min:20',
            'duration_minutes' => 'nullable|integer|min:1|max:1440',
            'material_cost' => 'nullable|numeric|min:0|max:999999',
            'materials_used' => 'nullable|string|max:1000',
            'difficulty' => 'required|in:facile,normale,difficile,complexe',
            'before_photos.*' => 'nullable|image|max:2048',
            'after_photos.*' => 'nullable|image|max:2048',
            'observations' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|string|max:1000',
        ]);

        // Traitement des nouvelles photos avant
        $beforePhotos = $taskReport->before_photos ?? [];
        if ($request->hasFile('before_photos')) {
            foreach ($request->file('before_photos') as $photo) {
                $path = $photo->store('task-reports/before', 'public');
                $beforePhotos[] = $path;
            }
        }

        // Traitement des nouvelles photos après
        $afterPhotos = $taskReport->after_photos ?? [];
        if ($request->hasFile('after_photos')) {
            foreach ($request->file('after_photos') as $photo) {
                $path = $photo->store('task-reports/after', 'public');
                $afterPhotos[] = $path;
            }
        }

        $taskReport->update([
            'task_title' => $validated['task_title'],
            'task_description' => $validated['task_description'],
            'duration_minutes' => $validated['duration_minutes'],
            'material_cost' => $validated['material_cost'] ?? 0,
            'materials_used' => $validated['materials_used'],
            'difficulty' => $validated['difficulty'],
            'before_photos' => $beforePhotos,
            'after_photos' => $afterPhotos,
            'observations' => $validated['observations'],
            'recommendations' => $validated['recommendations'],
        ]);

        return redirect()->route('task-reports.show', $taskReport)
            ->with('success', 'Rapport de tâche mis à jour avec succès.');
    }
}
