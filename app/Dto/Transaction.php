<?php namespace App\Dto;

use Patchwork\Utf8;

class Transaction{

    /**
     * @var \DateTime
     */
    public $date;
    public $description;
    public $amount_credited;
    public $amount_debited;
    public $amount_remaining_balance;

    public function createFromCoop(array $transaction)
    {
        $date = explode('/', $transaction[0]);
        $new_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        $this->date = new \DateTime($new_date);
        $this->description = $this->convertToUtf8($transaction[2]);
        $this->amount_credited = $transaction[4];
        $this->amount_debited = $transaction[3];
        $this->amount_remaining_balance = $transaction[5];

        return $this;
    }

    private function convertToUtf8($string)
    {
        return Utf8::toAscii($string);
    }
}

