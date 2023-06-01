@extends('layouts.app')

@section('content')
    <div class="container" style="max-width:100%">
        <div class="row justify-content-center">
            
            {{-- <div class="container" style="width:100%"> --}}
            <div class="col-md-8" style="width: 100%">
                <div class="card">
                    <div class="card-header">{{ __(' Cart Product Detail') }}</div>
                        <div class="card-group m-auto d-flex justify-content-center">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            @endif
                            @php 
                                $total_price=0;   
                            @endphp
                            {{-- <div class="card-group m-auto d-flex justify-content-center"> --}}
                                @foreach($carts as $cart)
                                    @php
                                        $price = $cart->product->price*$cart->amount;
                                        $total_price += $cart->product->price*$cart->amount;
                                    @endphp
                                    <h6>
                                    <div class="card m-3" style="width: 20rem; height:15rem;">
                                        <div class="card-body" style="object-fit: fill">
                                        <img class="card-img-top" style="object-fit: contain" src="{{ url('storage/'.$cart->product->image) }}" alt="Card image cap" width="10rem" height="85rem">
                                            <hr>
                                            <h5 class="card-title"><b>{{ $cart->product->name }}</b></h5>
                                            <h6>Update Amount</h6>
                                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                                <div class="btn-group me-2" role="group" aria-label="First group">
                                                    <form action="{{ route('update_cart', $cart) }}" method="post">
                                                        @method('patch')
                                                        @csrf
                                                        <div class="input-group mb-3" style="width: 10rem">
                                                            <input type="number" class="form-control" aria-describedby="basic-addon2" name="amount" value={{ $cart->amount }}>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary" type="submit"><span class="material-symbols-outlined fa-lg">update</span></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="btn-group" role="group" aria-label="Second group">
                                                    <form action="{{ route('delete_cart', $cart) }}" method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <button class="btn btn-danger align-items-center" type="submit"><span class="material-symbols-outlined fa-lg" >remove_shopping_cart</span></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </h6>
                                @endforeach
                            {{-- </div> --}}
                        </div>
                        <div class="card m-auto d-flex flex-column justify-content-end align-items-end" style="width:15rem; border: 0rem; ">
                            <form action="{{ route('checkout') }}" method="post">
                                @csrf
                                <br>
                                <br>
                                <p style="font-size: 15px">
                                    <b>Total:</b> @currency($total_price)
                                    <button type="submit" class="btn btn-primary" 
                                    @if ($carts->isEmpty()) disabled @endif><span class="material-symbols-outlined fa-lg">shopping_cart_checkout</span></button></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection