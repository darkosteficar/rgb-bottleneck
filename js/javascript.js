(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName("needs-validation");
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add("was-validated");
          },
          false
        );
      });
    },
    false
  );
})();

$(document).ready(function () {
  $("#comment_form").on("submit", function (event) {
    event.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
      url: "add_comment.php",
      method: "POST",
      data: form_data,
      dataType: "JSON",
      success: function (data) {
        if (data.error != "") {
          $("#comment_form")[0].reset();
          $("#comment_message").html(data.error);
        }
      },
    });
  });
});
