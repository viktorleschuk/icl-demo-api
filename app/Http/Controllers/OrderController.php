<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\DestroyRequest;
use App\Http\Requests\Orders\IndexRequest;
use App\Http\Requests\Orders\ShowRequest;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Requests\Orders\UpdateRequest;
use App\Order;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        $orders = auth()->user()->orders();

        //TODO: filters

        return response()->json($orders->get());
    }

    /**
     * @param ShowRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request, Order $order)
    {
        return response()->json($order);
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
    public function update(UpdateRequest $request, Order $order)
    {
        $order->update($request->only($order->getFillable()));

        return $order;
    }

    /**
     * @param DestroyRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroyRequest $request, Order $order)
    {
        $order->delete();

        return response()->json($order);
    }
}
