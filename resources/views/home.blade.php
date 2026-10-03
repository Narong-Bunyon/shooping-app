@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h1>Welcome to Laravel Shop</h1>
        <p>Find the best products here!</p>
    </div>
</div>
<div class="row">
    @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">${{ number_format($product->price, 2) }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection