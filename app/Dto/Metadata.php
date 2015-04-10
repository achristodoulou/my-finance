<?php namespace App\Dto;

class Metadata {

    private $metadata = [];

    function __construct($start_line = 1,
                         $filename = '',
                         $separator = ';',
                         $date_format = 'Y-m-d',
                         $source,
                         array $columns,
                         $created_at
    ){
        $this->metadata['start_line'] = $start_line;
        $this->metadata['source'] = $source;
        $this->metadata['separator'] = $separator;
        $this->metadata['date_format'] = $date_format;
        $this->metadata['columns'] = $columns;
        $this->metadata['filename'] = $filename;
        $this->metadata['created_at'] = $created_at;
    }

    /**
     * Get date created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->metadata['created_at'];
    }

    /**
     * Get list of metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return array
     */
    public function getOrderOfColumns()
    {
        return $this->metadata['columns'];
    }


    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->metadata['separator'];
    }

    /**
     * @return int
     */
    public function getStartLine()
    {
        return (int) $this->metadata['start_line'];
    }

    /**
     * Source can be bank, cash etc
     * @return string
     */
    public function getSource()
    {
        return $this->metadata['source'];
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->metadata['date_format'];
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->metadata['filename'];
    }
}