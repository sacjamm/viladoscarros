<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageHomeItem extends Model
{
    protected $fillable = [
        'seo_title',
        'seo_meta_description',
        'search_heading',
        'search_text',
        'search_background',
        'brand_heading',
        'brand_subheading',
        'brand_total',
        'brand_status',
        'video_heading',
        'video_subheading',
        'video_youtube_id',
        'video_background',
        'video_status',
        'listing_heading',
        'listing_subheading',
        'listing_total',
        'listing_status',
        'testimonial_heading',
        'testimonial_subheading',
        'testimonial_background',
        'testimonial_status',
        'location_heading',
        'location_subheading',
        'location_total',
        'location_status'
    ];

}
