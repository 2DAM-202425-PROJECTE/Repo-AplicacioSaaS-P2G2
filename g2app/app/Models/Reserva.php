<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reserves';
    public $timestamps = false;

    const PENDENT = 0;
    const CONFIRMAT = 1;
    const COMPLETAT = 2;
    const CANCELAT = 3;

    protected $fillable = [
        //'id_usuari',
        'id_taula',
        'id_restaurant',
        'telefon',
        'data',
        'hora',
        'num_persones',
        'estat',
        'terrassa',
        'solicituds'

    ];

    public function usuari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuari');
    }


    public function taula(): BelongsTo
    {
        return $this->belongsTo(Taula::class, 'id_taula');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant');
    }

}
