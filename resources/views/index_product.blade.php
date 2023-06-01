@extends('layouts.app')

@section('content')
<div class="container" style="max-width:100%">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="/product" method="get" style="background-color:white">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search product.." name="search" style="background-color:white" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><span class="material-symbols-outlined">search</span></button>
                  </div>              
            </form>
        </div>
    </div>
    <div class="row justify-content-center" >
        <div class="col-md-8" style="width:100%">
            <div class="card" >
                <div class="card-group m-auto d-flex justify-content-center">
                    @foreach ($products as $product)
                    <a href="{{ route('show_product', $product) }}" style="text-decoration:none; color:inherit">
                        <div class="card m-3" style="width: 12rem; height:15rem;">
                            <div class="card-body" style="object-fit: fill">
                                <img class="card-img-top" style="object-fit: contain" src="{{ url('storage/'.$product->image) }}" alt="Card image cap" width="10rem" height="85rem">
                                <p class="card-text" style="font-size:15px">
                                    <h6></h6><b>{{ $product->name }}</b>
                                    <h6 style="font-size:12px">@currency($product->price)</h6>
                                </p>
                                <p>
                                    <div class="average_rating">
                                        @php
                                            $average_rating = $product->average_rating;
                                            $total_votes = $product->total_votes;
                                        @endphp
                                        <div class="star">
                                            @php
                                                $count=1;
                                            @endphp
    
                                            @while ($count<=5)
                                                @if ($count <= $average_rating)
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
                                    <p><b>{{ round($average_rating, 1) }}</b>/{{ $total_votes }} Reviews</p>
                                </p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


