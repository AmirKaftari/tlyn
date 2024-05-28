<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Transaction;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use stdClass;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService = new OrderService()) {

    }
    public function store(OrderRequest $request) {
        try {

            $order = $this->orderService->createOrder($request);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order Created!'
            ]);
        }
        catch(\Exception $exception) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $exception->getMessage()
            ]);
        }
    }
}
