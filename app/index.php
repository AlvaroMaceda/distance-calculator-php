<?php
require_once 'autoloader.php';

function parseArguments()
{
    array_shift($argv);
    $out = array();
    foreach ($argv as $arg) {
        if (substr($arg, 0, 2) == '--') {
            $eqPos = strpos($arg, '=');
            if ($eqPos === false) {
                $key = substr($arg, 2);
                $out[$key] = isset($out[$key]) ? $out[$key] : true;
            } else {
                $key = substr($arg, 2, $eqPos - 2);
                $out[$key] = substr($arg, $eqPos + 1);
            }
        } elseif (substr($arg, 0, 1) == '-') {
            if (substr($arg, 2, 1) == '=') {
                $key = substr($arg, 1, 1);
                $out[$key] = substr($arg, 3);
            } else {
                $chars = str_split(substr($arg, 1));
                foreach ($chars as $char) {
                    $key = $char;
                    $out[$key] = isset($out[$key]) ? $out[$key] : true;
                }
            }
        } else {
            $out[] = $arg;
        }
    }
    return $out;
}

//$foo = parseArguments();

array_shift($argv);
$key = $argv[0];
$directions = $argv[1];
$origin = $argv[2];

//$origin = 'Lepe';
//$calculator = new \DistanceCalculator\CSVDistanceCalculator($key);
//$fileOrigin = fopen('../test/fixtures/MoreThanTwoColumns.csv', "r");

$calculator = new \DistanceCalculator\CSVDistanceCalculator($key, $origin);
$calculator->setLanguage('es');
//$calculator->setMode(\DistanceCalculator\TravelModes::BICYCLING);
$directionsFile = fopen($directions, "r");
$calculator->calculate($directionsFile);
fclose($directionsFile);
