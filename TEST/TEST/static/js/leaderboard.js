// Initiailize Duplifier
$('#scores').duplifer();

const table = $('#scores').DataTable({
  // Disable paging
  paging: false,

  // Enable fixed header
  fixedHeader: true,

  // Disabled searching and ordering on the first column (SNO)
  columnDefs: [
    {
      searchable: false,
      orderable: false,
      targets: 0,
    },

    // Disable ordering on the fourth column (E-Mail ID)
    {
      orderable: false,
      targets: 4,
    },
  ],

  // Order by register number column initially
  order: [[1, 'asc']],

  // Enable search builder
  searchBuilder: true,

  // Enable responsive nature
  responsive: true,
});

// Attach SearchBuilder to DOM
table.searchBuilder.container().prependTo(table.table().container());

// SNO must not change on sorting other columns
table
  .on('order.dt search.dt', () => {
    table
      .column(0, { search: 'applied', order: 'applied' })
      .nodes()
      .each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
  })
  .draw();
