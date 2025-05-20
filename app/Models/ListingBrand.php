<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingBrand extends Model
{
    protected $fillable = [
        'listing_brand_name',
        'listing_brand_slug',
        'listing_brand_photo',
        'listing_brand_photo_png',
        'seo_title',
        'seo_meta_description',
        'marca_id',
        'canal',
    ];

    public function rListing() {
        return $this->hasMany( Listing::class, 'listing_brand_id', 'id' );
    }

}