<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UserRepository {
    public function findById(int $user_id) {
        $user = User::where('id', $user_id)
        ->first(['id','balance','gold_balance']);

        return $user;
    }
}