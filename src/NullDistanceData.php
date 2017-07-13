<?php
namespace DistanceCalculator;

class NullDistanceData extends DistanceData
{
    private $message;

    public function __construct($message = '')
    {
        parent::__construct(null, null, null, null);
        $this->message = $message;
    }

    public function __toString()
    {
        return "[{$this->message}]";
    }

    public function toArray()
    {
        return [$this->message];
    }
}