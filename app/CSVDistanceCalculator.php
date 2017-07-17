<?php
namespace DistanceCalculator;

require_once 'autoloader.php';

class CSVDistanceCalculator
{
    private $delimiter = ",";
    private $maxLineLenght = null; //Null for no limits

    private $commonOrigin;

    public function __construct($key, $commonOrigin = null, $units = null)
    {
        $this->commonOrigin = $commonOrigin ?: $this->commonOrigin;
        $this->distanceMatrix = new GoogleDistanceMatrix($key, $units);
    }

    public function setLanguage($language)
    {
        $this->distanceMatrix->setLanguage($language);
    }

    public function setMode($mode)
    {
        $this->distanceMatrix->setMode($mode);
    }

    public function calculate($fileOrigin, $fileDestination = null)
    {
        $fileDestination = $fileDestination ?: STDOUT;

        while (($line = fgetcsv($fileOrigin, $this->maxLineLenght, $this->delimiter)) !== false) {
            $pathData = $this->calculatePathDataFromLine($line);
            $distance = $this->distanceMatrix->calculateDistance($pathData['origin'], $pathData['destination']);
            $this->writeResultToOutput($fileDestination, $line, $distance);
        }
    }

    private function calculatePathDataFromLine($data)
    {
        $pathData = [
            'destination' => $this->getLastField($data)
        ];

        if ($this->isCommonOriginSpecified()) {
            $pathData['origin'] = $this->commonOrigin;

        } else {
            $pathData['origin'] = $this->getPenultimateField($data);
        }

        return $pathData;
    }

    private function isCommonOriginSpecified()
    {
        return !empty($this->commonOrigin);
    }


    private function getPenultimateField($data)
    {
        return array_slice($data, -2, 1)[0];
    }

    private function getLastField($data)
    {
        return array_slice($data, -1)[0];
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