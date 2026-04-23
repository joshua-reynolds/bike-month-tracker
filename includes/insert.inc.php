<?php
  session_start();
  include_once 'dbconnect.inc.php';
  include_once 'runBases.inc.php';

  //--------------------------------------
  // add bat result to the database
  //--------------------------------------

  // team id
  $team_name = $_POST['team_name'];
  // $sqli2 = "SELECT team_id, name, runner1, runner2, runner3, runs, outs FROM teams";
  $sqli2 = "SELECT t.team_id, t.name, t.runner1, t.runner2, t.runner3, t.runs, t.outs, COALESCE(count_attempts_day, 0) AS count_attempts_day
				FROM teams t
				LEFT JOIN (
					SELECT team_id, COUNT(*) AS count_attempts_day
					FROM bats
					WHERE DATE(bat_date) = CURDATE()
					GROUP BY team_id
				) sub ON t.team_id = sub.team_id;";
  $result2 = mysqli_query($conn, $sqli2);
  $rows2 = array();
  while($r = mysqli_fetch_assoc($result2)) {
    $rows2[] = $r;
  }
  $names = array_column($rows2, 'name');
  $key = array_search($team_name, $names, true);
  $t_id = $rows2[$key]['team_id'];
  $runner1 = $rows2[$key]['runner1'];
  $runner2 = $rows2[$key]['runner2'];
  $runner3 = $rows2[$key]['runner3'];
  $runs = $rows2[$key]['runs'];
  $outs = $rows2[$key]['outs'];
  $count_attempts_day = $rows2[$key]['count_attempts_day'];
  $remaining_attempts = ((3 - $count_attempts_day) < 0) ? 0 : (3 - $count_attempts_day);

  // date
  date_default_timezone_set('America/Denver');
  $bat_date = date('Y-m-d H:i:s');

  // // query to count today's at bats for specified team
  // $sqli3 = "SELECT COUNT(*) FROM `bats` WHERE team_id =?  AND DATE(`bat_date`) = CURDATE()";
  // $stmt3 = $conn->prepare($sqli3);
  // $stmt3->bind_param('i' , $t_id);
  // $stmt3->execute();
  // $stmt3->bind_result($count_atbats_current_day);
  // $stmt3->fetch();

  // if ($count_atbats_current_day < 3){
  //   $batting_allowed = TRUE;
  // } else{
  //   $batting_allowed = FALSE;
  // }


  // bat result
  $bat_result = rand(0,4);

  // prepare dynamic query statement
  $sql = "INSERT INTO bats (bat_result, bat_date, team_id) 
              VALUES (?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('isi' , $bat_result,  $bat_date, $t_id);
  $stmt->execute();


  //--------------------------------------
  // update runners and runs in database
  //--------------------------------------

  $rbResult = runBases($bat_result, $runner1, $runner2, $runner3, $outs);
  $runs = $runs + $rbResult[3];

  $sql2 = "UPDATE teams SET runner1=?, runner2=?, runner3=?, runs=?, outs=? WHERE team_id=?";

  $stmt2 = $conn->prepare($sql2);
  $stmt2->bind_param('iiiiii' , $rbResult[0],  $rbResult[1], $rbResult[2], $runs, $rbResult[4], $t_id);
  $stmt2->execute();

  //--------------------------------------
  // stote  session variables
  //--------------------------------------
  
  $_SESSION["t_id"] = $t_id;
  $_SESSION["team_name"] = $team_name;
  $_SESSION["bat_result"] = $bat_result;
  $_SESSION["runner1"] = $rbResult[0];
  $_SESSION["runner2"] = $rbResult[1];
  $_SESSION["runner3"] = $rbResult[2];
  $_SESSION["new_runs"] = $rbResult[3];
  $_SESSION["runs"] = $runs;
  $_SESSION["outs"] = $rbResult[4];
  $_SESSION["remaining_attempts"] = $remaining_attempts - 1;


  // header("Location: ../index.php?bat=success");
  header("Location: ../result.php");
