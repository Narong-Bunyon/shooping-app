@extends('layouts.app')
@section('content')
<h2>All Products</h2>
<div class="row mt-4">
    @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <p class="card-text">${{ number_format($product->price, 2) }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{ $products->links() }}
@endsection