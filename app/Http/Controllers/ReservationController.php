<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{

    public function store(Request $request)
{
    $meal = \App\Models\Meal::findOrFail($request->meal_id);

    if ($meal->stock <= 0) {
        return back()->with('error', 'Désolé, ce plat est épuisé !');
    }

    \App\Models\Reservation::create([
        'user_id' => auth()->id(),
        'meal_id' => $meal->id,
    ]);

    // On diminue le stock de 1
    $meal->decrement('stock');

    return back()->with('success', 'Votre réservation a été enregistrée !');
}

    public function clearAll()
    {
        Reservation::truncate();
        return back()->with('success', 'Commandes vidées !');
    }

}
