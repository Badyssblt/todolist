$(document).ready(() => {
  // RECUPERATION ET AFFICHAGE DES AMIS
  function updateFriend(userID) {
    $.ajax({
      type: "POST",
      url: "/getFriends",
      data: {
        userID: userID,
      },
      dataType: "JSON",
      success: function (response) {
        renderFriend(response);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  }
  updateFriend(userID);

  function renderFriend(data) {
    $(".friends__wrapper").empty();
    for (let i = 0; i < data.length; i++) {
      let item = `<div class="friend" data-friendID='${data[i].friend_id}'>
      <div class="img__container">
          <img src="./images/user.png" alt="Image de l'utilisateur">
      </div>
      <p>
          ${data[i].friend_name || data[i].username}
      </p>
  </div>`;
      $(".friends__wrapper").append(item);
    }
  }

  // AFFICHAGE FORMULAIRE RECHERCHE D'AMIS
  let addMenuOpen = false;
  $(".friends__add__button").click(() => {
    if (addMenuOpen) {
      $(".friends__form").css("display", "none");
      addMenuOpen = false;
    } else {
      $(".friends__form").css("display", "flex");
      addMenuOpen = true;
    }
  });

  // AFFICHAGE RECHERCHE AMIS
  $("#friend__name").change(function (e) {
    e.preventDefault();
    let email = $(this).val();
    if (email == "") {
      updateFriend(userID);
    } else {
      $.ajax({
        type: "POST",
        url: "/searchFriend",
        data: {
          email: email,
        },
        dataType: "JSON",
        success: function (response) {
          console.log(response);
          renderFriend(response);
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    }
  });

  // AJOUTER EN AMIS
  $(document).on("click", "#add__friend", function () {
    let friendID = $(".friends__add__form").attr("data-friendid");
    $.ajax({
      type: "POST",
      url: "/addFriend",
      data: {
        userID: userID,
        friendID: friendID,
      },
      dataType: "JSON",
      success: function (response) {
        console.log(response);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });
  // AFFICHAGE AJOUTER AMIS
  let addFriendOpen = false;
  $(document).on("click", ".friend", function () {
    if (addFriendOpen) {
      $(".friends__add__form").css("display", "none");
      addFriendOpen = false;
    } else {
      $(".friends__add__form").css("display", "block");
      let friendID = $(this).data("friendid");
      $(".friends__add__form").attr("data-friendid", friendID);
      addFriendOpen = true;
    }
  });
});
