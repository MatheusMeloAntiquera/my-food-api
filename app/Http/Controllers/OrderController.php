<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\StatusOrder;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        //Implement Transaction

        $order = new Order();
        $order->customer_id = $request['customer_id'];
        $order->restaurant_id = $request['restaurant_id'];
        $order->status = StatusOrder::PENDING;
        $order->save();

        $total = 0.0;
        foreach ($request['items'] as $item) {
            $orderItem = new OrderItem();
            $orderItem->name = $item['name'];
            $orderItem->price = $item['price'];
            $orderItem->amount = $item['amount'];
            $orderItem->order_id = $order->id;
            $orderItem->save();

            $total += ($orderItem->price * $orderItem->amount);
        }

        return response()->json([
            'id' => $order->id,
            'customer_id' => $order->customer_id,
            'restaurant_id' => $order->restaurant_id,
            'total' => $total,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ], 201);
    }

    public function show(Request $request, string $orderId): JsonResponse
    {
        $order = Order::find($orderId)->with("items")->first();
        $order->total = array_reduce($order->items->toArray(), function ($total, $orderItem) {
            $total += ($orderItem['price'] * $orderItem['amount']);
            return $total;
        });
        return response()->json($order->toArray(), 200);
    }
}
