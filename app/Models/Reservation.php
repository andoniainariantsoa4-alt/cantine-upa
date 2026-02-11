<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Autoriser le remplissage de ces champs
    protected $fillable = [
        'user_id',
        'meal_id',
    ];

    /**
     * Relation avec l'Utilisateur (L'étudiant qui réserve)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le Plat (Le repas réservé)
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
