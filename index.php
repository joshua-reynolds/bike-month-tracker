<!doctype html>

<html>

<head>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>Wellness Bike Month Tracker</title>
    <link rel="icon" type="image/png" href="graphics/BeehiveLogoHexagon.png">
    <link rel='stylesheet' href="css/style.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.1.0/dist/progressbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>

<?php
include_once 'includes/dbconnect.inc.php';
?>


<body>

    <div id="headerDiv">
        <div id="logoDiv">
            <a href="https://wfrc.org/">
                <img src="graphics/WFRC logo long white_transparent.png" id="logo">
            </a>
        </div>

        <div id="titleDiv">
            <h1>Wellness Bike Month Tracker (2026)</h1>
        </div>
    </div>

    <div id="aboutModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>


            <div id="contentDiv">
                <div id="imageDiv">
                    <img id="image" src="graphics/biking-in-the-wasatch.png" alt="biking-in-the-wasatch">
                </div>
                <h2>The Mission:</h2>
                <p>Collectively bike the length of the Tour de France (2,071 miles) by June 1st! <br><br>The prize? A picnic celebration with food, frisbee, games, and plenty of outdoor fun.

                <h2>Rules of the Road:</h2>
                <ul>

                    <li><strong>All Wheels Welcome:</strong> E-bikes, stationary bikes, and unicycles all count.</li>
                    <li><strong>Bring a Buddy:</strong> Towing a passenger (trailer/carrier)? Count their miles too!</li>
                    <li><strong>Double Miles:</strong> Earn 2x mileage for the <strong>Golden Spoke Ride</strong> on May 16th.</li>
                    <li><strong>No Bike? No Problem:</strong> Walking miles count at a 1:1 ratio.</li>
                    
                </ul>

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
            </div>
        </div>
    </div>


    <div class="flex-container">
        <div id="imageContentDiv">

            <div id="progress-container"></div>
            <br>
            <br>
            <div id="benchmarkDiv">

            </div>
            <div id="teamContentDiv">
                <div id="teamRidesText"></div>
                <div id="teamMilesText"></div>
            </div>
        </div>

        <br>

        <div id="teamSelectorDiv">
            <form id="rideForm">
                <div class="select">
                    <select id="teamSelect" name="team" onchange="validateForm()">
                        <option selected disabled>----- Select your team -----</option>
                        <?php
                        $sqli = "SELECT team_id,name FROM teams ORDER BY teams.name ASC;";
                        $result = mysqli_query($conn, $sqli);
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<option value=' . $row['team_id'] . '>' . $row['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <br>


                <div class="form-group" id="mileageDiv">
                    <label for="miles">Enter Mileage:</label>
                    <div>
                        <input type="number"
                            id="miles"
                            name="miles"
                            step="0.5"
                            min="0"
                            placeholder="Example: 5"
                            oninput="validateForm()"
                            required>
                    </div>
                    <br>
                </div>


                <div class="form-group" id="modeSelectGroup">
                    <label for="modeSelect">Mode:</label>
                    <div class="select">
                        <select id="modeSelect" name="mode" onchange="validateForm()">
                            <option value="Bike" selected>Bike</option>
                            <option value="E_Bike">E-Bike</option>
                            <option value="Stationary_Bike">Stationary Bike</option>
                            <option value="Unicycle">Unicycle</option>
                            <option value="Walk_Run">Walk/Run</option>
                            <option value="Other">Other</option>
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
                    <button id="submitBtn" class="btn success" type="button" name="submit" onclick="submitRide()" disabled> Log my ride! </button>


                </div>

                <br>
                <div id="buttonDiv2">

                    <button id="aboutBtn" class="btn success" type="button" style="background-color: #464646;">About the Challenge</button>


                    <button id="standingsBtn" onclick="goToStandings()" class="btn success" type="button" style="background-color: #474747;">Standings</button>
                </div>
            </form>


        </div>
        <br>

        <div id="footerDiv">
            <b>Sponsored by: </b> <b style="color: #EFBF04;">Beehive Bikeways</b>
            <div id="beewaysDiv">
                <img id="beehiveBikewaysLogo" src="graphics/BeehiveLogoHexagon.png" alt="beehive-bikeways">
            </div>
        </div>

    </div>

    <div id="video-easter-egg" class="video-overlay" onclick="closeSecretVideo()">
        <div class="video-content">
            <video id="secret-video" width="100%" height="auto" playsinline webkit-playsinline muted>
                <source src="graphics/Video_Animation_Request_Fulfilled.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <p>🚲 Congrats, you found the secret! 🚲</p>
        </div>
    </div>





    <script>

    </script>
    <script src="js/script.js"></script>


</body>

</html>