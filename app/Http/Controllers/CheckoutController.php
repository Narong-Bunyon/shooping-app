<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if(!session('cart')) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }
        return view('checkout.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
        ]);

        $cart = session('cart');
        if(!$cart) {
            return redirect()->route('products.index');
        }

        $totalAmount = 0;
        foreach($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_address' => $request->shipping_address,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);

        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }
}
