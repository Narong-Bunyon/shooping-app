<?php

$views = [
    'resources/views/layouts/app.blade.php' => <<<'EOD'
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel Shop') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Laravel Shop</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart ({{ session('cart') ? count(session('cart')) : 0 }})</a></li>
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if(Auth::user()->is_admin)
                                <li class="nav-item"><a class="nav-link text-danger" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
EOD,
    'resources/views/home.blade.php' => <<<'EOD'
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
EOD,
    'resources/views/products/index.blade.php' => <<<'EOD'
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
EOD,
    'resources/views/products/show.blade.php' => <<<'EOD'
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
EOD,
    'resources/views/cart/index.blade.php' => <<<'EOD'
@extends('layouts.app')
@section('content')
<h2>Shopping Cart</h2>
@if(session('cart') && count(session('cart')) > 0)
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach(session('cart') as $id => $details)
                @php $total += $details['price'] * $details['quantity']; @endphp
                <tr>
                    <td>{{ $details['name'] }}</td>
                    <td>${{ number_format($details['price'], 2) }}</td>
                    <td>
                        <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex">
                            @csrf
                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" class="form-control form-control-sm me-2" style="width: 70px;" min="1">
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                        </form>
                    </td>
                    <td>${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total</strong></td>
                <td colspan="2"><strong>${{ number_format($total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="text-end mt-3">
        <a href="{{ route('checkout.index') }}" class="btn btn-success">Proceed to Checkout</a>
    </div>
@else
    <p class="mt-4">Your cart is empty. <a href="{{ route('products.index') }}">Go shopping!</a></p>
@endif
@endsection
EOD,
    'resources/views/checkout/index.blade.php' => <<<'EOD'
@extends('layouts.app')
@section('content')
<h2>Checkout</h2>
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Shipping Details</div>
            <div class="card-body">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Shipping Address</label>
                        <textarea name="shipping_address" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Place Order</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Order Summary</div>
            <div class="card-body">
                @php $total = 0; @endphp
                @foreach(session('cart') as $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <p>{{ $details['name'] }} x {{ $details['quantity'] }} <span class="float-end">${{ number_format($details['price'] * $details['quantity'], 2) }}</span></p>
                @endforeach
                <hr>
                <h5>Total <span class="float-end">${{ number_format($total, 2) }}</span></h5>
            </div>
        </div>
    </div>
</div>
@endsection
EOD,
    'resources/views/orders/index.blade.php' => <<<'EOD'
@extends('layouts.app')
@section('content')
<h2>My Orders</h2>
<div class="mt-4">
    @if($orders->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td><span class="badge bg-{{ $order->status == 'pending' ? 'warning' : 'success' }}">{{ ucfirst($order->status) }}</span></td>
                        <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">View Details</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>You have no orders yet.</p>
    @endif
</div>
@endsection
EOD,
    'resources/views/orders/show.blade.php' => <<<'EOD'
@extends('layouts.app')
@section('content')
<h2>Order #{{ $order->id }} Details</h2>
<div class="card mt-4">
    <div class="card-body">
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
        
        <h4 class="mt-4">Items</h4>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
EOD,
    'resources/views/admin/dashboard.blade.php' => <<<'EOD'
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
EOD,
];

foreach ($views as $path => $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $content);
}
echo "Views generated successfully!";
