<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Perfil
 * @package App\Entities
 *
 * @method get()
 * @method static create(array $array)
 */
class Perfil extends Model
{
    /**
     * @var string
     */
    protected string $table = 'perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'descricao',
        'status'
    ];
}
