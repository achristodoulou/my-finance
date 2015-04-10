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
        $transaction_record = Transaction::find($transaction->getId());

        $transaction_record->date = $transaction->getDate();
        $transaction_record->value_date = $transaction->getValueDate();
        $transaction_record->description = $transaction->description;
        $transaction_record->amount_credited = $transaction->amount_credited;
        $transaction_record->amount_debited = $transaction->amount_debited;
        $transaction_record->amount_remaining_balance = $transaction->amount_remaining_balance;
        $transaction_record->source = $transaction->source;

        return $transaction_record->save();
    }

    public static function delete($id)
    {
        Transaction::find($id)->delete();
    }

    /**
     * Get all transactions
     *
     * @return TransactionDto[]
     */
    public static function all()
    {
        $transactions = Transaction::all();
        $transactions_dto = [];

        foreach($transactions as $transaction)
        {
            $transactions_dto[] = self::map($transaction);
        }

        return $transactions_dto;
    }

    /**
     * Map Transaction model to TransactionDto
     *
     * @param Transaction $transaction
     * @return TransactionDto
     */
    private static function map(Transaction $transaction)
    {
        $dto = new TransactionDto();
        $dto->setId($transaction->id);
        $dto->setDate($transaction->date);
        $dto->setValueDate($transaction->date);
        $dto->description = $transaction->description;
        $dto->amount_credited = $transaction->amount_credited;
        $dto->amount_debited = $transaction->amount_debited;
        $dto->amount_remaining_balance = $transaction->amount_remaining_balance;
        $dto->source = $transaction->source;
        $dto->filename = $transaction->filename;
        return $dto;
    }
}