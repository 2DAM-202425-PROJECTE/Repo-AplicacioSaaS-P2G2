<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail($id)
 */
class Restaurant extends Model
{
    use HasFactory;

    protected $casts = [
        'tipus_cuina' => 'array',
    ];
    protected $fillable = [
        'id_ubicacio',
        'nom',
        'horari',
        'descripcio',
        'telefon',
        'tipus_cuina',
        'hora_obertura',
        'hora_tancament',
    ];

    public $timestamps = false;

    public static $TIPUS_CUINA = [
        'mediterrania',
        'italiana',
        'japonesa',
        'xinesa',
        'mexicana',
        'francesa',
    ];
}
