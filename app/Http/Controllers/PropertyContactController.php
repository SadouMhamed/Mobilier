<?php

namespace App\Http\Controllers;

use App\Models\PropertyContact;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index()
    {
        $query = PropertyContact::with(['property', 'sender', 'recipient']);

        // Filtrer par rôle
        if (Auth::user()->role === 'client') {
            $query->where('sender_id', Auth::id());
        } elseif (Auth::user()->role !== 'admin') {
            // Pour les propriétaires, voir les messages reçus pour leurs propriétés
            $propertyIds = Auth::user()->properties()->pluck('id');
            $query->where('recipient_id', Auth::id())
                  ->orWhereIn('property_id', $propertyIds);
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('property-contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created contact message.
     */
    public function store(Request $request, Property $property)
    {
        // Vérifier que l'utilisateur ne contacte pas sa propre annonce
        if ($property->user_id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas contacter votre propre annonce.');
        }

        $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'sender_phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        PropertyContact::create([
            'property_id' => $property->id,
            'sender_id' => Auth::id(),
            'recipient_id' => $property->user_id,
            'sender_name' => $request->sender_name,
            'sender_email' => $request->sender_email,
            'sender_phone' => $request->sender_phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Votre message a été envoyé avec succès ! Le propriétaire sera notifié.');
    }

    /**
     * Display the specified contact.
     */
    public function show(PropertyContact $propertyContact)
    {
        // Vérifier les permissions
        if (Auth::user()->role === 'client' && $propertyContact->sender_id !== Auth::id()) {
            abort(403);
        } elseif (Auth::user()->role !== 'admin' && 
                  $propertyContact->recipient_id !== Auth::id() && 
                  $propertyContact->sender_id !== Auth::id()) {
            abort(403);
        }

        // Marquer comme lu si c'est le destinataire qui regarde
        if ($propertyContact->recipient_id === Auth::id() && !$propertyContact->is_read) {
            $propertyContact->markAsRead();
        }

        $propertyContact->load(['property', 'sender', 'recipient']);

        return view('property-contacts.show', compact('propertyContact'));
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
     * Reply to a contact message.
     */
    public function reply(Request $request, PropertyContact $propertyContact)
    {
        // Seul le destinataire peut répondre
        if ($propertyContact->recipient_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        $propertyContact->reply($request->reply);

        return back()->with('success', 'Votre réponse a été envoyée avec succès !');
    }

    /**
     * Mark contact as read.
     */
    public function markAsRead(PropertyContact $propertyContact)
    {
        if ($propertyContact->recipient_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $propertyContact->markAsRead();

        return back()->with('success', 'Message marqué comme lu.');
    }

    /**
     * Get unread contacts count for current user.
     */
    public function unreadCount()
    {
        $count = 0;

        if (Auth::user()->role === 'admin') {
            $count = PropertyContact::unread()->count();
        } else {
            // Pour les propriétaires
            $propertyIds = Auth::user()->properties()->pluck('id');
            $count = PropertyContact::unread()
                ->where('recipient_id', Auth::id())
                ->orWhereIn('property_id', $propertyIds)
                ->count();
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Get received messages for property owners.
     */
    public function received()
    {
        if (Auth::user()->role === 'client') {
            abort(403);
        }

        $query = PropertyContact::with(['property', 'sender']);

        if (Auth::user()->role === 'admin') {
            // Admin voit tous les messages
        } else {
            // Propriétaire voit ses messages reçus
            $propertyIds = Auth::user()->properties()->pluck('id');
            $query->where('recipient_id', Auth::id())
                  ->orWhereIn('property_id', $propertyIds);
        }

        $receivedContacts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('property-contacts.received', compact('receivedContacts'));
    }

    /**
     * Delete a contact message (Admin only).
     */
    public function destroy(PropertyContact $propertyContact)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $propertyContact->delete();

        return back()->with('success', 'Message supprimé avec succès.');
    }
}
