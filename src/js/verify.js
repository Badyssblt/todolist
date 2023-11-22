$(document).ready(function () {
  $("#submit").submit(function (e) {
    e.preventDefault();
    let code = $("#code").val();
    if (!/^\d+$/.test(code)) {
      $(".warning__text").text("Veuillez entrer des chiffres");
    } else if ($("#code").val().length != 5) {
      $(".warning__text").text("Veuillez entrer un code à 5 chiffres");
    } else {
      const urlParams = new URLSearchParams(window.location.search);
      const token = urlParams.get("token");
      $.ajax({
        type: "POST",
        url: "/verifyCode",
        data: {
          code: code,
          token: token,
        },
        dataType: "JSON",
        success: function (response) {
          window.location.href = "/login";
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    }
  });
});
