<?php namespace App\Helpers;

use App\Dto\Transaction;

class TransactionHelper {
    /**
     * @param \App\Dto\Transaction[] $transactions
     * @return array
     */
    public static function getDescriptions(array $transactions)
    {
        $descriptions = [];
        foreach ($transactions as $transaction) {
            $descriptions[] = $transaction->description;
        }
        return $descriptions;
    }

    /**
     * @param $filename
     * @return Transaction[]
     */
    public static function getTransactionsFromFile($filename)
    {
        $transactions = [];

        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $transactions[] = (new Transaction())->createFromCoop(explode(';', $data[0]));
            }
        }

        return $transactions;
    }
}