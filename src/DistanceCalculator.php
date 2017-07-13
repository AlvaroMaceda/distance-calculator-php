<?php
namespace DistanceCalculator;

require_once 'autoloader.php';

class DistanceCalculator
{

    const API_HOST = 'https://maps.googleapis.com';
    const API_URL = '/maps/api/distancematrix/json?';
    const DEFAULT_UNItS = 'metric';

    private $units = 0;
    private $key = '';
    private $origin;

    public function __construct($key, $origin = null, $units = null)
    {
        $this->key = $key;
        $this->origin = $origin ?: $this->origin;
        $this->units = $units ?: $this->units;
    }

    public function calculate($fileName)
    {
        $row = 1;
        if (($handle = fopen($fileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $this->calculateDistance($data[1], $data[0]);
            }
            fclose($handle);
        }
    }

    private function calculateDistance($origin, $destination)
    {
        $url = $this->generateRequestURL($origin, $destination);
        $body = file_get_contents($url);
        $json = json_decode($body);
        $data = $this->parseDistanceData($json);
        echo($origin." to ".$destination."->".$data."\n");
    }

    private function parseDistanceData($data)
    {
        if ($data->status != 'OK') {
            throw new \Exception($data->error_message);
        }

        $line = $data->rows[0]->elements[0];
        if ($line->status==='OK') {
            return new DistanceData(
                $line->distance->value,
                $line->distance->text,
                $line->duration->value,
                $line->duration->text
            );
        } else {
            return new NullDistanceData("Error calculating distance: {$line->status}");
        }
    }

    private function getBaseURL()
    {
        return DistanceCalculator::API_HOST . DistanceCalculator::API_URL;
    }

    private function generateRequestURL($origin, $destination)
    {
        $parameters = array(
            'units' => $this->units,
            'key'=> $this->key,
            'origins'=> $origin,
            'destinations' => $destination
        );
        $query = http_build_query($parameters);

        return $this->getBaseURL() . $query;
    }


}