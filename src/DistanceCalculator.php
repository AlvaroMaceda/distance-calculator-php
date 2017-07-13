<?php
namespace DistanceCalculator;

require_once 'autoloader.php';

class DistanceCalculator
{
    private $origin;

    public function __construct($key, $origin = null, $units = null)
    {
        $this->origin = $origin ?: $this->origin;
        $this->distanceMatrix = new GoogleDistanceMatrix($key, $units);
    }

    public function calculate($fileName)
    {
        $row = 1;
        if (($handle = fopen($fileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $origin = $data[1];
                $destination = $data[0];
                $data = $this->distanceMatrix->calculateDistance($origin, $destination);
                echo($origin." to ".$destination."->".$data."\n");
            }
            fclose($handle);
        }
    }




}