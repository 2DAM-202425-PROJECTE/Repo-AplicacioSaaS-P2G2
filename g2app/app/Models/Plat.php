<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static factory()
 */
class Plat extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nom',
        'id_restaurant',
        'descripcio',
        'preu',
        'id_categoria',
        'foto',
        'vegetaria',
        'vega',
        'carn_vermella',
        'gluten',
        'lactics',
        'kosher',
        'halal',
        'keto',
        'crustaci',
        'ous',
        'lupines',
        'mostassa',
        'cacahuats',
        'soja',

    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant', 'id');
    }

}
