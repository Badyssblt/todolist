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
  // Affiche les todo après le drag
  function renderFilteredTodoList(filteredTodos) {
    const todoWrapper = $(".todo__wrapper");
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
          let todoHtml = `<div class="todo__items" data-id='${
            item.i
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

  // Gère l'affichage des todos
  function renderTodoList(data) {
    var todoWrapper = $(".todo__wrapper");
    todoWrapper.empty();
    if (data != null) {
      if (data.length > 0 && data != null) {
        data.forEach(function (item) {
          var state = item.state == "1";
          let color = item.color;
          let textColor = isColorDark(color) ? "white" : "black";
          var categoryHtml =
            item.categoryName !== null
              ? "<p>" + item.categoryName + "</p>"
              : '<p  style="font-size: .9em;" onclick="defineCategory(' +
                item.id +
                ', this)">Définir une catégorie</p>';

          var todoHtml = `
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
                                                                  ${
                                                                    item
                                                                      .description
                                                                      .length !==
                                                                    0
                                                                      ? `<button class="btn__more"><i class="fas fa-angle-down close"></i></button>`
                                                                      : ""
                                                                  }
                                                              </div>`;

          todoWrapper.append(todoHtml);
        });
      } else {
        let message = $(
          "<p class='warning__text'>Vous n'avez pas encore de tâche, commencez par en créer une.</p>"
        );
        todoWrapper.append(message);
      }
    }
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
      url: "/deleteTodo",
      data: { id: todoID },
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

function isColorDark(hexColor) {
  const r = parseInt(hexColor.slice(1, 3), 16);
  const g = parseInt(hexColor.slice(3, 5), 16);
  const b = parseInt(hexColor.slice(5, 7), 16);
  const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
  return luminance < 0.5;
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
