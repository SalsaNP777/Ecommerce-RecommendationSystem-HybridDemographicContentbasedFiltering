<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\Promise\all;

class ProductController extends Controller
{
    public function create_product(){
        return view('create_product');
    }

    public function store_product(Request $request){
        $request->validate([
            'name'=>'required',
            'price'=>'required',
            'description'=>'required',
            'image'=>'required',
            'stock'=>'required'
        ]);

        $file = $request->file('image');
        $path = time().'_'.$request->name.'.'.$file->getClientOriginalExtension();

        Storage::disk('local')->put('public/'.$path, file_get_contents($file));

        Product::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description,
            'image'=>$path,
            'stock'=>$request->stock
        ]);
        
        return Redirect::route('index_product'); 
    }
    
    public function index_product(Product $product, Rating $rating){
        if(request('search')){
            $products = Product::where('name', 'like', '%'.request('search').'%')->get();
        }
        else{
            $products = Product::all();
            foreach($products as $product){
                $user = Auth::user();
                $user_id = Auth::id();
                if(Auth::check()){
                    // if user ever order any product
                    if(Order::where('user_id', $user_id)->exists()){
                        $user = User::find($user_id);
                        $bought_products = Order::join('transactions', 'orders.id' , '=', 'transactions.order_id')
                                                ->join('products', 'transactions.product_id', '=', 'products.id')
                                                ->where('orders.user_id', $user_id)
                                                ->pluck('products.name');
                        //product to compare
                        $product_id = $product->id;
                        $productToCompare = Product::find($product_id);
                        $similarityScore = [];
                        foreach ($bought_products as $product_name) {
                            //case folding (preprocessing)
                            $text1 = strtolower($product->name);
                            $text2 = strtolower($product_name);
                            //tokenizing (preprocessing)
                            $words1 = preg_split('/\s+/', $text1, -1, PREG_SPLIT_NO_EMPTY);
                            $words2 = preg_split('/\s+/', $text2, -1, PREG_SPLIT_NO_EMPTY);
                            //count word frequency (menghitung jumlah kata), with create vectors for the texts
                            $vector1 = array_count_values($words1);
                            $vector2 = array_count_values($words2);
                            //Cosine Distance Calculation
                            //calculate the dot product vectors
                            $dotProduct = 0;
                            foreach ($vector1 as $word => $count1) {
                                if (isset($vector2[$word])) {
                                    $dotProduct += $count1 * $vector2[$word];
                                }
                            }
                            // Calculate the magnitude of the vectors
                            $magnitude1 = sqrt(array_sum(array_map(function ($count) { return $count * $count; }, $vector1)));
                            $magnitude2 = sqrt(array_sum(array_map(function ($count) { return $count * $count; }, $vector2)));
                            // Calculate the Cosine Distance
                            if ($magnitude1 == 0 || $magnitude2 == 0) {
                                $similarityScore[$product_name] = 0;
                            } else {
                                $similarityScore[$product_name] = $dotProduct / ($magnitude1 * $magnitude2);
                            }
                            $product->filtering = $similarityScore;
                        }
                    }else{
                        //Demographic Filtering using IMDB's weighted rating formula
                        $rating = Rating::where('product_id', $product->id)->get();
                        $v = $product->total_votes;
                        $r = $product->average_rating;
                        $m = 0.9; //based on journals
                        $c = $product->avg('average_rating');
                        $product->filtering = ($v/($v+$m))*$r+($m/($v+$m))*$c;
                    }
                }else{
                    //Demographic Filtering using IMDB's weighted rating formula
                    $rating = Rating::where('product_id', $product->id)->get();
                    $v = $product->total_votes;
                    $r = $product->average_rating;
                    $m = 0.9; //based on journals
                    $c = $product->avg('average_rating');
                    $product->filtering = ($v/($v+$m))*$r+($m/($v+$m))*$c;
                }
            }
            $products = $products->sortByDesc('filtering')->values();
        }
        return view('index_product', compact('products'));
    }

    public function show_product(Product $product){
        $rating = Rating::with('user')->where('product_id', $product->id)->orderBy('created_at', 'desc')->get();
        return view('show_product', compact('product'));
    }

    public function edit_product(Product $product){
        return view('edit_product', compact('product'));
    }

    public function update_product(Product $product, Request $request){
        $request->validate([
            'name'=>'required',
            'price'=>'required',
            'description'=>'required',
            'image'=>'required',
            'stock'=>'required'
        ]);

        $file = $request->file('image');
        $path = time().'_'.$request->name.'.'.$file->getClientOriginalExtension();

        Storage::disk('local')->put('public/'.$path, file_get_contents($file));

        $product->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description,
            'stock'=>$request->stock,
            'image'=>$path
        ]);
        
        return Redirect::route('show_product', $product); 
    }

    public function delete_product(Product $product){
        $product->delete();
        return Redirect::route('index_product');
    }

    public function submit_rating(Request $request, Product $product, Order $order, Transaction $transaction){
        $user_id = Auth::id();
        $rating = Rating::where('user_id', $user_id)->where('product_id', $product->id)->first();
        $product_id = $product->id;
        $order_id = $order->id;
        $transaction_id = $transaction->id;

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

        $product->update([
            'total_votes'=>$product->total_votes+1,
            'average_rating'=>($product->average_rating+($request->rate-$product->average_rating)/($product->total_votes+1)),
        ]);

        return Redirect::route('show_product', $product);
    }
}
?>