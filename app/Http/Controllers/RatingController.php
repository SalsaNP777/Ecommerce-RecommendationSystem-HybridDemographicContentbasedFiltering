<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;

class RatingController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function create_rating(){
        return view('create_rating');
    }
    
    public function edit_rating(){
        return view('edit_rating');
    }

    public function index_rating(){
        //display rating from the latest
    }

    public function submit_rating(Request $request, Product $product, Order $order, Transaction $transaction){
        $user_id = Auth::id();
        $transaction_id = $transaction->id;
        $product_id = $product->id;
        $order_id = $order->id;

        $request->validate([
            'rating'=>'nullable|gte:1|lte:5',
            'comment'=>'nullable'
        ]);

        Rating::create([
            'user_id'=>$user_id,
            'product_id'=>$product_id,
            'order_id'=>$order_id,
            'transaction_id'=>$transaction_id,
            'rate'=>$request->rate,
            'review'=>$request->review
        ]);
        
        $transaction->update([
            'is_rated'=>true
        ]);

        return Redirect::route('show_product', $product);
    }

    // public function submit_rating(Request $request, Product $product, Order $order){
    //     $user_id = Auth::id();
    //     $product_id = $product->id;
    //     $order_id = $order->id;

    //     $request->validate([
    //         'rating'=>'required|gte:1|lte:5',
    //         'comment'=>'nullable'
    //     ]);

    //     Rating::create([
    //         'user_id'=>$user_id,
    //         'product_id'=>$product_id,
    //         'order_id'=>$order_id,
    //         'rating'=>$request->rating,
    //         'comment'=>$request->comment
    //     ]);

    //     $order->update([
    //         'is_rated'=>true
    //     ]);

    //     return Redirect::route('show_order', $order_id);
    // }
}

?>