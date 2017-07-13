<?php
namespace DistanceCalculator;

class DistanceData
{
    public $distance;
    public $distanceText;
    public $time;
    public $timeText;

    public function __construct($distance, $distanceText, $time, $timeText)
    {
        $this->distance = $distance;
        $this->distanceText = $distanceText;
        $this->time = $time;
        $this->timeText = $timeText;
    }

    public function __toString()
    {
        return "[{$this->distance},{$this->distanceText},{$this->time},{$this->timeText}]";
    }
}