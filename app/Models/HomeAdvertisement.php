<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeAdvertisement extends Model
{
    protected $fillable = [
        'above_brand_1',
        'above_brand_1_url',
        'above_brand_2',
        'above_brand_2_url',
        'above_brand_status',
        'above_featured_listing_1',
        'above_featured_listing_1_url',
        'above_featured_listing_2',
        'above_featured_listing_2_url',
        'above_featured_listing_status',
        'above_location_1',
        'above_location_1_url',
        'above_location_2',
        'above_location_2_url',
        'above_location_status'
    ];

}
