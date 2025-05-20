<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model {

    protected $fillable = [
        'id',
        'ramal',
        'source',
        'name',
        'phone',
        'phone2',
        'email',
        'city',
        'neighbourhood',
        'prop_ref',
        'type_negotiation',
        'brand',
        'model',
        'description',
        'price',
        'url',
        'body', 
        'created_at',
        'updated_at',
        'status',
    ];
} 
