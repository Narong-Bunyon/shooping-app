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