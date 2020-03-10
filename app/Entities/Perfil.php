<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model 
{
    protected $table = 'perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'descricao', 'status'
    ];

}