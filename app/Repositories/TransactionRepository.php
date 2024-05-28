<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository {
    public function create(array $data) {
        $transaction = Transaction::create($data);
        return $transaction;
    }

    public function findByUserId(int $user_id) {
        
        $transactions = Transaction::with(['buyer:id,name','seller:id,name','order:id,price,quantity'])
        ->where('buyer_id', $user_id)
        ->orWhere('seller_id', $user_id)
        ->get();

        return $transactions;
    }
}