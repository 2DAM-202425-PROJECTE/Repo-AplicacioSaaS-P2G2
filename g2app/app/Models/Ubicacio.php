<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $id)
 */
class Ubicacio extends Model
{
    use HasFactory;

    protected $table = 'ubicacio';

    protected $primaryKey = 'id_ubicacio';
    public $timestamps = false;
    protected $fillable = [
        //'comunitat',
        'provincia',
        'municipi',
        'codi_postal',
       // 'latitud',
        //'longitud',
    ];
}
