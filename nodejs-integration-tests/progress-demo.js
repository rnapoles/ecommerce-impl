import cliProgress from 'cli-progress';

// create a new progress bar instance and use shades_classic theme
const bar1 = new cliProgress.SingleBar({}, cliProgress.Presets.shades_classic);

// start the progress bar with a total value of 200 and start value of 0
bar1.start(200, 0);

// update the current value in your application..
bar1.update(100);

// stop the progress bar
bar1.stop();

// create new progress bar
const b1 = new cliProgress.SingleBar({
    format: 'CLI Progress |' + colors.cyan('{bar}') + '| {percentage}% || {value}/{total} Chunks || Speed: {speed}',
    barCompleteChar: '\u2588',
    barIncompleteChar: '\u2591',
    hideCursor: true,
    stopOnComplete: true,
    clearOnComplete: true,
});

b1.start(200, 0, {
    speed: "N/A"
});

b1.on('stop', () => {
  console.log('stop');
  console.clear();
});

b1.on('redraw-post', () => {
  let value = parseInt(b1.value);
  console.log(value)
  if(value == 3){
    b1.stop();
    b1.stop();
    b1.update(200);
  }
});
