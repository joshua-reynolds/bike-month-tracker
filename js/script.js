let teams = [];
let grandTotalMiles;
let mileageProgress;
let progresscontainer;
let bar;
const mileGoal = 2071;

// create progress bar
progressContainer = document.getElementById('progress-container');
bar = new ProgressBar.SemiCircle(progressContainer, {
  strokeWidth: 6,
  color: '#fffb00',
  trailColor: '#eeeeee',
  trailWidth: 1,
  easing: 'easeInOut',
  duration: 1800,
  svgStyle: null,
  text: {
    value: '',
    alignToBottom: false,
  },
  from: {
    color: '#92fe9d',
  },
  to: {
    color: '#fffb00',
  },
  // Set default step function for all animate calls
  step: (state, bar) => {
    bar.path.setAttribute('stroke', state.color);

    let displayMiles = Math.round(
      (grandTotalMiles * bar.value()) / mileageProgress,
    );
    if (displayMiles === 0) {
      bar.setText("Let's get started!");
    } else if (bar.value() >= 1 && mileageProgress >= 1) {
      bar.setText('🏆 GOAL REACHED: ' + Math.round(grandTotalMiles) + ' Miles');
      bar.text.style.fontWeight = 'bold';
    } else {
      bar.setText(displayMiles + ' / ' + mileGoal + ' Miles');
    }
    bar.text.style.color = state.color;
  },
});
bar.text.style.fontFamily = '"Lexend", sans-serif';
bar.text.style.fontSize = '1.5rem';

function celebrate() {
  confetti({
    particleCount: 150,
    spread: 70,
    origin: {
      y: 0.6,
    },
    colors: ['#00d2ff', '#92fe9d', '#fde047'], // Use your gradient colors!
  });
}

// Fetch the Teams data and the benchmarks at the same time
Promise.all([
  fetch('includes/getJSON.php').then((res) => res.json()),
  fetch('data/benchmarks.json').then((res) => res.json()),
])
  .then(([newTeams, benchmarks]) => {
    // --- PART A: PROCESS TEAMS ---
    teams = newTeams;
    grandTotalMiles = teams.reduce((accumulator, team) => {
      return accumulator + (parseFloat(team.total_miles) || 0);
    }, 0);

    // get the miles, animate the bar
    mileageProgress = grandTotalMiles / mileGoal;
    bar.animate(mileageProgress);

    // --- PART B: PROCESS BENCHMARKS ---
    // Now grandTotalMiles is GUARANTEED to exist and be a number
    const achieved = benchmarks
      .filter((b) => b.distance <= grandTotalMiles)
      .reduce((prev, curr) => (curr.distance > prev.distance ? curr : prev));

    // find the benchmark div and update the text
    document.getElementById('benchmarkDiv').innerHTML = `
      <h3>Fun Fact: ${achieved.icon} ${achieved.benchmark}</h3>
      <p>${achieved.fact}</p>
  `;

    // if Goal as been reached show confetti
    if (mileageProgress >= 1) {
      celebrate();
    }
  })
  .catch((err) => console.error('Error loading data:', err));

// modal page stuff
const modal = document.getElementById('aboutModal');
const aboutBtn = document.getElementById('aboutBtn');
const span = document.getElementsByClassName('close')[0];

aboutBtn.onclick = () => (modal.style.display = 'block');
span.onclick = () => (modal.style.display = 'none');

// Close if they click anywhere OUTSIDE the box
window.onclick = (event) => {
  if (event.target == modal) modal.style.display = 'none';
};

// standings button
function goToStandings() {
  window.location.href = 'standings.php';
}

function redirectTo(sUrl) {
  window.location = sUrl;
}

function celebrate() {
  confetti({
    particleCount: 150,
    spread: 70,
    origin: {
      y: 0.6,
    },
    colors: ['#00d2ff', '#92fe9d', '#fde047'], // Use your gradient colors!
  });
}

function validateForm() {
  const submitBtn = document.getElementById('submitBtn');
  const mileageInput = document.getElementById('miles');
  const teamSelect = document.getElementById('teamSelect');
  const modeSelect = document.getElementById('modeSelect');
  const goldenSpokeSelect = document.getElementById('gsSelect');

  const hasMileage =
    mileageInput.value.trim() !== '' && parseFloat(mileageInput.value) > 0;

  const isTeamSelected = teamSelect.value !== '';
  const isModeSelected = modeSelect.value !== '';
  const isGoldenSpokeSelected = goldenSpokeSelect.value !== '';

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
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      alert('Ride logged! Excellent work!');
      document.getElementById('miles').value = '';
      document.getElementById('submitBtn').disabled = true;
      return fetch('includes/getJSON.php');
    })
    .then((response) => response.json())
    .then((newTeams) => {
      // overwrite team global with updated teams
      teams = newTeams;

      // console.log('Updated teams:', teams);
      updateTeamStats(document.getElementById('teamSelect').value);

      // update total team miles
      grandTotalMiles = teams.reduce((accumulator, team) => {
        return accumulator + parseFloat(team.total_miles);
      }, 0);

      mileageProgress = grandTotalMiles / mileGoal;
      bar.animate(mileageProgress);

      // launch fireworks if goal is met
      if (mileageProgress >= 1) {
        celebrate();
      }
    })
    .catch((error) => {
      console.error('Error in the sequence:', error);
    });
}

updateTeamStats = function (_team_id) {
  const team_object = teams.find((team) => team.team_id === _team_id);
  const team_rides = team_object.total_rides;
  const team_miles = team_object.total_miles;
  const message1 = `Your Rides Logged: ${team_rides}`;
  const message2 = `Your Miles Pedaled: ${team_miles}`;
  document.getElementById('teamRidesText').innerHTML = message1;
  document.getElementById('teamMilesText').innerHTML = message2;
};

// When a team is selected with the drop down
window.onload = function () {
  if (document.getElementById('teamSelect')) {
    document
      .getElementById('teamSelect')
      .addEventListener('change', function () {
        let team_id = document.getElementById('teamSelect').value;
        updateTeamStats(team_id);

        let team_object = teams.find((team) => team.team_id === team_id);
        let team_rides = team_object.total_rides;
        let team_miles = team_object.total_miles;

        document.getElementById('benchmarkDiv').style.display = 'none';
        document.getElementById('mileageDiv').style.display = 'grid';
        document.getElementById('modeSelectGroup').style.display = 'grid';
        document.getElementById('gsSelectGroup').style.display = 'grid';

        // Update progress bar
        bar.animate(mileageProgress);

        document.getElementById('teamContentDiv').style.display = 'grid';
      });
  }
};

// Easter Egg
let clickCount = 0;

const secretTrigger = document.getElementById('beehiveBikewaysLogo');

secretTrigger.addEventListener('click', () => {
  clickCount++;

  if (clickCount === 3) {
    openSecretVideo();
    clickCount = 0; // Reset
  }

  // Reset count if they don't click fast enough (within 1 second)
  setTimeout(() => {
    clickCount = 0;
  }, 1000);
});

function openSecretVideo() {
  const overlay = document.getElementById('video-easter-egg');
  const video = document.getElementById('secret-video');

  overlay.style.display = 'flex';

  // Attempt to play
  let playPromise = video.play();

  if (playPromise !== undefined) {
    playPromise.catch((error) => {
      console.log("Autoplay prevented. Show a 'Play' button.");
      // You could show a "Tap to Start" button here if it fails
    });
  }
}

function closeSecretVideo() {
  const overlay = document.getElementById('video-easter-egg');
  const video = document.getElementById('secret-video');

  video.pause();
  video.currentTime = 0; // Rewind for next time
  overlay.style.display = 'none';
}
