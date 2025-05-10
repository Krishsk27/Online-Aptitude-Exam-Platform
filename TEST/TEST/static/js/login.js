// Delete any existing countdown stored
localStorage.removeItem('savedCountdown');

// UI Dropdown
$('.ui.dropdown').dropdown();

// Form Validation
$('.ui.form').form({
  fields: {
    name: 'empty',
    regIn: ['empty', 'regExp[/^19[0-9]{7}$/]'],
    dept: 'empty',
    email: ['empty', 'regExp[/^2019.+@svce.ac.in$/]'],
  },
});
