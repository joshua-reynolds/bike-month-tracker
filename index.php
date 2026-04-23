<!doctype html>
<html>

<body>
  <h1>WFRC Bike Month</h1>
  <p>Progress</p>
  <div id="container"></div>

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

    var bar = new ProgressBar.SemiCircle(container, {
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

    bar.animate(1.0);  // Number from 0.0 to 1.0
  </script>
  <style>
    #container {
      margin: 20px;
      width: 200px;
      height: 100px;
      /* position: relative; */
    }
  </style>
</body>

</html>