let timeLimit = 3600; // This is the time duration of the test in seconds
let savedCountdown = localStorage.getItem('savedCountdown'); // See if the time we are counting down to is stored in local storage

if (savedCountdown == null) {
  // Set the time we're counting down to using the timeLimit allowed
  let newCountdown = new Date().getTime() + timeLimit * 1000;
  timeLimit = newCountdown;
  // Store the time we are counting down to in local storage
  localStorage.setItem('savedCountdown', newCountdown);
} else {
  timeLimit = savedCountdown;
}

// Update the count down every 1 second
const x = setInterval(() => {
  // Get current date and time
  let currentTime = new Date().getTime();

  // Find the time remaining between currentTime and submission time
  let timeRemaining = timeLimit - currentTime;

  // Time calculations for minutes and seconds
  let timerHour = Math.floor(
    (timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
  );
  let timerMinute = Math.floor(
    (timeRemaining % (1000 * 60 * 60)) / (1000 * 60)
  );
  let timerSecond = Math.floor((timeRemaining % (1000 * 60)) / 1000);

  // Select DOM element where you wish to place the timer
  const timer = document.querySelector('#timer');

  if (timerSecond >= 10) {
    if (timerMinute >= 10) {
      timer.innerHTML = '0' + timerHour + ':' + timerMinute + ':' + timerSecond;
    } else {
      timer.innerHTML =
        '0' + timerHour + ':' + '0' + timerMinute + ':' + timerSecond;
    }
  } else if (timerSecond < 10) {
    if (timerMinute >= 10) {
      timer.innerHTML =
        '0' + timerHour + ':' + timerMinute + ':0' + timerSecond;
    } else {
      timer.innerHTML =
        '0' + timerHour + ':' + '0' + timerMinute + ':0' + timerSecond;
    }
  }

  if (timerSecond == 0) {
    if (timerHour == 0 && timerMinute == 0 && timerSecond == 0) {
      document.getElementById('test').submit();
    }
    if (timerHour == 0 && timerMinute == 1) {
      timer.style.color = 'red';
    }
  }

  if (timerMinute < 0) {
    document.getElementById('test').submit();
  }

  if (timeRemaining < 0) {
    clearInterval(x);
    localStorage.removeItem('savedCountdown');
  }
}, 1000);
