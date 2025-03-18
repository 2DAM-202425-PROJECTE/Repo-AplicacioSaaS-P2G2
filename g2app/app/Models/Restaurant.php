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


    protected $fillable = [
//        'id_ubicacio',
        'nom',
        'horari',
        'descripcio',
        'telefon',
        'tipus_cuina',
        'hora_obertura',
        'hora_tancament',
        'user_id',
    ];

    public $timestamps = false;

    public static array $TIPUS_CUINA = [
        'Mediterrania',
        'Italiana',
        'Japonesa',
        'Xinesa',
        'Mexicana',
        'Francesa',
        'India',
    ];

}
