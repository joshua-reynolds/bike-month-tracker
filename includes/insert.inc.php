<?php
  session_start();
  include_once 'dbconnect.inc.php';
  // include_once 'runBases.inc.php';

  //--------------------------------------
  // add bat result to the database
  //--------------------------------------

  // team id
  $team_id = $_POST['team'];
  // $sqli2 = "SELECT team_id, name FROM teams;";
  // // $sqli2 = "SELECT t.team_id, t.name, t.runner1, t.runner2, t.runner3, t.runs, t.outs, COALESCE(count_attempts_day, 0) AS count_attempts_day
	// // 			FROM teams t
	// // 			LEFT JOIN (
	// // 				SELECT team_id, COUNT(*) AS count_attempts_day
	// // 				FROM bats
	// // 				WHERE DATE(bat_date) = CURDATE()
	// // 				GROUP BY team_id
	// // 			) sub ON t.team_id = sub.team_id;";
  // $result2 = mysqli_query($conn, $sqli2);
  // $rows2 = array();
  // while($r = mysqli_fetch_assoc($result2)) {
  //   $rows2[] = $r;
  // }

  // $names = array_column($rows2, 'name');
  // $key = array_search($team_name, $names, true);
  // $t_id = $rows2[$key]['team_id'];
  
  // $runner1 = $rows2[$key]['runner1'];
  // $runner2 = $rows2[$key]['runner2'];
  // $runner3 = $rows2[$key]['runner3'];
  // $runs = $rows2[$key]['runs'];
  // $outs = $rows2[$key]['outs'];
  // $count_attempts_day = $rows2[$key]['count_attempts_day'];
  // $remaining_attempts = ((3 - $count_attempts_day) < 0) ? 0 : (3 - $count_attempts_day);

  // date
  date_default_timezone_set('America/Denver');
  $ride_date = date('Y-m-d H:i:s');

  // // // query to count today's at bats for specified team
  // // $sqli3 = "SELECT COUNT(*) FROM `bats` WHERE team_id =?  AND DATE(`bat_date`) = CURDATE()";
  // // $stmt3 = $conn->prepare($sqli3);
  // // $stmt3->bind_param('i' , $t_id);
  // // $stmt3->execute();
  // // $stmt3->bind_result($count_atbats_current_day);
  // // $stmt3->fetch();

  // // if ($count_atbats_current_day < 3){
  // //   $batting_allowed = TRUE;
  // // } else{
  // //   $batting_allowed = FALSE;
  // // }


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
  $stmt->bind_param('dsssi' , $totalMiles,  $ride_date, $mode, $golden_spoke, $team_id);
  $stmt->execute();


  // //--------------------------------------
  // // update runners and runs in database
  // //--------------------------------------

  // $rbResult = runBases($bat_result, $runner1, $runner2, $runner3, $outs);
  // $runs = $runs + $rbResult[3];

  // $sql2 = "UPDATE teams SET runner1=?, runner2=?, runner3=?, runs=?, outs=? WHERE team_id=?";

  // $stmt2 = $conn->prepare($sql2);
  // $stmt2->bind_param('iiiiii' , $rbResult[0],  $rbResult[1], $rbResult[2], $runs, $rbResult[4], $t_id);
  // $stmt2->execute();

  // //--------------------------------------
  // // store  session variables
  // //--------------------------------------
  
  // $_SESSION["t_id"] = $t_id;
  // $_SESSION["team_name"] = $team_name;
  // $_SESSION["bat_result"] = $bat_result;
  // $_SESSION["runner1"] = $rbResult[0];
  // $_SESSION["runner2"] = $rbResult[1];
  // $_SESSION["runner3"] = $rbResult[2];
  // $_SESSION["new_runs"] = $rbResult[3];
  // $_SESSION["runs"] = $runs;
  // $_SESSION["outs"] = $rbResult[4];
  // $_SESSION["remaining_attempts"] = $remaining_attempts - 1;


  // header("Location: ../index.php?bat=success");
  // header("Location: ../result.php");
  // header("Location: ../index.php?status=success");
