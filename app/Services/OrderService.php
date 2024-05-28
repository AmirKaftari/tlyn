<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;

class OrderService {
    public function __construct(
        private OrderRepository $orderRepository = new OrderRepository,
        private TransactionRepository $transactionRepository = new TransactionRepository,
        private UserService $userService = new UserService
        ){

    }
    public function createOrder($order) {
        $order = $this->orderRepository->create([
            'user_id' => $order->user_id,
            'type' => $order->type,
            'price' => $order->price,
            'quantity' => $order->quantity,
            'remaining_quantity' => $order->quantity
        ]);
        $this->matchOrders($order);
        return $order;
    }

    public function matchOrders($order) {
        $orderMatches  = $this->findMatch($order);

        foreach($orderMatches as $matchedOrder) {

            // Calculate Balance(money and gold) for order user
            if ($order->remaining_quantity == 0){
                $this->calculateBalance($order);
                break;
            }

            $transactionQuantity = min($matchedOrder->remaining_quantity, $order->remaining_quantity);
            $fee = $this->calculateFee($transactionQuantity);

            $this->transactionRepository->create([
                'buyer_id' => $order->type == 'buy' ? $order->user_id : $matchedOrder->user_id,
                'seller_id' => $order->type == 'sell' ? $order->user_id : $matchedOrder->user_id,
                'order_id' => $order->id,
                'price' => $order->price,
                'quantity' => $transactionQuantity,
                'fee' => $fee
            ]);

            $order->remaining_quantity -= $transactionQuantity;
            $order->save();

            // Calculate Balance(money and gold) for order user if remaining quantity equal to zero
            $this->calculateBalance($order);

            $matchedOrder->remaining_quantity -= $transactionQuantity;
            $matchedOrder->save();

            // Calculate Balance(money and gold) for match order user
            $this->calculateBalance($matchedOrder);
        }
    }

    public function findMatch($order) {

        $oppositeType = $order->type == 'buy' ? 'sell' : 'buy';
        $orderMatches = Order::where('type', $oppositeType)
        ->where('remaining_quantity', '>', 0)
        ->orderBy('created_at')
        ->get();

        return $orderMatches;
    }

    public function calculateFee($quantity)
    {
        if ($quantity <= 1) {
            return max(50000, $quantity * 10000000 * 0.02);
        } elseif ($quantity <= 10) {
            return min(5000000, max(50000, $quantity * 10000000 * 0.015));
        } else {
            return min(5000000, max(50000, $quantity * 10000000 * 0.01));
        }
    }

    public function calculateBalance(Order $order) {
        
        if($order->status != config('order.status.finished')
            && $order->status != config('order.status.cancelled')) {
                $this->userService->calculateBalance($order);

                if($order->remaining_quantity == 0) {
                    $order->status = config('order.status.finished');
                    $order->save();
                }
            }
    }
}