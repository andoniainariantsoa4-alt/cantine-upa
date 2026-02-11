<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('meals', 'public');
        }

        Meal::create($data);
        return back()->with('success', 'Plat ajouté !');
    }

    public function destroy(Meal $meal)
    {
        if ($meal->image) {
            Storage::disk('public')->delete($meal->image);
        }
        $meal->delete();
        return back()->with('success', 'Plat supprimé !');
    }
}
