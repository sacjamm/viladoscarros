<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    protected $table = 'banners';
    
    protected $fillable = ['title', 'description', 'image', 'is_active','largura','altura','largura_768','altura_768','largura_480','altura_480'];
}
