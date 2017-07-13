<?php
namespace DistanceCalculator;

class DistanceCalculator
{

    const API_HOST = 'https://maps.googleapis.com';
    const API_URL = '/maps/api/distancematrix/json';

    public function calculate($fileName)
    {
        $row = 1;
        if (($handle = fopen($fileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $this->calculateDistance()
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }

    private function calculateDistance($origin, $destination) {
        $url = $this->generateRequestURL();
        $xml = file_get_contents("http://www.example.com/file.xml");
    }

    private function generateRequestURL()
    {
        return DistanceCalculator::API_HOST . "/" . DistanceCalculator::API_URL;
    }


}