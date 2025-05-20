<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App_Cidades extends Model
{
    use HasFactory;
    protected $table = 'app_cidades';
    // Define os campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'cidade_id',
        'estado_id',
        'cidade_nome',
        'cidade_uf',
    ];
}
