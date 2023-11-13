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
        renderFriendAccept(response);
        renderFriendWaiting(response);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  }
  updateFriend(userID);
  // Accepter amis
  $(document).on("mouseup", ".add__friend", function (event) {
    event.stopPropagation();
    let friendID = $(this).closest(".friend").data("friendid");
    $.ajax({
      type: "POST",
      url: "/acceptFriend",
      data: {
        userID: userID,
        friendID: friendID,
      },
      dataType: "JSON",
      success: function (response) {
        updateFriend(userID);
        $(".bell__menu").empty();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });

  function renderFriendAccept(data) {
    $(".friends__wrapper").empty();
    for (let i = 0; i < data["accepted"].length; i++) {
      let item = `<div class="friend" data-friendID='${
        data["accepted"][i].friend_id || data["accepted"][i].ID
      }'>
      <div class="friend__info">
        <div class="img__container">
          <img src="./images/user.png" alt="Image de l'utilisateur">
        </div>
        <div class="friend__info__name">
        <p>
          ${data["accepted"][i].friend_name || data["accepted"][i].username}
        </p>
        <p>
          ${data["accepted"][i].is_online == 1 ? "En ligne" : "Hors ligne"}
        </p>
        </div>

      </div>

  </div>`;

      $(".friends__wrapper").append(item);
    }
  }

  function renderFriendSearch(data) {
    console.log(data);
    $(".friends__wrapper").empty();
    for (let i = 0; i < data.length; i++) {
      let item = `<div class="friend" data-friendID='${
        data[i].friend_id || data[i].ID
      }'>
      <div class="friend__info">
        <div class="img__container">
          <img src="./images/user.png" alt="Image de l'utilisateur">
        </div>
        <div class="friend__info__name">
        <p>
          ${data[i].friend_name || data[i].username}
        </p>
        </div>

      </div>

  </div>`;

      $(".friends__wrapper").append(item);
      if (data[0].username) {
        let search = $(
          "<div class='friends__add__form' data-friendid=''><a id='add__friend'>Ajouter en ami</a></div>"
        );
        $(".friend").append(search);
      }
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
          renderFriendSearch(response);
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    }
  });

  // AJOUTER EN AMIS
  $(document).on("click", "#add__friend", function () {
    let friendID = $(this).closest(".friend").data("friendid");
    $.ajax({
      type: "POST",
      url: "/addFriend",
      data: {
        userID: userID,
        friendID: friendID,
      },
      success: function (response) {
        $("#add__friend").text();
        let check = $('<i class="fas fa-check"></i>');
        $("#add__friend").append(check);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });
});

function renderFriendWaiting(data) {
  $(".bell__menu").empty();
  let numberOfWaiting = data["pending"].length;
  $(".bell__notif").text(numberOfWaiting);
  if (data["pending"].length == 0) {
    const warning = $("<p class='warning__text'>Aucune demande d'ami</p>");
    $(".bell__menu").append(warning);
  }
  for (let i = 0; i < data["pending"].length; i++) {
    let item = `<div class="friend waiting" data-friendID='${
      data["pending"][i].friend_id
    }'>
    <div class="friend__info">
      <div class="img__container">
          <img src="./images/user.png" alt="Image de l'utilisateur">
      </div>
      <p>
          ${data["pending"][i].friend_name || data["pending"][i].username}
      </p>
    </div>
    <div class='add__friend'><i class="fa-solid fa-plus"></i></div>
</div>`;
    $(".bell__menu").append(item);
  }
}

// Ouvre menu attente amis
$(document).ready(() => {
  const bellMenu = $(".bell__menu");

  $(".friends__top__bell").click(function (event) {
    event.stopPropagation();
    bellMenu.toggle();
  });

  bellMenu.on("click", function (event) {
    event.stopPropagation();
  });

  $(document).on("click", function () {
    bellMenu.hide();
  });
});
