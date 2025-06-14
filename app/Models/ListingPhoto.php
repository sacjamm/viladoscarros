<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingPhoto extends Model
{
    protected $fillable = [
        'listing_id',
        'photo',
        'photo_name_original',
        'listing_image_alterada_admin',
        'canal'
    ];

}
