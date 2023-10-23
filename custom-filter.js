jQuery(document).ready(function ($) {
  $("#category-filter-form").on("submit", function (e) {
    e.preventDefault();

    var filterData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: custom_filter_params.ajax_url,
      data: filterData,
      success: function (response) {
        $("#filtered-posts").html(response);
        console.log("response", response);
      },
    });
  });
});
