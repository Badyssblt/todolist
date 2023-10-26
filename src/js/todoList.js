$(document).ready(() => {
  // GERE LE CHECK ET UNCHECK DES TODO

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
    let close = $(
      "<button class='form__close' onclick='hideForm()'><i class='fa-solid fa-xmark'></i></button>"
    );

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
  $(".form").css("display", "none");
}
