<?php namespace App\Dto;

use Patchwork\Utf8;

class TransactionDto{

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var \DateTime
     */
    public $value_date;
    public $description;
    public $amount_credited;
    public $amount_debited;
    public $amount_remaining_balance;
    public $source;
    public $filename = null;

    const DATE_FORMAT = 'Y-m-d';

    public function getDate()
    {
        if(!($this->value_date instanceof \DateTime))
            throw new \Exception('Invalid date format!');

        return $this->date->format(self::DATE_FORMAT);
    }

    public function getValueDate()
    {
        if(!($this->value_date instanceof \DateTime))
            throw new \Exception('Invalid date format!');

        return $this->value_date->format(self::DATE_FORMAT);
    }

    public function getHash()
    {
        return md5(
            $this->getDate() .
            $this->getValueDate() .
            $this->amount_credited .
            $this->amount_debited .
            $this->amount_remaining_balance .
            $this->source .
            $this->description
        );
    }
}

