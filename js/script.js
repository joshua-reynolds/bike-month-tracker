/* 
function revealMessage() {
	document.getElementById("hiddenMessage").style.display = 'block';
}



function countDown(){
	var currentVal = document.getElementById("countDownButton").innerHTML;
	var newVal = 0;

	if (currentVal > 0){
		newVal = currentVal - 1;
	}
	document.getElementById('countDownButton').innerHTML = newVal;
}
*/

function getTeam() {
  var e = document.getElementById("teamSelect").value;
  document.getElementById("team_name").innerHTML = e;
}

function updateMainDiv() {
  document.getElementById("main").innerHTML = "";
}

function revealMessage2() {
  document.getElementById("hiddenMessage2").style.display = "block";
}

function play0() {
  var audio = new Audio("sound_effects/Laugh.mp3");
  audio.play();
}

function play1() {
  var audio = new Audio("sound_effects/Baseball_Hit_Sound_Effect.mp3");
  audio.play();
}

function play2() {
  var audio = new Audio(
    "sound_effects/Baseball_Hit_With_Cheer_Sound_Effect.mp3"
  );
  audio.play();
}



function redirectTo(sUrl) {
  window.location = sUrl;
}

function bat() {
  // get teamName/id from drop down somehow
  var tn = document.getElementById("team_name").innerHTML;

  // get id

  // get date

  let result = Math.floor(Math.random() * 5);

  if (result == 0) {
    play0();
  } else if (result > 0 && result <= 2) {
    play1();
  } else if (result > 2) {
    play2();
  }

  const results = {
    0: "Out",
    1: "Single",
    2: "Double",
    3: "Triple!",
    4: "Home-Run!!",
  };
  document.getElementById("bat_result").innerHTML = results[result];

  // create an insert record using team id, date, and result
}

function bat2() {
  $.ajax({
    type: "POST",
    url: "results.php",
    dataType: "json",
    success: function (response) {},
  });
}

function validateForm() {
	const submitBtn = document.getElementById("bat_button");
	const mileageInput = document.getElementById("miles");
	const teamSelect = document.getElementById("teamSelect");
	const modeSelect = document.getElementById("modeSelect");
	const goldenSpokeSelect = document.getElementById("gsSelect");

	const hasMileage = mileageInput.value.trim() !== "" && parseFloat(mileageInput.value) > 0;

	const isTeamSelected = teamSelect.value !== "" ;
	const isModeSelected = modeSelect.value !== "" ;
	const isGoldenSpokeSelected = goldenSpokeSelect.value !== "" ;

	if (isTeamSelected && hasMileage && isModeSelected && isGoldenSpokeSelected) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;

    }

	
}

function submitRide() {
    const formData = new FormData();
    formData.append('miles', document.getElementById('miles').value);
    formData.append('team', document.getElementById('teamSelect').value);
    formData.append('mode', document.getElementById('modeSelect').value);
    formData.append('golden_spoke', document.getElementById('gsSelect').value);

    fetch('includes/insert.inc.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert("Ride logged successfully!");
        // Optional: Reset the form or update the progress bar here
    })
    .catch(error => console.error('Error:', error));
}

window.onload = function () {


	if(document.getElementById("teamSelect")){
		document.getElementById("teamSelect").addEventListener("change", function () {
			// Clear the content of the div
			// document.getElementById("contentDiv").innerHTML = "status goes here...";
			var team_name = document.getElementById("teamSelect").value;
			// console.log(team_name)
			var team = teams.find(team => team.name === team_name);
			// var r1 = parseInt(team.runner1);
			// var r2 = parseInt(team.runner2);
			// var r3 = parseInt(team.runner3);
			// var runners = Array(r1, r2, r3);
			// var runs = parseInt(team.runs);
			// var outs = parseInt(team.outs);
			// var count_attempts_day = parseInt(team.count_attempts_day);
			// var remaining_attempts = (3 - count_attempts_day) < 0 ? 0 : (3 - count_attempts_day);

			// // current runners (query database)
			// if (runners.includes(1) == true && runners.includes(2) == true && runners.includes(3) == true){
			// 	var image =  '<img id=bases src="graphics/bases-first-second-third.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == true && runners.includes(2) == true && runners.includes(3) == false){
			// 	var image =  '<img id=bases src="graphics/bases-first-second.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == true && runners.includes(2) == false && runners.includes(3) == true){
			// 	var image =  '<img id=bases src="graphics/bases-first-third.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == false && runners.includes(2) == true && runners.includes(3) == true){
			// 	var image =  '<img id=bases src="graphics/bases-second-third.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == true && runners.includes(2) == false && runners.includes(3) == false){
			// 	var image =  '<img id=bases src="graphics/bases-first-only.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == false && runners.includes(2) == true && runners.includes(3) == false){
			// 	var image =  '<img id=bases src="graphics/bases-second-only.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == false && runners.includes(2) == false && runners.includes(3) == true){
			// 	var image =  '<img id=bases src="graphics/bases-third-only.png" alt="homerun">';
			// }
			// else if(runners.includes(1) == false && runners.includes(2) == false && runners.includes(3) == false){
			// 	var image =  '<img id=bases src="graphics/bases-empty.png" alt="homerun">';
			// }

			// message1 = `<p> Total Runs: ${runs}</p> <p> Current Outs: ${outs}</p>`
			// message1 = `Your Total Rides: ${runs}`
			// message2 = `Your Team Mileage: ${outs}`
			message1 = `Your Total Rides: `
			message2 = `Your Team Mileage: `
			
			document.getElementById("contentDiv").style.display = 'none';
			document.getElementById("imageDiv").style.display = 'none';
			document.getElementById("imageDiv").style.display = 'none';

			document.getElementById("progress-container").style.display = 'grid';
			document.getElementById("mileageDiv").style.display = 'grid';
			document.getElementById("modeSelectGroup").style.display = 'grid';
			document.getElementById("gsSelectGroup").style.display = 'grid';
			document.getElementById("buttonDiv").style.display = 'grid';
			// bar.set(0); this retriggers animation
			bar.animate(1.0);  // Number from 0.0 to 1.0, this amount will be the amount from the database
			document.getElementById("contentDiv2").style.display = 'grid';
			document.getElementById("runsP").innerHTML = message1;
			document.getElementById("outsP").innerHTML = message2;
			// document.getElementById("atBatsP").innerHTML = message3;
			// document.getElementById("runnersDiv").innerHTML = image;
			
			// setTimeout(function() {
			// 	if (remaining_attempts == 0){
			// 		alert(`You have completed all of your at-bats for today. Please check back in tomorrow!`)
			// 	}
			// }, 100);
			
		});
	}
};
