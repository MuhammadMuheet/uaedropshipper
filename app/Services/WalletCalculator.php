<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletCalculator
{
    /**
     * Calculate the total wallet balance for a user with optional date filters.
     *
     * @param int $userId
     * @param Request $request
     * @return float
     */
    public static function calculateTotalWallet($userId, Request $request): float
    {
        $query = Transaction::where('user_id', $userId);

        // Apply date filters from the request
        if (!empty($request->current_date)) {
            $query->whereDate('created_at', $request->current_date);
        }
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->get();
        $totalAmountIn = $transactions->where('amount_type', 'in')->sum('amount');
        $totalAmountOut = $transactions->where('amount_type', 'out')->sum('amount');

        return $totalAmountIn - $totalAmountOut;
    }
}