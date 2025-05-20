<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App_Estados extends Model
{
    use HasFactory;
    protected $table = 'app_estados';
    // Define os campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'estado_id',
        'estado_nome',
        'estado_uf',
        'estado_regiao',
    ];
}
