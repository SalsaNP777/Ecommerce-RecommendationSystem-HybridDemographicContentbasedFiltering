@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Product') }}</div>

                    <div class="card-body">
                        <form action="{{ route('store_product') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <Label>Name</Label>
                                <input type="text" name="name" placeholder="Name" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <Label>Description</Label>
                                <input type="text" name="description" placeholder="Description" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <Label>Price</Label>
                                <input type="text" name="price" placeholder="Price" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <Label>Stock</Label>
                                <input type="text" name="stock" placeholder="Stock" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Submit Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection