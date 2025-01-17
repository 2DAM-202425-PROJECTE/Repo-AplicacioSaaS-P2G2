<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_ubicacio',
        'nom',
        'horari',
        'descripcio',
        'telefon',
        'tipus_cuina',
    ];
}
