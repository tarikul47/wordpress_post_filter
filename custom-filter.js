jQuery(document).ready(function ($) {
  $("#category-filter-form").on("submit", function (e) {
    e.preventDefault();

    console.log('clikc');

    var filterData = $(this).serialize();

    console.log(custom_filter_params);
    $.ajax({
      type: "POST",
      url: custom_filter_params.ajax_url,
      data: filterData,
      success: function (response) {
        $("#filtered-posts").html(response);
      },
    });
  });
});
