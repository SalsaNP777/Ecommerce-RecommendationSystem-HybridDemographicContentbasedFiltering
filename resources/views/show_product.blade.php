@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card" style="max-width:100%">
            <div class="card-header" >{{ __('Product Detail') }}</div>

                    <div class="card-body">
                        <div class="card">
                            <div class="d-flex justify-content-center" style="padding: 1rem">
                                <div class="image" style="margin-right:100px">
                                    <img class="card-img-top" style="object-fit: contain" src="{{ url('storage/'.$product->image) }}" alt="Card image cap" width="400rem;" height="290rem;">
                                </div>
                                <div class="details">
                                    <div class="card d-flex justify-content-center" style="border:0rem;">
                                        <h1 style="font-size:35px">{{ $product->name }}</h1>
                                        <div class="average_rating">
                                            @php
                                                $product = $product;
                                                $ratings = $product->ratings;
                                                $total = 0;
                                                $count = 0;
                                                foreach ($ratings as $rating) {
                                                    $total += $rating->rate;
                                                    $count++;
                                                }
                                                if ($count == 0) {
                                                    $average = 0;
                                                } else {
                                                    $average = $total/$count;
                                                }
                                                $total_reviews = $count;
                                            @endphp
                                            <div class="star">
                                                @php
                                                    $count=1;
                                                @endphp
        
                                                @while ($count<=5)
                                                    @if ($count <= $average)
                                                        <i class="material-symbols-outlined yellow fa-lg">star_rate</i>
                                                    @else
                                                        <i class="material-symbols-outlined fa-lg">star_rate</i>
                                                    @endif
                                                    @php
                                                        $count++;
                                                    @endphp
                                                @endwhile
                                            </div>
                                        </div>
                                        <p><b>{{ round($average, 1) }}</b>/{{ $total_reviews }} Reviews</p>
                                        <h3>@currency($product->price)</h3>
                                        <hr>
                                        <p>{{ $product->stock }} left</p>
                                        
                                        @if (!Auth::user())
                                        @elseif (!Auth::user()->is_admin)
                                        <form action="{{ route('add_to_cart', $product) }}" method="post">
                                            @csrf
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control" aria-describedby="basic-addon2" name="amount" value="1">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="submit"><i class="material-symbols-outlined">add_shopping_cart</i></button>
                                                </div>
                                            </div>
                                        </form>
                                        @else
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group me-2" role="group" aria-label="First group">
                                                <form action="{{ route('edit_product', $product) }}" method="get">
                                                    <button type="submit" class="btn btn-primary"><i class="material-symbols-outlined">edit_note</i></button>
                                                </form>
                                            </div>
                                            <div class="btn-group" role="group" aria-label="Second group">
                                                <form action="{{ route('delete_product', $product) }}" method="post">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger"><i class="material-symbols-outlined">delete</i></button>
                                                </form>   
                                            </div>                                
                                        </div>
                                        <hr>
                                        @endif
                                        <div class="product-detail">
                                            <h6><b>Product description:</b></h6>
                                            <h6>{{ $product->description }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-top: 1rem">
                            <div class="rating-review" style="padding:0.5rem">
                                <div class="average_rating" style="padding:0.5rem">
                                    <h2>Product Rating</h2>
                                    @php
                                        $product = $product;
                                        $ratings = $product->ratings;
                                        $total = 0;
                                        $count = 0;
                                        foreach ($ratings as $rating) {
                                            $total += $rating->rate;
                                            $count++;
                                        }
                                        if ($count == 0) {
                                            $average = 0;
                                        } else {
                                            $average = $total/$count;
                                        }
                                        $total_reviews = $count;
                                    @endphp
                                    <div class="form-group-star">
                                        @php
                                            $count=1;
                                        @endphp

                                        @while ($count<=5)
                                            @if ($count <= $average)
                                                <i class="material-symbols-outlined yellow fa-lg">star_rate</i>
                                            @else
                                                <i class="material-symbols-outlined fa-lg">star_rate</i>
                                            @endif
                                            @php
                                                $count++;
                                            @endphp
                                        @endwhile
                                    </div>
                                    <p style="font-size:12px padding:0.5rem"><b>{{ round($average, 1) }}</b>/{{ $total_reviews }} Reviews</p>
                                </div>
                                <hr>
                                
                                @if ($total_reviews == 0)
                                    <h6>No reviews yet</h6>
                                @endif
                                
                                @foreach ($ratings as $rating)
                                    @php
                                        $product = $product;                                    
                                        $ratings = $product->ratings;
                                        $id = $rating->id;
                                    @endphp
                                    <div class="ratings" style="padding:0.5rem">
                                        <h6><b>Rated by:</b> {{ $rating->user->name }} at {{ $rating->created_at }}</h6>
                                        @php
                                            $count=1;
                                        @endphp
                                        @while($count <= 5)
                                            @if ($count <= $rating->rate)
                                                <i class="material-symbols-outlined yellow fa-lg">star_rate</i>
                                            @else
                                                <i class="material-symbols-outlined fa-lg">star_rate</i>
                                            @endif
                                            @php
                                                $count++;
                                            @endphp
                                        @endwhile
                                        @if ($rating->review != null)
                                            <p>{{ $rating->review }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div>
@endsection