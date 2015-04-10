<?php namespace App\Helpers;

use App\Dto\Metadata;
use App\Dto\TransactionDto;
use App\Models\Category;
use Patchwork\Utf8;

class TransactionHelper {
    /**
     * Get list of descriptions from a transaction list
     *
     * @param \App\Dto\TransactionDto[] $transactions
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
     * @return TransactionDto[]
     */
    public static function getTransactionsFromFile($filename)
    {
        $transactions = [];
        $current_line = 1;
        $metadata = FileHelper::getMetadata($filename);
        $filename_path = storage_path('transactions') . '/' . $filename;

        if (($handle = fopen($filename_path, "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle)) !== FALSE)
            {
                if($current_line >= $metadata->getStartLine())
                {
                    $transactions[] = self::transactionTransform($metadata, $data[0], $metadata->getSource(), $filename);
                }
                $current_line++;
            }
        }

        return $transactions;
    }

    /**
     * Create a transaction from array
     *
     * @param Metadata $metadata
     * @param string $transaction
     * @param $filename
     * @param string $source , file source
     * @return TransactionDto
     */
    public static function transactionTransform(Metadata $metadata, $transaction, $source, $filename = '')
    {
        $transaction = explode($metadata->getSeparator(), $transaction);

        $columns = $metadata->getOrderOfColumns();

        $idx_date = array_search('date', $columns);
        $idx_value_date = array_search('value_date', $columns);
        $idx_description = array_search('description', $columns);
        $idx_debit_amount = array_search('debit_amount', $columns);
        $idx_credit_amount = array_search('credit_amount', $columns);
        $idx_remaining_balance = array_search('remaining_balance', $columns);

        $obj = new TransactionDto();

        $obj->date = \DateTime::createFromFormat($metadata->getDateFormat(), $transaction[$idx_date]);
        $obj->value_date = \DateTime::createFromFormat($metadata->getDateFormat(), $transaction[$idx_value_date]);
        $obj->description = Utf8::toAscii($transaction[$idx_description]);
        $obj->amount_debited = $transaction[$idx_debit_amount];
        $obj->amount_credited = $transaction[$idx_credit_amount];
        $obj->amount_remaining_balance = $transaction[$idx_remaining_balance];
        $obj->source = $source;
        $obj->filename = $filename;

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
                if (preg_match("/($label?)/", $input_label)) {
                    return $category['category_name'];
                }
            }
        }

        return null;
    }
}