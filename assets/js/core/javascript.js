function validateAddPost() {
  var title = document.forms["add_post"]["post_title"].value;
  var content = document.forms["add_post"]["post_content"].value;

  if (title == "") {
    alert("Naslov objave je obavezno!");

    return false;
  }
  if (content == "") {
    alert("Sadržaj objave je obavezan!");
    return false;
  }
}

$(document).ready(function () {
  $("#imageInputForm").ajaxForm(function () {
    alert("Uploaded!");
  });
});
function showImageHereFunc() {
  $("#showImageHere").empty();
  var total_file = document.getElementById("uploadImageFile").files.length;
  var last = total_file - 1;

  $("#showImageHere").append(
    "<img src='" +
      URL.createObjectURL(event.target.files[last]) +
      "' height='300px' width=' 500px' style='border-style: solid;  '>"
  );
}
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

// Show image preview

function showImageHereFuncAddPost() {
  var total_file = document.getElementById("uploadImageFileAddPost").files
    .length;
  for (var i = 0; i < total_file; i++) {
    $("#showImageHereAddPost").append(
      "<div class='card' style='width: 20rem;'>" +
        "<img src='" +
        URL.createObjectURL(event.target.files[i]) +
        "' class='card-img-top'> <div class='card-body'>" +
        "<h4 class='card-title'>Card title</h4>" +
        "<p class='card-text'>Some quick example text to build on the card title and make up the bulk of the card's content.</p>" +
        "<a href='#' class='btn btn-primary'>Go somewhere</a>" +
        "</div>" +
        "</div>"
    );
  }
}
/*
 */
