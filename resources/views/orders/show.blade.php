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