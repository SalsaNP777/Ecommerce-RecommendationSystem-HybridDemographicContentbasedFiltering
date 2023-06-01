<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'stock',
        'total_votes',
        'average_rating'
    ];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}
?>