<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\DestroyRequest;
use App\Http\Requests\Orders\IndexRequest;
use App\Http\Requests\Orders\ShowRequest;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Requests\Orders\UpdateRequest;
use App\Http\Resources\Order as OrderResource;
use App\Order;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexRequest $request)
    {
        $orders = auth()->user()->orders();

        if ($request->filled('filter')) {
            $orders->filter($request->get('filter'));
        }

        return OrderResource::collection($orders->paginate($request->get('page_size', 10)));
    }

    /**
     * @param ShowRequest $request
     * @param Order $order
     * @return OrderResource
     */
    public function show(ShowRequest $request, Order $order)
    {
        $order->load('items');

        return new OrderResource($order);
    }

    /**
     * @param StoreRequest $request
     * @return OrderResource
     */
    public function store(StoreRequest $request)
    {
        /** @var \App\User $user */
        $user = auth()->user();

        $order = $user->orders()->create($request->only('client_name', 'client_phone', 'client_address'));

        return new OrderResource($order);
    }

    /**
     * @param UpdateRequest $request
     * @param Order $order
     * @return OrderResource
     */
    public function update(UpdateRequest $request, Order $order)
    {
        $order->update($request->only($order->getFillable()));

        return new OrderResource($order);
    }

    /**
     * @param DestroyRequest $request
     * @param Order $order
     * @return OrderResource
     * @throws \Exception
     */
    public function destroy(DestroyRequest $request, Order $order)
    {
        $order->delete();

        return new OrderResource($order);
    }
}
