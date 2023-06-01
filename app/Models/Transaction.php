<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'is_rated'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function rating(){
        return $this->hasOne(Rating::class);
    }
}
