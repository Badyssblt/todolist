$(document).ready(function () {
  updateProject();
  updateWaitingProject();
});

function updateProject() {
  $.ajax({
    type: "POST",
    url: "/getProject",
    dataType: "JSON",
    success: function (response) {
      renderProject(response);
    },
  });
}

function renderProject(data) {
  $(".project__wrapper").empty();
  for (let i = 0; i < data.length; i++) {
    let item = data[i];
    let projectItem = `<div class="project">
        <a href='/project/${item.project_id}'><i class='fas fa-arrow-up-right-from-square'></i></a>
        <p class='project__name'>${item.project_name}</p>
        <div class="progress__wrapper">
          <p class='progress__title'>Progression</p>
          <div class="project__progress" id='progress-bar${i}'>
            <div id="progress${i}" class='progress__content'></div>
          </div>
        </div>
      </div>`;
    $(".project__wrapper").append(projectItem);

    displayProgressBar(item.task_finish, item.task_count, i);
  }
}

function displayProgressBar(currentValue, maxValue, index) {
  let percentage = (currentValue / maxValue) * 100;
  $(`#progress${index}`).css("width", percentage + "%");
}

// Affichage formualaire création projet

$(".addTaskButton").click(function () {
  $(".project__form").toggle();
});

$("#close__form").click(function () {
  $(".project__form").hide();
});

// Créer un projet au clique de submit du formulaire
function createProject() {
  let name = $("#name").val();
  if (name.length != 0) {
    $.ajax({
      type: "POST",
      url: "/createProject",
      data: {
        name: name,
      },
      success: function (response) {
        updateProject();
        $(".project__form").hide();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  }
}

function updateWaitingProject() {
  $.ajax({
    type: "POST",
    url: "/getProjectWaitingByUser",
    data: {},
    dataType: "JSON",
    success: function (response) {
      renderWaitingProject(response);
    },
    error: function (jqXHR) {
      console.log(jqXHR);
    },
  });
}

function renderWaitingProject(data) {
  $("#project__int").text(data.length);
  if (data.length > 0) {
    $(".project__waiting__wrapper").empty();
    for (let i = 0; i < data.length; i++) {
      let item = data[i];
      let itemDiv = `
      <div class='project__waiting__item'>
        <p>${item.project_name}</p>
        <div class="project__waiting__choose">
          <span class='project__waiting__choosen' data-projectID='${item.project_id}' data-int='1'><i class='fas fa-check'></i></span>
          <span class='project__waiting__choosen' data-projectID='${item.project_id}' data-int='0'><i class='fas fa-xmark'></i></span>
        </div>
      </div>`;
      $(".project__waiting__wrapper").append(itemDiv);
    }
  } else {
    let message = $("<p>Vous n'avez aucune demande de projet</p>");
    $(".project__waiting__wrapper").append(message);
  }
}

$(".project__form").submit(function (e) {
  e.preventDefault();
  createProject();
  console.log("test");
});

$(document).on("click", ".project__int", function () {
  $(".project__waiting__container").toggle();
});

// Fonction pour gérer acceptation invitation projets
function updateInvit(projectID, choose) {
  $.ajax({
    type: "POST",
    url: "/updateProjectWaiting",
    data: {
      projectID: projectID,
      choose: choose,
    },
    dataType: "JSON",
    success: function (response) {
      $(".project__waiting__wrapper").empty();
      updateWaitingProject();
      updateProject();
    },
    error: function (jqXHR) {
      console.log(jqXHR);
    },
  });
}

$(document).on("click", ".project__waiting__choosen", function () {
  let projectID = $(this).attr("data-projectID");
  let choose = $(this).attr("data-int");
  updateInvit(projectID, choose);
});
