<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;

class TransactionController extends Controller
{
    public function __construct(private TransactionRepository $transactionRepository = new TransactionRepository) {

    }
    public function userTransactions(int $user_id) {

        $transactions = $this->transactionRepository->findByUserId($user_id);

        return response()->json([
            'success' => true,
            'data' => $transactions,
            'message' => 'User transaction Listed!'
        ]);
    }
}
