<?php
session_start();
include_once 'dbconnect.inc.php';

//--------------------------------------
// add bike log to the database
//--------------------------------------

// team id
$team_id = $_POST['team'];


// date
date_default_timezone_set('America/Denver');
$ride_date = date('Y-m-d H:i:s');


// entries entered by user
$miles = $_POST['miles'];
$mode = $_POST['mode'];
$golden_spoke = $_POST['golden_spoke'];


// double miles if goldenspoke
if ($golden_spoke === 'yes') {
  // Double the miles if it was the Golden Spoke ride
  $totalMiles = $miles * 2;
} else {
  // Keep the miles as they are
  $totalMiles = $miles;
}


// prepare dynamic query statement
$sql = "INSERT INTO rides (miles, ride_date, mode, golden_spoke, team_id) 
              VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('dsssi', $totalMiles,  $ride_date, $mode, $golden_spoke, $team_id);
$stmt->execute();


