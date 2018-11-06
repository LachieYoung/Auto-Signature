// Listen for manual page load and populate dropdown with avaliable templates
if (top.location.pathname === "/manual.php") {
  $.ajax({
    type: "POST",
    url: "ajax-handler.php",
    data: {
      action: "ajax_get_templates"
    },
    dataType: "json",
    success: function(data) {
      $.each(data, function(key, value) {
        $("#as-template-select").append(
          "<option value='" + value + "'>" + value + "</option>"
        );
      });

      if ($("#as-template-select option").length == 2) {
        $("#as-template-select option:nth-child(2)").attr("selected", true);
        $("#as-template-select").trigger("change");
      }
    },
    error: function() {
      alert("Oops, look like we experienced an error!");
    }
  });
}

// Create dynamic form once user selects template
$("#as-template-select").change(function() {
  var template = $("#as-template-select")
    .find(":selected")
    .text();
  $.ajax({
    type: "POST",
    url: "ajax-handler.php",
    data: {
      action: "ajax_get_template_tags",
      template: template
    },
    dataType: "json",
    success: function(data) {
      $("#as-manual-form").remove();
      $("#as-template-select").after('<div id="as-manual-form"></div>');

      $.each(data, function(key, value) {
        $("#as-manual-form").append(
          "<label for='as-manual-" +
            value +
            "'>" +
            value +
            "</label><input type='text' name='as-manual-" +
            value +
            "' id='as-manual-" +
            value +
            "'>"
        );
      });

      $("#as-manual-form input")
        .last()
        .after('<button type="submit" id="as-manual-preview">Preview</button>');

      $("#as-manual-form").wrapInner(
        "<form name='as-manual-form-inner' method='POST' id='as-manual-form-inner'></form>"
      );
    },
    error: function() {
      alert("Oops, look like we experienced an error!");
      $("#as-manual-form").remove();
      return false;
    }
  });
});

// Preview signature
// Have to target root element because jQuery cannot see elements created after load
$(document).on("click", "#as-manual-preview", function(e) {
  e.preventDefault();
  var template = $("#as-template-select")
    .find(":selected")
    .text();

  var form = $("#as-manual-form-inner").serializeArray();

  $.ajax({
    type: "POST",
    url: "ajax-handler.php",
    dataType: "html",
    data: {
      action: "ajax_preview_signature",
      template: template,
      form: form
    },
    success: function(data) {
      $("#as-signature-preview").empty();
      $("#as-manual-form").after(
        "<div id='as-signature-preview'>" + data + "</div>"
      );
    },
    error: function(data) {}
  });
});
