<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = RoomCategory::all();

        $availableRooms = Room::where('status', 'disponible')
            ->latest()
            ->take(6)
            ->get();

        $popularRooms = Room::where('status', 'disponible')
            ->withCount('reservations')
            ->orderByDesc('reservations_count')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'availableRooms', 'popularRooms'));
    }
}
