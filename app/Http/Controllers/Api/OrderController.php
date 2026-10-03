<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display user's orders.
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('orderItems.product')->latest()->get();

        return response()->json([
            'status' => 'success',
            'count' => $orders->count(),
            'data' => $orders
        ], 200);
    }

    /**
     * Display a specific order details.
     */
    public function show(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access to this order'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    /**
     * Store a new order (Checkout from mobile app).
     * Request Payload format:
     * {
     *   "shipping_address": "123 Main St, City",
     *   "items": [
     *     {"product_id": 1, "quantity": 2},
     *     {"product_id": 3, "quantity": 1}
     *   ]
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => "Product '{$product->name}' does not have enough stock available."
                    ], 400);
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ];

                // Deduct stock
                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'shipping_address' => $request->shipping_address,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            foreach ($orderItemsData as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order->load('orderItems.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
        }
    }
}
