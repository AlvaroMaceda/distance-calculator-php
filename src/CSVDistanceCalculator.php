<?php
namespace DistanceCalculator;

require_once 'autoloader.php';

class CSVDistanceCalculator
{
    private $placeOrigin;

    public function __construct($key, $placeOrigin = null, $units = null)
    {
        $this->placeOrigin = $placeOrigin ?: $this->placeOrigin;
        $this->distanceMatrix = new GoogleDistanceMatrix($key, $units);
    }

    public function calculate($fileOrigin, $fileDestination = null)
    {
        $fileDestination = $fileDestination ?: STDOUT;

        while (($data = fgetcsv($fileOrigin, 1000, ",")) !== false) {
            $origin = $data[1];
            $destination = $data[0];
            $data = $this->distanceMatrix->calculateDistance($origin, $destination);
            echo($origin." to ".$destination."->".$data."\n");
        }
    }




}