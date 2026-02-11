<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    // Affiche le formulaire de création
    public function create()
    {
        return view('admin.meals.create');
    }

    // Enregistre le plat dans la base
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Meal::create([
            'name' => $request->name,
            'price' => $request->price,
            'available_at' => now(), // Pour le menu du jour
        ]);

        return redirect()->route('dashboard')->with('success', 'Plat ajouté au menu !');
    }
}

