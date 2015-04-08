<?php namespace App\Dto;

class Metadata {

    private $start_line;
    private $source;
    private $separator;
    private $date_format;
    private $columns;

    function __construct($start_line = 1,
                         $source = 'Unknown',
                         $separator = ';',
                         $date_format = 'Y-m-d',
                         array $columns
    ){
        $this->start_line = $start_line;
        $this->source = $source;
        $this->separator = $separator;
        $this->date_format = $date_format;
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getOrderOfColumns()
    {
        return $this->columns;
    }


    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * @return int
     */
    public function getStartLine()
    {
        return (int) $this->start_line;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }


}