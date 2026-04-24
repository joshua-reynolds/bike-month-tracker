<?php

$dbServername = "localhost";
$dbUsername = "jreynolds";
$dbPassword = 'popcorner';
$dbName = 'wellness_bike_month'; // change this to new db every week

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

?>