<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Models\Meal;
use App\Models\Reservation;

// Accueil redirige vers le dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// TOUTES LES ROUTES PROTEGÉES (AUTH)
Route::middleware('auth')->group(function () {

    // Le Dashboard unique
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'meals' => Meal::all(),
            'reservations' => Reservation::with(['user', 'meal'])->get()
        ]);
    })->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des Plats (Meals)
    Route::post('/meals', [MealController::class, 'store'])->name('meals.store');
    Route::delete('/meals/{meal}', [MealController::class, 'destroy'])->name('meals.destroy');

    // Gestion des Réservations
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/clear', [ReservationController::class, 'clearAll'])->name('reservations.clear');

}); // <--- C'est cette fermeture qui manquait !

require __DIR__.'/auth.php';
