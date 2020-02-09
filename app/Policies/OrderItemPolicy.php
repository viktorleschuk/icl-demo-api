<?php

namespace App\Policies;

use App\Order;
use App\OrderItem;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order items.
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function index(User $user, Order $order)
    {
        return $this->isOwner($user, $order);
    }

    /**
     * Determine whether the user can create order items.
     *
     * @param  \App\User  $user
     * @param  Order $order
     * @return mixed
     */
    public function create(User $user, Order $order)
    {
        return $this->isOwner($user, $order);
    }

    /**
     * Determine whether the user can update the order item.
     *
     * @param  \App\User  $user
     * @param Order $order
     * @param  \App\OrderItem  $orderItem
     * @return mixed
     */
    public function update(User $user, Order $order, OrderItem $orderItem)
    {
        return $this->isOwner($user, $order) && $this->itemRelatedToOrder($orderItem, $order);
    }

    /**
     * Determine whether the user can delete the order item.
     *
     * @param  \App\User  $user
     * @param Order $order
     * @param  \App\OrderItem  $orderItem
     * @return mixed
     */
    public function delete(User $user, Order $order, OrderItem $orderItem)
    {
        return $this->isOwner($user, $order) && $this->itemRelatedToOrder($orderItem, $order);
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    private function isOwner(User $user, Order $order)
    {
        return $user->getKey() == $order->user_id;
    }

    /**
     * @param OrderItem $orderItem
     * @param Order $order
     * @return bool
     */
    private function itemRelatedToOrder(OrderItem $orderItem, Order $order)
    {
        return $order->getKey() == $orderItem->order_id;
    }
}
