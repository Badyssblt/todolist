$(document).ready(function () {
  updateProject();
});

function updateProject() {
  $.ajax({
    type: "POST",
    url: "/getProject",
    dataType: "JSON",
    success: function (response) {
      renderProject(response);
      console.log(response);
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
        console.log(response);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  }
}

$(".project__form").submit(function (e) {
  e.preventDefault();
  createProject();
  console.log("test");
});
