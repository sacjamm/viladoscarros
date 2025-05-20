<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Follower;

class User extends Authenticatable {

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cnpj',
        'phone',
        'country',
        'address',
        'state',
        'city',
        'zip',
        'website',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'pinterest',
        'youtube',
        'photo',
        'banner',
        'password',
        'token',
        'status'
    ];

    public function rPurchasePackage() {
        return $this->hasMany(PackagePurchase::class, 'user_id', 'id');
    }

    public function vendas() {
        return $this->hasMany(Venda::class, 'user_id', 'id');
    }

    public function isFollowing($userId) {
        return Follower::where('user_id', $userId)
                        ->where('follower_id', auth()->id())
                        ->exists();
    }
}
