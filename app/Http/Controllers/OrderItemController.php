<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\DestroyRequest;
use App\Http\Requests\Orders\IndexRequest;
use App\Http\Requests\Orders\ShowRequest;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Requests\Orders\UpdateRequest;
use App\Order;
use App\OrderItem;

class OrderItemController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request, Order $order)
    {
        $items = $order->items();

        //TODO: filters

        return response()->json($items->get());
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        /** @var \App\User $user */
        $user = auth()->user();

        $order = $user->orders()->create($request->all());

        return response()->json($order);
    }

    /**
     * @param UpdateRequest $request
     * @param Order $order
     * @return Order
     */
    public function update(UpdateRequest $request, Order $order, OrderItem $item)
    {
        $item->update($request->only($item->getFillable()));

        return $order;
    }

    /**
     * @param DestroyRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroyRequest $request, Order $order, OrderItem $item)
    {
        $item->delete();

        return response()->json($item);
    }
}
