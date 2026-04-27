<?php
session_start();
include_once 'dbconnect.inc.php';

$sqli = "SELECT t.team_id, t.name, COALESCE(SUM(r.miles), 0) AS total_miles, COALESCE(COUNT(r.team_id), 0) AS total_rides
                FROM teams t
                LEFT JOIN rides r ON t.team_id = r.team_id
                GROUP BY t.team_id, t.name;";
$result = mysqli_query($conn, $sqli);
$rows = array();
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}


$jsonData = json_encode($rows);

// Set header and output the data
header('Content-Type: application/json');
echo json_encode($rows);
exit();