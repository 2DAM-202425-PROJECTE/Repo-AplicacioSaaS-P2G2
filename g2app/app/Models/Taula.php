<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, array|string|null $restaurantId)
 */
class Taula extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_taula';

    protected $table = 'taules';

    protected $fillable = [
        'id_restaurant',
        'terrassa',
        'capacitat',
        'disponible',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'id_restaurant');
    }
}
