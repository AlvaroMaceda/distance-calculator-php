<?php
/**
 * Created by PhpStorm.
 * User: alvaro
 * Date: 13/07/17
 * Time: 12:33
 */

namespace DistanceCalculator;


class GoogleDistanceMatrix
{
    const API_HOST = 'https://maps.googleapis.com';
    const API_URL = '/maps/api/distancematrix/json?';
    const DEFAULT_UNITS = 'metric';

    private $units;
    private $key = '';

    public function __construct($key, $units = null)
    {
        $this->key = $key;
        $this->units = $units ?: static::DEFAULT_UNITS;
    }

    public function calculateDistance($origin, $destination)
    {
        $url = $this->generateRequestURL($origin, $destination);
        $body = file_get_contents($url);
        $json = json_decode($body);
        $data = $this->parseDistanceData($json);

        return $data;
    }

    private function parseDistanceData($data)
    {
        if ($data->status != 'OK') {
            return new NullDistanceData("Error calculating distance: {$data->error_message}");
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
        return static::API_HOST . static::API_URL;
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