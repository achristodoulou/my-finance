<?php namespace App\Repositories;

use App\Dto\TransactionDto;
use App\Models\Transaction;
use DB;

class TransactionsRepository {


    public static function store(TransactionDto $transaction)
    {
        $transaction_record = new Transaction();

        $transaction_record->date = $transaction->getDate();
        $transaction_record->value_date = $transaction->getValueDate();
        $transaction_record->description = $transaction->description;
        $transaction_record->amount_credited = $transaction->amount_credited;
        $transaction_record->amount_debited = $transaction->amount_debited;
        $transaction_record->amount_remaining_balance = $transaction->amount_remaining_balance;
        $transaction_record->source = $transaction->source;
        $transaction_record->filename = $transaction->filename;
        $transaction_record->hash = $transaction->getHash();

        return $transaction_record->save();
    }

    /**
     * Store multiple transactions in database
     *
     * @param TransactionDto[] $transactions
     * @return array
     */
    public static function storeMoreThanOne(array $transactions)
    {
        $result = [
            'status' => true,
            'skip' => 0,
            'total' => sizeof($transactions)
        ];

        if(sizeof($transactions) > 0)
        {
            try {
                DB::connection()->getPdo()->beginTransaction();

                foreach ($transactions as $transaction) {

                    $transaction_record = Transaction::where('hash', '=', $transaction->getHash())->first();

                    if(is_null($transaction_record))
                    {
                        TransactionsRepository::store($transaction);
                    }
                    else
                    {
                        $result['skip'] += 1;
                    }
                }

                DB::connection()->getPdo()->commit();
            }
            catch (\PDOException $e)
            {
                DB::connection()->getPdo()->rollBack();
                $result['status'] = false;
                $result['skip'] = 0;
            }
        }

        return $result;
    }

    public static function update(TransactionDto $transaction)
    {
        $transaction_record = Transaction::where('hash', '=', $transaction->getHash());

        $transaction_record->date = $transaction->getDate();
        $transaction_record->value_date = $transaction->getValueDate();
        $transaction_record->description = $transaction->description;
        $transaction_record->amount_credited = $transaction->amount_credited;
        $transaction_record->amount_debited = $transaction->amount_debited;
        $transaction_record->amount_remaining_balance = $transaction->amount_remaining_balance;
        $transaction_record->source = $transaction->source;

        return $transaction_record->save();
    }
}