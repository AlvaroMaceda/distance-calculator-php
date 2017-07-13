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

        while (($line = fgetcsv($fileOrigin, 1000, ",")) !== false) {
            $pathData = $this->calculatePathDataFromLine($line);
            $distance = $this->distanceMatrix->calculateDistance($pathData['origin'], $pathData['destination']);
            $this->writeResultToOutput($fileDestination, $line, $distance);
//            echo($pathData['origin']." to ".$pathData['destination']."->".$distance."\n");
        }
    }

    private function calculatePathDataFromLine($data)
    {
        $pathData = [
            'destination' => $data[0]
        ];

        if (count($data) >= 2) {
            $pathData['origin'] = $data[1];
        } elseif (count($data) == 1) {
            $pathData['origin'] = $this->placeOrigin;
        } else {
            throw new \Exception('Invalid CSV data');
        }

        return $pathData;
    }

    private function writeResultToOutput($fileOutput, $line, DistanceData $distance)
    {
        $outputData = $this->generateOutputData($line, $distance);
        fputcsv($fileOutput, $outputData);
    }

    private function generateOutputData($line, DistanceData $distance)
    {
        $res = array_merge($line, $distance->toArray());
        return $res;
    }


}