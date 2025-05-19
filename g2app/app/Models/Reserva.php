<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reserves';

    const PENDENT = 0;
    const CONFIRMAT = 1;
    const COMPLETAT = 3;
    const CANCELAT = 2;

    protected $fillable = [
        //'id_usuari',
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


    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant');
    }

    public function getNomEstatAttribute(): string
    {
        return match ($this->estat) {
            self::PENDENT => 'Pendent',
            self::CONFIRMAT => 'Confirmat',
            self::COMPLETAT => 'Completat',
            self::CANCELAT => 'Cancel·lat',
            default => 'Desconegut',
        };
    }
}
