<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versao extends Model
{
    use HasFactory;
     protected $table = 'versoes';

    protected $fillable = ['versao_name', 'versao_slug','versaoId','marcaId','modeloId','canal'];
}
