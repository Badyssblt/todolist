$(document).ready(() => {
  function updateUser() {
    $.ajax({
      type: "GET",
      url: "/getUserInProject",
      data: {
        projectID: projectID,
      },
      dataType: "JSON",
      success: function (response) {
        console.log(response);
      },
    });
  }
});
