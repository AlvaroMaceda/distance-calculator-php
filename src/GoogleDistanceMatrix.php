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
    const DEFAULT_LANGUAGE = 'en';

    private $units;
    private $key = '';
    private $language;

    public function __construct($key, $units = null)
    {
        $this->key = $key;
        $this->units = $units ?: static::DEFAULT_UNITS;
        $this->language = static::DEFAULT_LANGUAGE;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
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
            $error = $this->obtainResponseErrorMessage($data);
            return new NullDistanceData($error);
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
            $error = $this->obtainLineErrorMessage($line);
            return new NullDistanceData($error);
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
            'language' => $this->language,
            'key'=> $this->key,
            'origins'=> $origin,
            'destinations' => $destination
        );
        $query = http_build_query($parameters);

        return $this->getBaseURL() . $query;
    }

    private function obtainResponseErrorMessage($data)
    {
        $error = '';
        if (isset($data->error_message)) {
            $error .= $data->error_message;
        }
        $error .= ' Status:' . $data->status;
        return $error;
    }

    private function obtainLineErrorMessage($line)
    {
        return $line->status;
    }

}