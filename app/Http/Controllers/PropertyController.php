<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin voit toutes les propriétés avec filtres
            $query = Property::with('user');
            
            if (request('status')) {
                $query->where('status', request('status'));
            }
            
            $properties = $query->latest()->paginate(12);
        } else {
            // Client voit seulement ses propriétés
            $properties = $user->properties()->latest()->paginate(12);
        }
        
        return view('properties.index', compact('properties'));
    }

    /**
     * Affichage public des annonces validées
     */
    public function public()
    {
        $query = Property::with('user')->where('status', 'validee');
        
        // Appliquer les filtres
        if (request('type')) {
            $query->where('type', request('type'));
        }
        
        if (request('property_type')) {
            $query->where('property_type', request('property_type'));
        }
        
        if (request('city')) {
            $query->where('city', 'like', '%' . request('city') . '%');
        }
        
        if (request('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }
        
        $properties = $query->latest('validated_at')->paginate(12);
            
        return view('properties.public', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'en_attente';
        
        // Gestion des images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }
        
        Property::create($data);
        
        return redirect()->route('properties.index')
            ->with('success', 'Votre annonce a été soumise et est en cours de validation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        // Vérification des permissions
        if ($property->status !== 'validee' && Auth::user()->role !== 'admin' && $property->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        // Seul le propriétaire ou l'admin peut modifier
        if ($property->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        // Vérification des permissions
        if ($property->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $data = $request->validated();
        
        // Gestion des images
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images
            if ($property->images) {
                foreach ($property->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }
        
        // Si c'est un client qui modifie, remettre en attente
        if (Auth::user()->role !== 'admin' && $property->status === 'validee') {
            $data['status'] = 'en_attente';
            $data['validated_at'] = null;
        }
        
        $property->update($data);
        
        return redirect()->route('properties.index')
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Seul le propriétaire ou l'admin peut supprimer
        if ($property->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Supprimer les images
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $property->delete();
        
        return redirect()->route('properties.index')
            ->with('success', 'Annonce supprimée avec succès.');
    }

    /**
     * Valider une annonce (Admin seulement)
     */
    public function validate(Property $property)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $property->update([
            'status' => 'validee',
            'validated_at' => now(),
        ]);
        
        return back()->with('success', 'Annonce validée avec succès.');
    }

    /**
     * Rejeter une annonce (Admin seulement)
     */
    public function reject(Request $request, Property $property)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $request->validate([
            'admin_comment' => 'required|string|max:500'
        ]);
        
        $property->update([
            'status' => 'rejetee',
            'admin_comment' => $request->admin_comment,
        ]);
        
        return back()->with('success', 'Annonce rejetée.');
    }

    /**
     * Generate PDF for property
     */
    public function pdf(Property $property)
    {
        // Vérification des permissions
        if ($property->status !== 'validee' && Auth::user()->role !== 'admin' && $property->user_id !== Auth::id()) {
            abort(403);
        }

        // Charger les relations nécessaires
        $property->load('user');

        // Générer le PDF
        $pdf = Pdf::loadView('properties.pdf', compact('property'));
        
        // Nom du fichier
        $filename = 'propriete-' . $property->id . '-' . Str::slug($property->title) . '.pdf';
        
        // Retourner le PDF pour téléchargement
        return $pdf->download($filename);
    }

    /**
     * Marquer une propriété comme louée
     */
    public function markAsRented(Property $property)
    {
        // Seul le propriétaire ou l'admin peut marquer comme loué
        if ($property->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Vérifier que la propriété est de type location et validée
        if ($property->type !== 'location') {
            return back()->with('error', 'Cette action n\'est disponible que pour les propriétés en location.');
        }

        if ($property->status !== 'validee') {
            return back()->with('error', 'Seules les propriétés validées peuvent être marquées comme louées.');
        }

        $property->update([
            'status' => 'louee',
        ]);

        return back()->with('success', 'Propriété marquée comme louée. Elle n\'apparaîtra plus dans les annonces publiques.');
    }

    /**
     * Marquer une propriété comme vendue
     */
    public function markAsSold(Property $property)
    {
        // Seul le propriétaire ou l'admin peut marquer comme vendu
        if ($property->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Vérifier que la propriété est de type vente et validée
        if ($property->type !== 'vente') {
            return back()->with('error', 'Cette action n\'est disponible que pour les propriétés en vente.');
        }

        if ($property->status !== 'validee') {
            return back()->with('error', 'Seules les propriétés validées peuvent être marquées comme vendues.');
        }

        $property->update([
            'status' => 'vendue',
        ]);

        return back()->with('success', 'Propriété marquée comme vendue. Elle n\'apparaîtra plus dans les annonces publiques.');
    }

    /**
     * Remettre une propriété en ligne (Admin seulement)
     */
    public function reactivate(Property $property)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Vérifier que la propriété est vendue ou louée
        if (!in_array($property->status, ['vendue', 'louee'])) {
            return back()->with('error', 'Cette action n\'est disponible que pour les propriétés vendues ou louées.');
        }

        $property->update([
            'status' => 'validee',
        ]);

        return back()->with('success', 'Propriété remise en ligne avec succès.');
    }
}
