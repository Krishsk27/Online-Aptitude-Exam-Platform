(function ($) {
  $.fn.duplifer = function (options) {
    var settings = $.extend(
      {
        colorGenerator: function (index) {
          return '#E94F37';
        },
        highlightClass: 'duplifer',
      },
      options
    );

    var cell_index = this.find('thead tr')
      .find('th.' + settings.highlightClass)
      .index();
    var row_tds = $('tr td:nth-child(' + (cell_index + 1) + ')');
    var row_values = row_tds
      .map(function () {
        return $(this).html();
      })
      .get();

    var duplicates = row_values.filter(function (value, index) {
      return (
        row_values.lastIndexOf(value) == index &&
        row_values.indexOf(value) != index
      );
    });

    $(duplicates).each(function (duplicates_index) {
      row_tds
        .filter(function () {
          return $(this).html() == duplicates[duplicates_index];
        })
        .css('background-color', settings.colorGenerator(duplicates_index))
        .addClass('duplifer-highlighted');
    });
  };
})(jQuery);
