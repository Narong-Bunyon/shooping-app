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