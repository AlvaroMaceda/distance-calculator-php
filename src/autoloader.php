<?php

define("USE_COMPOSER", false);

if (USE_COMPOSER) {
    require_once '../vendor/autoload.php';
} else {
    require_once 'DistanceData.php';
    require_once 'NullDistanceData.php';
    require_once 'GoogleDistanceMatrix.php';
    require_once 'CSVDistanceCalculator.php';
}
