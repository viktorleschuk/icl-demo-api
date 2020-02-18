<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderItems\ImportRequest;
use App\Http\Requests\Orders\DestroyRequest;
use App\Http\Requests\Orders\IndexRequest;
use App\Http\Requests\Orders\ShowRequest;
use App\Http\Requests\Orders\StoreRequest;
use App\Http\Requests\Orders\UpdateRequest;
use App\Jobs\HandleCsvFile;
use App\Order;
use App\OrderItem;
use App\Http\Resources\OrderItem as OrderItemResource;

class OrderItemController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request, Order $order)
    {
        $items = $order->items();

        return OrderItemResource::collection($items->get());
    }

    /**
     * @param ImportRequest $request
     * @param Order $order
     * @return mixed
     */
    public function import(ImportRequest $request, Order $order)
    {
        $items = $this->dispatch(new HandleCsvFile($request->file('file')));
        array_walk($items, function (&$item) use ($order) {
            $item['order_id'] = $order->getKey();
        });

        $order->items()->insert($items);

        return OrderItemResource::collection($order->items);
    }
}
