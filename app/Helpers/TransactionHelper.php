<?php namespace App\Helpers;

use App\Dto\Transaction;
use App\Models\Category;
use Patchwork\Utf8;

class TransactionHelper {
    /**
     * Get list of descriptions from a transaction list
     *
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
                $transactions[] = self::createFromCoop(explode(';', $data[0]));
            }
        }

        return $transactions;
    }

    /**
     * Create a transaction from COOP
     *
     * @param array $transaction
     * @return Transaction
     */
    public static function createFromCoop(array $transaction)
    {
        $obj = new Transaction();
        $date = explode('/', $transaction[0]);
        $new_date = $date[2] . '-' . $date[1] . '-' . $date[0];

        $value_date = explode('/', $transaction[1]);
        $new_value_date = $value_date[2] . '-' . $value_date[1] . '-' . $value_date[0];

        $obj->date = new \DateTime($new_date);
        $obj->value_date = new \DateTime($new_value_date);
        $obj->description = Utf8::toAscii($transaction[2]);
        $obj->amount_credited = $transaction[4];
        $obj->amount_debited = $transaction[3];
        $obj->amount_remaining_balance = $transaction[5];

        return $obj;
    }

    /**
     * Debit/credit amount on total
     *
     * @param $total
     * @param int $debitAmount
     * @param int $creditAmount
     * @return int
     */
    public static function calculateTotal($total, $debitAmount = 0, $creditAmount = 0)
    {
        if($debitAmount)
            $total -= $debitAmount;
        else
            $total += $creditAmount;
        return $total;
    }

    /**
     * Get category name
     *
     * @param $input_label
     * @return null
     */
    public static function getCategoryName($input_label)
    {
        $input_label = strtolower($input_label);

        $categories = Category::all()->toArray();

        foreach ($categories as $category) {
            $labels = explode('|', $category['labels']);

            foreach ($labels as $label) {
                if (strpos($input_label, $label) !== false) {
                    return $category['category_name'];
                }
            }
        }

        return null;
    }
}