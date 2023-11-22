$(document).ready(() => {
  let url = window.location.href;
  let projectID = url.substring(url.lastIndexOf("/") + 1);
  $.ajax({
    type: "POST",
    url: "/getProjectByID",
    data: {
      ID: projectID,
    },
    dataType: "JSON",
    success: function (response) {
      setTitleOfDocument(response);
    },
  });
  function setTitleOfDocument(name) {
    document.title = name;
  }
  function updateUser() {
    $.ajax({
      type: "POST",
      url: "/getUserInProject",
      data: {
        projectID: projectID,
      },
      dataType: "JSON",
      success: function (response) {
        renderUser(response);
      },
      error: function (jqXHR) {},
    });
  }
  updateUser();

  function renderUser(data) {
    console.log(data);
    for (let i = 0; i < data.length; i++) {
      let item = data[i];
      let userName = item.ID == userID ? "Vous" : item.username;
      let divItem = `<div class="participations__item" data-collabID='${item.ID}'>
      <div class="participations__info">
        <div class="img__container">
          <img src="/images/user.png" alt="Image de l'utilisateur">
        </div>
        <div class="participations__name">
        <p>
          ${userName}
        </p>
        </div>
      </div>
      <p class='project__more'><i class="fas fa-ellipsis-v" style="color: #ffffff;"></i></p>
      <div class="participations__delete" data-collabid='${item.ID}'>
        <p style='font-family: Poppins; font-weight: bold; font-size: .9em'>Voulez vous supprimer ce participant du
            projet ?</p>
        <button class='participations__delete__accept'>Supprimer</button>
        <button class='participations__delete__denied'>Annuler</button>
    </div>
  </div>`;
      $(".participation__wrapper").append(divItem);
    }
  }

  // Gère l'ajout d'un participant
  $(document).on("click", "#addParticipantForm", function () {
    $(".participations__form").toggle();
  });
  $(document).on("click", "#addParticipant", function () {
    $(".participations__form__confirm").toggle();
    let friendID = $(this).closest(".friend").attr("data-friendID");
    $(".participations__form__confirm").attr("data-friendID", friendID);
  });
  $(document).on("click", "#participations__denied", function () {
    $(".participations__form__confirm").hide();
  });
  $(document).on("click", "#participations__accept", function () {
    let friendID = $(this)
      .closest(".participations__form__confirm")
      .attr("data-friendID");
    $.ajax({
      type: "POST",
      url: "/addUserInProject",
      data: {
        friendID: friendID,
        projectID: projectID,
      },
      dataType: "JSON",
      success: function (response) {
        $(".participations__form__confirm").hide();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });
});
$(document).on("click", ".project__more", function () {
  var parentItem = $(this).closest(".participations__item");

  parentItem.find(".participations__delete").toggle();
});
