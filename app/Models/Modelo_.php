<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    
    protected $table = 'modelos';

    protected $fillable = ['modelo_name', 'modelo_slug','marca_id'];

    public function listingModelos()
    {
        return $this->hasMany(ListingModelo::class, 'modelo_id');
    }
}
