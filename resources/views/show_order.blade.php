@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">{{ __('Order') }}</div>
                    @php
                        $total_price=0;
                    @endphp

                    <div class="card-body">
                        <h5 class="card-title">Order ID {{ $order->id }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">By {{ $order->user->name }}</h6>

                        @if ($order->is_paid == true)
                            <p class="card-text">Paid</p>
                        @else
                            <p class="card-text">Unpaid</p>
                        @endif

                        <hr>

                        
                        {{-- @foreach ($order->transactions as $transaction)
                            @php 
                                $product = $transaction->product;
                            @endphp
                            @if ($order->is_paid == true && $transaction->is_rated == false && $order->payment_receipt != null && !Auth::user()->is_admin)
                                <form action="{{ route('create_rating', [$order , $transaction, $product]) }}" method="get">
                                    <a href="{{ route('show_product', $product) }}">
                                        <h6>{{ $transaction->product->name }}-{{ $transaction->amount }} pcs
                                    </a>
                                    ,   @currency(( $transaction->amount * $transaction->product->price))</h6>
                                    <button type="submit" class="btn btn-primary">Rate this product</button>
                                </form>
                                <br>
                                <hr>
                            @endif
                        @endforeach --}}

                        @foreach ($order->transactions as $transaction)
                            @php
                                $total_price += $transaction->product->price*$transaction->amount;
                                $product = $transaction->product;
                                $user = Auth::user();
                                $ever_order = false;
                            @endphp
                            @if ($order->is_paid == true && $transaction->is_rated == false && $order->payment_receipt != null && !Auth::user()->is_admin)
                                @if ($user->ratings->where('product_id', $product->id)->count() > 0)
                                    @php
                                        $ever_order = true;
                                    @endphp
                                @endif
                            @endif
                            @if ($ever_order == false && $order->is_paid == true && $transaction->is_rated == false && $order->payment_receipt != null && !Auth::user()->is_admin)
                                <div class="rating">
                                    <h6>Rate this product →    
                                        <a href="{{ route('show_product', $product) }}">
                                            <b>{{ $transaction->product->name }} </b>
                                        </a>({{ $transaction->amount }} pcs), @currency(( $transaction->amount * $transaction->product->price))
                                    </h6>
                                    {{-- <form action="{{ route('show_product', $product) }}" method="get">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Rate this product</button>
                                    </form> --}}
                                    <form action="{{ route('submit_rating', [$product, $order , $transaction]) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <div class="form-group-star">
                                                <input type="radio" id="star5" name="rate" value="5" />
                                                <label for="star5" title="rating star">5 stars</label>
                                                <input type="radio" id="star4" name="rate" value="4" />
                                                <label for="star4" title="rating star">4 stars</label>
                                                <input type="radio" id="star3" name="rate" value="3" />
                                                <label for="star3" title="rating star">3 stars</label>
                                                <input type="radio" id="star2" name="rate" value="2" />
                                                <label for="star2" title="rating star">2 stars</label>
                                                <input type="radio" id="star1" name="rate" value="1" />
                                                <label for="star1" title="rating star">1 star</label>
                                                </div>
                                        </div> 
                                        <div class="form-group">
                                            <input type="text" name="review" placeholder="Review" class="form-control">
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Submit your rating</button>
                                    </form>
                                    <hr>
                                </div>
                            @elseif ($ever_order == true && $order->is_paid == true && $transaction->is_rated == false && $order->payment_receipt != null && !Auth::user()->is_admin)
                                <a href="{{ route('show_product', $product) }}">
                                    <h6><b>{{ $transaction->product->name }} </b>
                                </a>  ({{ $transaction->amount }} pcs), @currency(( $transaction->amount * $transaction->product->price)). You have rated this product</h6>
                                {{-- <form action="{{ route('update_rating', [$product, $order , $transaction]) }}" method="post">
                                    @method('patch')
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-group-star">
                                            <input type="radio" id="star5" name="rate" value="5" />
                                            <label for="star5" title="rating star">5 stars</label>
                                            <input type="radio" id="star4" name="rate" value="4" />
                                            <label for="star4" title="rating star">4 stars</label>
                                            <input type="radio" id="star3" name="rate" value="3" />
                                            <label for="star3" title="rating star">3 stars</label>
                                            <input type="radio" id="star2" name="rate" value="2" />
                                            <label for="star2" title="rating star">2 stars</label>
                                            <input type="radio" id="star1" name="rate" value="1" />
                                            <label for="star1" title="rating star">1 star</label>
                                            </div>
                                    </div>    
                                    <div class="form-group">
                                        <input type="text" name="review" placeholder="Review" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Update your rating</button>
                                </form> --}}
                                <hr>
                            @elseif($transaction->is_rated == true)
                                <a href="{{ route('show_product', $product) }}">
                                    <h6><b>{{ $transaction->product->name }} </b>
                                </a>  ({{ $transaction->amount }} pcs), @currency(( $transaction->amount * $transaction->product->price)). You have rated this product</h6>
                                {{-- <form action="{{ route('update_rating', [$product, $order , $transaction]) }}" method="post">
                                    @method('patch')
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-group-star">
                                            <input type="radio" id="star5" name="rate" value="5" />
                                            <label for="star5" title="rating star">5 stars</label>
                                            <input type="radio" id="star4" name="rate" value="4" />
                                            <label for="star4" title="rating star">4 stars</label>
                                            <input type="radio" id="star3" name="rate" value="3" />
                                            <label for="star3" title="rating star">3 stars</label>
                                            <input type="radio" id="star2" name="rate" value="2" />
                                            <label for="star2" title="rating star">2 stars</label>
                                            <input type="radio" id="star1" name="rate" value="1" />
                                            <label for="star1" title="rating star">1 star</label>
                                        </div>
                                    </div>    
                                    <div class="form-group">
                                        <input type="text" name="review" placeholder="Review" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Update your rating</button>
                                </form> --}}
                                <hr>
                            @else
                                <a href="{{ route('show_product', $product) }}">
                                    <h6><b>{{ $transaction->product->name }} </b>
                               </a>  ({{ $transaction->amount }} pcs), @currency(( $transaction->amount * $transaction->product->price)) </h6>
                            @endif
                            {{-- <hr> --}}
                        @endforeach

                        @php
                            $total_price += $transaction->product->price*$transaction->amount;
                        @endphp
                        <p>Total: @currency(($total_price))</p>
                        
                        @if ($order->is_paid == false && $order->payment_receipt == null && !Auth::user()->is_admin)
                            <p>Pay to this Account Number:  BANK BRI <b>0320-01-013785-50-6</b> a.n AGUS SUPRIANTO</p>
                            <hr>
                            <form action="{{ route('submit_payment_receipt', $order) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                <div class="form-group">
                                    <label>Upload your payment receipt</label>
                                    <input type="file" name="payment_receipt" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Submit payment</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection