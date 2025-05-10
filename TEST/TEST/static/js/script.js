// Disable right click
document.addEventListener('contextmenu', (event) => event.preventDefault());

// Disble Alt, Ctrl, Shift, and F12
document.onkeydown = function (e) {
  if (e.altKey || e.ctrlKey || e.shiftKey || e.keyCode === 123) {
    return false;
  }
};
