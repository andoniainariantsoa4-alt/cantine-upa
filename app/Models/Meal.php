<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    // Ajoute 'stock' ici !

    protected $fillable = ['name', 'price', 'stock', 'image'];
}
