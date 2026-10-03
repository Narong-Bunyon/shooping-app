@extends('layouts.app')
@section('content')
<h2>Admin Dashboard</h2>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <p class="card-text fs-3">{{ $categoriesCount }}</p>
                <a href="{{ route('admin.categories.index') }}" class="text-white">Manage Categories</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Products</h5>
                <p class="card-text fs-3">{{ $productsCount }}</p>
                <a href="{{ route('admin.products.index') }}" class="text-white">Manage Products</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Orders</h5>
                <p class="card-text fs-3">{{ $ordersCount }}</p>
                <a href="{{ route('admin.orders.index') }}" class="text-white">Manage Orders</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="card-text fs-3">${{ number_format($revenue, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection