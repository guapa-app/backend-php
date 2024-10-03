<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Resources\TransactionResource;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\TransactionCollection;

class TransactionController extends BaseApiController
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionService->getUserTransactionHistory(auth()->user()->id);

        return TransactionCollection::make($transactions)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function show(Transaction $transaction)
    {
        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== auth()->id()) {
            return response()->json(['message' => __('Not Found')], 401);
        }

        return new TransactionResource($transaction);
    }
}
