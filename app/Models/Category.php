<?php

namespace App\Models;
use App\Models\User;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function category_products(){
        return $this->hasMany(Product::class);
    }
}
