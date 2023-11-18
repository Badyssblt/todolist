$(document).ready(() => {
  let isOpen;
  $(".filter__icon").click(function () {
    if (!isOpen) {
      $("#search").css("display", "block");
      $(".todo__filter").addClass("active");
      isOpen = true;
    } else {
      $("#search").css("display", "none");
      $(".todo__filter").removeClass("active");
      isOpen = false;
    }
  });

  $(".todo__wrapper__project").sortable({
    axis: "y",
    update: function (event, ui) {
      var updatedOrder = $(this).sortable("toArray", {
        attribute: "data-order",
      });
      let todoID = $(this).attr("data-id");
      updateTodoOrder(todoID, updatedOrder);
    },
  });

  $.ajax({
    type: "GET",
    url: "/getCategory",
    dataType: "JSON",
    success: function (response) {
      for (let i = 0; i < response.length; i++) {
        $("#category").append(
          $(`<option value='${response[i].id}'>${response[i].name}</option>`)
        );
      }
    },
  });
  $(document).on("submit", "#search", function (e) {
    e.preventDefault();
  });
  $(document).on("change", "#search", function () {
    let category = $("#category").find(":selected").val();
    let name = $("#todoName").val();
    $.ajax({
      type: "POST",
      url: "/getTodo",
      data: {
        category: category,
        name: name,
      },
      dataType: "JSON",
      success: function (response) {
        renderFilteredTodoList(response);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });

  $("#addTaskButton").click(() => {
    let date = new Date();
    addEvent(date);
  });

  function renderFilteredTodoList(filteredTodos) {
    const todoWrapper = $(".todo__wrapper__project");
    todoWrapper.empty();

    if (filteredTodos != null) {
      if (filteredTodos.length > 0) {
        filteredTodos.forEach(function (item) {
          var state = item.state === "1";
          let color = item.color;
          var categoryHtml =
            item.categoryName !== null
              ? "<p>" + item.categoryName + "</p>"
              : '<p onclick="defineCategory(' +
                item.id +
                ', this)">Définir une catégorie</p>';
          let textColor = isColorDark(color) ? "white" : "black";
          let todoHtml = `
                                                          <div class="todo__items" data-id='${
                                                            item.id
                                                          }' data-order='${
            item.orderTodo
          }' style='background: ${color}; color: ${textColor}'>
                                                              <button class="btn__check ${
                                                                state
                                                                  ? "check"
                                                                  : "uncheck"
                                                              }" data-id='${
            item.id
          }'></button>
                                                              <p class="todo__name">${
                                                                item.name
                                                              }</p>
                                                              <div class="todo__category">${categoryHtml}</div>
                                                              <div class="todo__description">
                                                                  <p class="todo__description todo__description__title hidden">Description : </p>
                                                                  <p class="todo__description hidden">${
                                                                    item.description
                                                                  }</p>
                                                              </div>
                                                              <div class='todo__parameter'><i class='fas fa-gear'></i></div>
                                                              <button class="btn__more">
                                                                  <i class="fas fa-angle-down close"></i>
                                                              </button>
                                                          </div>`;
          todoWrapper.append(todoHtml);
        });
      } else {
        todoWrapper.append(
          '<p class="warning__text">Aucune tâche trouvée pour les critères spécifiés.</p>'
        );
      }
    } else {
      todoWrapper.append(
        '<p class="warning__text">Une erreur s\'est produite lors de la récupération des tâches.</p>'
      );
    }
  }

  function updateTodoOrder(todoID, newPosition) {
    var updatedOrderData = {};
    $(".todo__wrapper__project .todo__items").each(function (index) {
      var taskId = $(this).data("id");
      updatedOrderData[taskId] = index;
    });
    $.ajax({
      type: "POST",
      url: "/updateTodoOrderProject",
      data: {
        orderData: updatedOrderData,
      },
      success: function (response) {
        updateTodoList();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  }

  // Mets à jour les todos
  function updateTodoList() {
    var url = window.location.href;
    let id = url.substring(url.lastIndexOf("/") + 1);
    $.ajax({
      type: "POST",
      url: "/homeProject",
      data: {
        id: id,
      },
      dataType: "JSON",
      success: function (response) {
        renderTodoList(response);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
  updateTodoList();

  $(document).ready(() => {
    $(document).on("submit", ".addCategory", function (e) {
      e.preventDefault();
      let categoryID = $("#category").val();
      let todoID = $("#todoID").val();
      $.ajax({
        type: "POST",
        url: "/addCategoryProject",
        data: {
          categoryID: categoryID,
          todoID: todoID,
        },
        success: function (response) {
          updateTodoList();
          console.log(response);
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    });
    $(document).on("submit", "#addTask", function (e) {
      e.preventDefault();
      let name = $("#name").val();
      let description = $("#description").val();
      var url = window.location.href;
      let projectID = url.substring(url.lastIndexOf("/") + 1);
      if (name.length < 3) {
        if ($(".warning__form").length === 0) {
          const warningText = $(
            "<p class='warning__form'>Veuillez entrer un nom de tâche d'au moins 3 caractères</p>"
          );
          $(".form").append(warningText);
        }
      } else {
        $.ajax({
          type: "POST",
          url: "/postEventProject",
          data: {
            name: name,
            description: description,
            projectID: projectID,
            owner: userID,
          },
          success: function (response) {
            updateTodoList();
            hideForm();
          },
          error: function (jqXHR) {
            console.log(jqXHR);
          },
        });
      }
    });

    $(document).on("submit", ".parameter", function (e) {
      e.preventDefault();
      let todoID = $("#todoID").val();
      let color = $("#form__color").val();
      $.ajax({
        type: "POST",
        url: "/editTodo",
        data: {
          todoID: todoID,
          color: color,
        },
        success: function (response) {
          updateTodoList();
          console.log(response);
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    });

    $(document).on("click", ".uncheck", function () {
      let id = $(this).attr("data-id");
      let btn = $(this);
      $.ajax({
        type: "POST",
        url: "/checkEventProject",
        data: {
          id: id,
        },
        success: function (response) {
          btn.removeClass("uncheck");
          btn.addClass("check");
        },
        error: function (jqXHR) {
          console.log(jqXHR);
          console.log("error");
        },
      });
    });
    $(document).on("click", ".check", function () {
      let id = $(this).attr("data-id");
      let btn = $(this);
      $.ajax({
        type: "POST",
        url: "/uncheckEventProject",
        data: {
          id: id,
        },
        success: function (response) {
          btn.removeClass("check");
          btn.addClass("uncheck");
        },
        error: function (jqXHR) {
          console.log(jqXHR);
          console.log("error");
        },
      });
    });
    $(document).on("click", ".todo__parameter", function () {
      $(".parameter").css("display", "grid");
      let todoID = $(this).closest(".todo__items").data("id");
      $("#todoID").val(todoID);
    });

    $("#deleteTask").click(function () {
      let todoID = $("#todoID").val();
      $.ajax({
        type: "POST",
        url: "/deleteTodo",
        data: { id: todoID },
        success: function (response) {
          updateTodoList();
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    });
  });
});

// RENDER LES TODOS AVEC LA REPONSE EN PARAMETRE
function renderTodoList(data) {
  var todoWrapper = $(".todo__wrapper__project");
  todoWrapper.empty();
  if (data.type !== "noTodo" && data.type !== "NotAllowed") {
    if (data != null) {
      for (let i = 0; i < data.length; i++) {
        let item = data[i];
        var state = item.task_state == "1";
        var categoryHtml =
          item.task_category !== null
            ? "<p>" + item.task_category + "</p>"
            : '<p onclick="defineCategory(' +
              item.task_id +
              ', this)">Définir une catégorie</p>';
        var todoHtml = `
                                                          <div class="todo__items" data-id='${
                                                            item.task_id
                                                          }' data-order='${
          item.task_order
        }'>
                                                              <button class="btn__check ${
                                                                state
                                                                  ? "check"
                                                                  : "uncheck"
                                                              }" data-id='${
          item.task_id
        }'></button>
                                                              <div class='todo__name__content'>
                                                                  <p class="todo__name">${
                                                                    item.task_name
                                                                  }</p>
                                                                  <p style='font-size: .9em'>Par ${
                                                                    item.owner_username
                                                                  }</p>
                                                              </div>
                                                              
                                                              <div class="todo__category">${categoryHtml}</div>
                                                              <div class="todo__description">
                                                                  <p class="todo__description todo__description__title hidden">Description : </p>
                                                                  <p class="todo__description hidden">${
                                                                    item.task_description
                                                                  }</p>
                                                              </div>
                                                              <div class='todo__parameter'><i class='fas fa-gear'></i></div>
                                                              ${
                                                                item
                                                                  .task_description
                                                                  .length !== 0
                                                                  ? `<button class="btn__more"><i class="fas fa-angle-down close"></i></button>`
                                                                  : ""
                                                              }
                                                          </div>`;
        todoWrapper.append(todoHtml);
      }
    } else {
      todoWrapper.append(
        '<p class="warning__text">Vous n\'avez pas encore de tâche, commencez par en créer une.</p>'
      );
    }
  } else if (data.type == "noTodo") {
    let message = $(
      "<p class='warning__text'>Il n'y a pas de tâche pour le moment</p>"
    );
    todoWrapper.append(message);
  } else if (data.type == "NotAllowed") {
    window.location.href = "/project";
    console.log("pas autorisé");
  }
}
$(document).ready(() => {
  // jQuery UI qui gère le drag des todo
  $(".todo__wrapper").sortable({
    axis: "y",
    update: function (event, ui) {
      var updatedOrder = $(this).sortable("toArray", {
        attribute: "data-order",
      });
      let todoID = $(this).attr("data-id");
      updateTodoOrder(todoID, updatedOrder);
    },
  });

  function updateTodoOrder(todoID, newPosition) {
    var updatedOrderData = {};
    $(".todo__wrapper .todo__items").each(function (index) {
      var taskId = $(this).data("id");
      updatedOrderData[taskId] = index;
    });
    $.ajax({
      type: "POST",
      url: "/updateTodoOrder",
      data: {
        orderData: updatedOrderData,
      },
      success: function (response) {
        updateTodoList();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  }

  $.ajax({
    type: "GET",
    url: "/getCategory",
    dataType: "JSON",
    success: function (response) {
      for (let i = 0; i < response.length; i++) {
        $("#category").append(
          $(`<option value='${response[i].id}'>${response[i].name}</option>`)
        );
      }
    },
  });

  // Déclenche la création d'une todo
  $("#addTaskButton").click(() => {
    let date = new Date();
    addEvent(date);
  });

  // Ajoute todo à la base de donnée
  $(document).on("submit", "#addTask", function (e) {
    e.preventDefault();
    let name = $("#name").val();
    let description = $("#description").val();
    let date = $("#dateHidden").val();
    if (name.length < 3) {
      if ($(".warning__form").length === 0) {
        const warningText = $(
          "<p class='warning__form'>Veuillez entrer un nom de tâche d'au moins 3 caractères</p>"
        );
        $(".form").append(warningText);
      }
    } else {
      $.ajax({
        type: "POST",
        url: "/postEvent",
        data: {
          name: name,
          description: description,
          data: date,
        },
        success: function (response) {
          updateTodoList();
          hideForm();
        },
        error: function (jqXHR) {
          console.log(jqXHR);
        },
      });
    }
  });

  // Modifie la todo sélectionnée
  $(document).on("submit", ".parameter", function (e) {
    e.preventDefault();
    let todoID = $("#todoID").val();
    let color = $("#form__color").val();
    $.ajax({
      type: "POST",
      url: "/editTodo",
      data: {
        todoID: todoID,
        color: color,
      },
      success: function (response) {
        updateTodoList();
        console.log(response);
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });

  // Ajoute la catégorie à la todo
  $(document).on("submit", ".addCategory", function (e) {
    e.preventDefault();
    let categoryID = $("#category").val();
    let todoID = $("#todoID").val();
    $.ajax({
      type: "POST",
      url: "/addCategoryP",
      data: {
        categoryID: categoryID,
        todoID: todoID,
      },
      success: function (response) {
        updateTodoList();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });

  // GERE LE CHECK ET UNCHECK DES TODO
  $(document).on("change", "#form__color", function () {
    $("#span__color").css("background-color", $(this).val());
  });

  // Gère le state d'une todo dans la base de donnée
  $(document).on("click", ".uncheck", function () {
    let id = $(this).attr("data-id");
    let btn = $(this);
    $.ajax({
      type: "POST",
      url: "/checkEvent",
      data: {
        id: id,
      },
      success: function (response) {
        btn.removeClass("uncheck");
        btn.addClass("check");
      },
      error: function (jqXHR) {
        console.log(jqXHR);
        console.log("error");
      },
    });
  });
  $(document).on("click", ".check", function () {
    let id = $(this).attr("data-id");
    let btn = $(this);
    $.ajax({
      type: "POST",
      url: "/uncheckEvent",
      data: {
        id: id,
      },
      success: function (response) {
        btn.removeClass("check");
        btn.addClass("uncheck");
      },
      error: function (jqXHR) {
        console.log(jqXHR);
        console.log("error");
      },
    });
  });

  // Affichage du formulaire des paramètres
  $(document).on("click", ".todo__parameter", function () {
    $(".parameter").css("display", "grid");
    let todoID = $(this).closest(".todo__items").data("id");
    $("#todoID").val(todoID);
  });

  // Gère la suppression d'une tâche
  $("#deleteTask").click(function () {
    let todoID = $("#todoID").val();
    $.ajax({
      type: "POST",
      url: "/deleteTodoInProject",
      data: { todoID: todoID },
      success: function (response) {
        updateTodoList();
        $(".parameter").hide();
      },
      error: function (jqXHR) {
        console.log(jqXHR);
      },
    });
  });

  $(document).on("click", ".btn__more", function () {
    if ($(this).find("i").hasClass("close")) {
      $(this).find("i").addClass("open");
      $(this).find("i").removeClass("close");
      let description = $(this)
        .closest(".todo__items")
        .find(".todo__description");
      description.removeClass("hidden");
    } else {
      $(this).find("i").addClass("close");
      $(this).find("i").removeClass("open");
      let description = $(this)
        .closest(".todo__items")
        .find(".todo__description");
      description.addClass("hidden");
    }
  });
});

function createForm(data, classes) {
  const form = $("<form method='POST'>");
  const submit = $('<input type="submit" value="Envoyer">');
  form.addClass(classes);

  for (let i = 0; i < data.length; i++) {
    let input = data[i];
    let field;

    // DETERMINE LES INPUTS EN PARAMETRES ET LES CREER
    switch (input.type) {
      case "text":
        field = $("<input>")
          .attr("type", "text")
          .attr("name", input.name)
          .attr("placeholder", input.placeholder)
          .attr("id", "name");
        break;
      case "textarea":
        field = $("<textarea>").attr("name", input.name);
        break;
      case "hidden":
        field = $("<input>")
          .attr("type", "hidden")
          .attr("value", input.value)
          .attr("id", "todoID");
        break;
      case "span":
        field = $("<span>").attr("id", "span__color");
        let color = $("<input>")
          .attr("id", "form__color")
          .attr("type", "color");
        field.append(color);
        break;
      case "select":
        field = $("<select>").attr("name", input.name).attr("id", "category");
        // CREER LES OPTIONS DU SELECT
        for (let j = 0; j < input.options.length; j++) {
          let options = input.options[j];

          field.append(
            $("<option>").attr("value", options.value).text(options.label)
          );
        }
        break;
      default:
        console.error("Type de champ non pris en charge : " + input.type);
        continue;
    }

    form.append(field);
  }

  form.append(submit);
  return form;
}

// PERMET A L'UTILISATEUR DE CREER DES CATEGORIES
function defineCategory(todoID, clickedElement) {
  const formInputs = [
    {
      type: "select",
      name: "category",
      options: [],
    },
    {
      type: "hidden",
      name: "todoID",
      value: todoID,
    },
  ];
  $.ajax({
    type: "GET",
    url: "/getCategory",
    dataType: "JSON",
    success: function (response) {
      const adaptedResponse = response.map((category) => ({
        value: category.id,
        label: category.name,
      }));

      formInputs.find((input) => input.name === "category").options =
        adaptedResponse;

      $(".addCategory.form").remove();
      const form = createForm(formInputs, "addCategory");

      $(clickedElement).after(form);
    },
    error: function (jqXHR) {
      console.log(jqXHR);
    },
  });
}

function hideForm() {
  $(".addTask").css("display", "none");
}

function hideFormParameter() {
  $(".parameter").css("display", "none");
}

// Ecouteur de clique
function gestionClicMasquage(elementProtected, divHidden) {
  $(document).on("click", function (event) {
    if (!$(event.target).closest(elementProtected, divHidden).length) {
      $(divHidden).hide();
    }
  });

  $(elementProtected).on("click", function (event) {
    event.stopPropagation();
  });
}
gestionClicMasquage(".todo__parameter", ".parameter");
