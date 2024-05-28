<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {
    public function __construct(
        private UserRepository $userRepository = new UserRepository,
    ){

    }

    public function calculateBalance(object $order) {
        if($order->type == config('order.type.buy')) {
            $this->addToGoldBalance($order->user_id, (float) $order->quantity);
            return;
        }

        $this->addToBalance($order->user_id, (float) $order->price);
        return;
    }

    private function addToBalance(int $user_id, float $balance) {
        $user = $this->userRepository->findById( $user_id);
        $user->balance += $balance;
        $user->save();
    }

    private function addToGoldBalance(int $user_id, float $gold_balance) {
        $user = $this->userRepository->findById($user_id);
        $user->gold_balance += $gold_balance;
        $user->save();
    }
}