<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create(Room $room)
    {
        return view('reservations.create', compact('room'));
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'capacity' => 'required|integer|min:1|max:' . $room->capacity,
        ]);

        $nights = \Carbon\Carbon::parse($validated['check_in'])
            ->diffInDays(\Carbon\Carbon::parse($validated['check_out']));

        $conflict = Reservation::where('room_id', $room->id)
            ->whereIn('status', ['en_attente', 'confirmee'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                    ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('check_in', '<=', $validated['check_in'])
                          ->where('check_out', '>=', $validated['check_out']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['check_in' => 'Cette chambre est déjà réservée sur cette période.']);
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'nights' => $nights,
            'total_price' => $nights * $room->price,
            'status' => 'en_attente',
        ]);

        return redirect()->route('reservations.index')->with('success', 'Votre réservation a été envoyée avec succès !');
    }

    public function index()
    {
        $reservations = auth()->user()->reservations()->with('room')->latest()->get();

        return view('reservations.index', compact('reservations'));
    }

    public function cancel(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);

        if (\Carbon\Carbon::parse($reservation->check_in)->isPast()) {
            return back()->withErrors(['error' => 'Impossible d\'annuler une réservation déjà commencée.']);
        }

        $reservation->update(['status' => 'annulee']);

        return back()->with('success', 'Réservation annulée.');
    }
}
