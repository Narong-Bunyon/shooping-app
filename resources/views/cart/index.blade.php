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