<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;

class OrderRepository {
    public function create(array $order): object {
        $order = Order::create($order);
        return $order;
    }
}