<?php

namespace App\Services;

use App\Models\Transaction;
use App\Enums\TransactionType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Create a new transaction.
     *
     * @param int $userId
     * @param float $amount
     * @param TransactionType $transactionType
     * @param string|null $invoiceLink
     * @return Transaction
     */
    public function createTransaction(int $userId, float $amount, TransactionType $transactionType, ?string $invoiceLink = null): Transaction
    {
        try {
            DB::beginTransaction();

            // Generate a unique transaction number
            $transactionNumber = $this->generateTransactionNumber();

            // Create the transaction
            $transaction = Transaction::create([
                'user_id' => $userId,
                'transaction_number' => $transactionNumber,
                'amount' => $amount,
                'transaction_type' => $transactionType,
                'transaction_date' => now(),
                'invoice_link' => $invoiceLink,
            ]);

            // $invoiceLink = $this->pdfService->addTransactionPDF($transaction);
            // $transaction->invoice_link = $invoiceLink;
            // $transaction->save();

            DB::commit();

            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction creation failed: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be handled elsewhere
        }
    }

    /**
     * Generate a unique transaction number.
     *
     * @return string
     */
    protected function generateTransactionNumber(): string
    {
        return 'TXN-' . Str::upper(Str::random(10));
    }

    /**
     * Update the transaction.
     *
     * @param Transaction $transaction
     * @param float $amount
     * @param TransactionType $transactionType
     * @param string|null $invoiceLink
     * @return Transaction
     */
    public function updateTransaction(Transaction $transaction, float $amount, TransactionType $transactionType, ?string $invoiceLink = null): Transaction
    {
        try {
            DB::beginTransaction();

            $transaction->update([
                'amount' => $amount,
                'transaction_type' => $transactionType,
                'invoice_link' => $invoiceLink,
                'transaction_date' => now(),
            ]);

            DB::commit();

            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch the user's transaction history.
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserTransactionHistory(int $userId, int $limit = 10)
    {
        return Transaction::where('user_id', $userId)
            ->orderBy('transaction_date', 'desc')
            ->limit($limit)
            ->get();
    }
}
