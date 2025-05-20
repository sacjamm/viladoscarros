<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model {

    use HasFactory;

    // Define o nome da tabela, caso seja diferente da convenção padrão
    protected $table = 'additionals';
    // Define os campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'additional_name',
        'additional_slug',
        'opcional_id',
    ];
}
