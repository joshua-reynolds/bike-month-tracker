<!doctype html>

<html>

<head>
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<title>Wellness Spring Training</title>
	<link rel='stylesheet'  href="css/style.css?v=<?php echo time(); ?>">
	<script src="js/script.js"></script>
</head>

<?php
	include_once 'includes/dbconnect.inc.php';
	// $sqli = "SELECT team_id, name, runner1, runner2, runner3, runs, outs FROM teams";
	$sqli = "SELECT t.team_id, t.name
				FROM teams t;";
	$result = mysqli_query($conn, $sqli);
	$rows = array();
	while($r = mysqli_fetch_assoc($result)) {
		$rows[] = $r;
	}


	$jsonData = json_encode($rows);
	// print_r($jsonData)


?>

<script type="text/javascript">
	const teams = <?php echo $jsonData;?>;
	console.log(teams)
</script>



<body>

<div id="headerDiv">
		
		<div id="logoDiv">
			<a href="https://wfrc.org/">
				<img src="graphics/WFRC logo long white_transparent.png" id="logo"  >
			</a>
		</div>

		<div id="titleDiv">
			<h1>Wellness Bike Month (2026)</h1>
		</div>

	</div>




  

  <div class="flex-container">
        
        <div id="imageContentDiv">
            
            <div id="imageDiv">
                <img id="image" src="graphics/biking-in-the-wasatch.png" alt="stadium">
            </div>

            <div id="progress-container"></div>
            
            <div id="contentDiv">
                <p>
                    <h2>The Mission:</h2>
                    <p>Collectively bike the length of the Tour de France (**2,071 miles**) by June 1st!</p> 
                    
                    

                    <h2>Rules of the Road:</h2>
                    <ul>
    
                        <li><strong>All Wheels Welcome:</strong> E-bikes, stationary bikes, and unicycles all count.</li>
                        <li><strong>Bring a Buddy:</strong> Towing a passenger (trailer/carrier)? Count their miles too!</li>
                        <li><strong>Double Miles:</strong> Earn 2x mileage for the <strong>Golden Spoke Ride</strong> on May 16th.</li>
                        <li><strong>No Bike? No Problem:</strong> Walking miles count at a 1:1 ratio.</li>
                        <li>Click <a href="standings.php">here</a> to view the top cyclists.</li>
                    </ul>
                </p>

                <p>
                    <h2>How to play:</h2>
                    <ol>
                    <li><strong>Select</strong> your team from the dropdown.</li>
                    <li><strong>Enter</strong> your total mileage for the ride.</li>
                    <li><strong>Indicate</strong> if it was part of the Golden Spoke Ride.</li>
                    <li><strong>Log it!</strong> Click the button below to register your ride.</li>
                    </ol>
                    <p style="font-size: 0.9em; color: #555;">
                        <em>Flat tire? Email <a href="mailto:josh.reynolds@wfrc.utah.gov">josh.reynolds@wfrc.utah.gov</a> to fix a log </em>
                    </p>
                </p>
            </div>
            
            <div id="contentDiv2">
                <div id="runsP"></div>
                <div id="outsP"></div>
                <div id="atBatsP"></div>
                <br>
                <div id="runnersDiv"></div>
            </div>
        </div>
    
        <div id="teamSelectorDiv">
            <form id="rideForm">
                <div class="select">
                    <select id="teamSelect" name="team" onchange="validateForm()">
                        <option selected disabled>----- Select your team -----</option>
                        <?php
                            $sqli = "SELECT team_id,name FROM teams ORDER BY teams.name ASC;";
                            $result = mysqli_query($conn, $sqli);
                            while ($row = mysqli_fetch_array($result)){
                                echo '<option value='.$row['team_id'].'>'.$row['name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
                <br>
                

                <div class="form-group" id="mileageDiv" >
                    <label for="miles">Enter Mileage:</label>
                    <div>
                        <input type="number" 
                            id="miles" 
                            name="miles" 
                            step="0.5" 
                            min="0" 
                            placeholder="Example: 5" 
                            oninput="validateForm()"
                            required
                            >
                    </div>
                <br>
                </div>
                

                <div class="form-group" id="modeSelectGroup">
                    <label for="modeSelect">Mode:</label>
                    <div class="select">
                        <select id="modeSelect" name="mode" onchange="validateForm()">
                            <option value="Bike" selected>Bike</option>
                            <option value="E-Bike">E-Bike</option>
                            <option value="Stationary Bike">Stationary Bike</option>
                            <option value="Unicycle">Unicycle</option>
                            <option value="Walk">Walk</option>
                        </select>
                    </div>
                <br>
                </div>
                

                <div class="form-group" id="gsSelectGroup">
                    <label for="gsSelect">Was this a Golden Spoke Ride?</label>
                    <div class="select">
                        <select id="gsSelect" name="golden_spoke" onchange="validateForm()">
                            <option value="no" selected>No</option>
                            <option value="maybe">Maybe</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                <br>
                </div>

                
                <div id="buttonDiv">
                    <button id="bat_button" class="btn success" type="button" name="submit" onclick="submitRide()" disabled> Log a ride </button>
                </div>  
            </form>
        </div>  
        
        <div id="footerDiv">
            <b>Sponsored by: </b> <b style="color: #EFBF04;">Beehive Bikeways</b>
        </div>

    </div>










  <script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.1.0/dist/progressbar.min.js"></script>

  <script>
    // var bar = new ProgressBar.Line(container, {
    //   strokeWidth: 4,
    //   easing: "easeInOut",
    //   duration: 3000,
    //   color: "#FFEA82",
    //   trailColor: "#eee",
    //   trailWidth: 1,
    //   svgStyle: { width: "100%", height: "100%" },
    //   text: {
    //     style: {
    //       // Text color.
    //       // Default: same as stroke color (options.color)
    //       color: "#999",
    //       position: "absolute",
    //       right: "0",
    //       top: "30px",
    //       padding: 0,
    //       margin: 0,
    //       transform: null,
    //     },
    //     autoStyleContainer: false,
    //   },
    //   from: { color: "#FFEA82" },
    //   to: { color: "#ED6A5A" },
    //   step: (state, bar) => {
    //     bar.setText(Math.round(bar.value() * 100) + " %");
    //   },
    // });

    // bar.animate(1.0); // Number from 0.0 to 1.0
    var progressContainer = document.getElementById("progress-container")
    var bar = new ProgressBar.SemiCircle(progressContainer, {
      strokeWidth: 6,
      color: '#FFEA82',
      trailColor: '#eee',
      trailWidth: 1,
      easing: 'easeInOut',
      duration: 3000,
      svgStyle: null,
      text: {
        value: '',
        alignToBottom: false
      },
      from: { color: '#FFEA82' },
      to: { color: '#ED6A5A' },
      // Set default step function for all animate calls
      step: (state, bar) => {
        bar.path.setAttribute('stroke', state.color);
        var value = Math.round(bar.value() * 100);
        if (value === 0) {
          bar.setText('');
        } else {
          bar.setText(value + '%');
        }

        bar.text.style.color = state.color;
      }
    });
    bar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
    bar.text.style.fontSize = '2rem';

    
  </script>
</body>

</html>