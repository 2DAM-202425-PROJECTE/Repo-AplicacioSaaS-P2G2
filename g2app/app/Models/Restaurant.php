<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail($id)
 */
class Restaurant extends Model
{
    use HasFactory;

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function usuari(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function plats()
    {
        return $this->hasMany(Plat::class, 'id_restaurant', 'id');
    }
    protected $fillable = [
        'nom',
        'municipio_id',
        'horari',
        'descripcio',
        'telefon',
        'tipus_cuina',
        'hora_obertura',
        'hora_tancament',
        'user_id',
        'carrer',
        'profile_image',
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
    public static array $noms = [

        "El Rincón de la Paella",
        "La Tasquita Andaluza",
        "Restaurante Japonés Sakura",
        "Pizzeria Italiana Bella Napoli",
        "El Vegetariano Feliz",
        "El Far de la Costa Brava",
        "La Bodega del Sol",
        "Casa Pepe",
        "Restaurante La Rambla",
        "El Mesón de Don Quijote",
        "La Taberna del Puerto",
        "El Jardí de l'Àvia",
        "Can Culleretes",
        "Els Quatre Gats",
        "La Fonda de la Plaça",
        "Trattoria Romana",
        "Pizzeria Il Forno",
        "Ristorante La Piazza",
    ];

}
