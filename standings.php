<html>

<head>
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<title>Wellness Bike Month Tracker</title>
	<link rel='stylesheet' href="css/style.css?v=<?php echo time(); ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>

<?php
include_once 'includes/dbconnect.inc.php';

// store the query
$sqli = "SELECT  t.name, COALESCE(SUM(r.miles), 0) AS total_miles
                FROM teams t 
                LEFT JOIN rides r ON t.team_id = r.team_id
                GROUP BY t.team_id, t.name
				ORDER BY total_miles DESC LIMIT 10;";

// connect to db
$result = mysqli_query($conn, $sqli);

// create array and add query results
$rows = array();
while ($r = mysqli_fetch_assoc($result)) {
	$rows[] = $r;
}

// encode the result as a json
$jsonData = json_encode($rows);
// print_r($jsonData)

?>

<script type="text/javascript">
	const teams = <?php echo $jsonData; ?>;
	console.log(teams)
</script>


<body>
	<div id="headerDiv">

		<div id="logoDiv">
			<a href="https://wfrc.org/">
				<img src="graphics/WFRC logo long white_transparent.png" id="logo">
			</a>
		</div>

		<div id="titleDiv">
			<h1>Standings</h1>
		</div>

	</div>

	<div class="flex-container">
		<br>
		<div id="standingsMsgDiv">
			<p id="standingsMsg"> While every mile counts toward our goal, these teams are currently setting the tempo!</p>
		</div>

		<br>
		<?php if (count($rows) > 0): ?>
			<div id="standingsDiv">
				<table>
					<tr>

						<th>Team Name</th>
						<th>Miles</th>

					</tr>

					<?php foreach ($rows as $row): array_map('htmlentities', $row); ?>
						<tr>
							<td><?php echo implode('</td><td>', $row); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		<?php endif; ?>
		<br>
		<div id="teamSelectorDiv">
			<div id=buttonDiv>
				<button id='restart' class="btn success" onclick="goToIndex()"> Return to main page </button>
			</div>
		</div>

		<div id="footerDiv">
			<b>Sponsored by: </b> <b style="color: #EFBF04;">Beehive Bikeways</b>
			<div id="beewaysDiv">
				<img id="beehiveBikewaysLogo" src="graphics/BeehiveLogoHexagon.png" alt="beehive-bikeways">
			</div>

		</div>

	</div>
	<script>
		// return to main button
		function goToIndex() {
			window.location.href = "index.php";
		}
	</script>
</body>

</html>