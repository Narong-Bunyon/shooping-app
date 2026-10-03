@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="bg-light p-5 text-center">No Image</div>
    </div>
    <div class="col-md-6">
        <h2>{{ $product->name }}</h2>
        <p class="text-muted">Category: {{ $product->category->name ?? 'N/A' }}</p>
        <h4>${{ number_format($product->price, 2) }}</h4>
        <p>{{ $product->description }}</p>
        <p>Stock: {{ $product->stock }}</p>
        
        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
            @csrf
            <div class="input-group mb-3" style="max-width: 200px;">
                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                <button class="btn btn-primary" type="submit">Add to Cart</button>
            </div>
        </form>
    </div>
</div>
@endsection