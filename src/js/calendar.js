$(document).ready(function () {
  // Initialiser la date
  let currentDate = new Date();

  // Création du calendrier
  updateCalendar();

  // Ajout de la ligne pour l'heure actuelle
  updateCurrentHourLine();

  // Mettre à jour la ligne toutes les minutes
  setInterval(updateCurrentHourLine, 60000);

  // Bouton "Précédent"
  $("#prev-day").on("click", function () {
    currentDate.setDate(currentDate.getDate() - 1);
    updateCalendar();
  });

  // Bouton "Suivant"
  $("#next-day").on("click", function () {
    currentDate.setDate(currentDate.getDate() + 1);
    updateCalendar();
  });

  function updateCalendar() {
    // Mettre à jour la date affichée
    $("#selected-date").text(currentDate.toDateString());

    // Vider les heures existantes
    $("#hours").empty();

    // Création du calendrier
    for (let hour = 0; hour <= 23; hour++) {
      let hours = $(
        '<div class="hour" data-hour="' +
          hour +
          '"><p class="hourItem">' +
          hour +
          ":00</p></div>"
      );
      $("#hours").append(hours);

      let hourElement = $(
        '<div class="hour" data-hour="' + hour + '">' + pad(hour) + ":00</div>"
      );

      hourElement.on("click", addEvent);
    }
    updateCurrentHourLine();
  }

  function updateCurrentHourLine() {
    const currentHour = new Date().getHours();
    const currentMinute = new Date().getMinutes();
    const topPosition = ((currentHour * 60 + currentMinute) / 60) * 78;

    $(".current-hour-line").remove();
    // Verifie si le calendrier est sur le jour actuelle
    if (currentDate.toDateString() === new Date().toDateString()) {
      $('.hour[data-hour="' + currentHour + '"]').append(
        '<div class="current-hour-line" style="top: ' +
          topPosition +
          'px; display: block"></div>'
      );
    } else {
      $('.hour[data-hour="' + currentHour + '"]').append(
        '<div class="current-hour-line" style="top: ' +
          topPosition +
          'px; display: none"></div>'
      );
    }
  }

  function pad(num) {
    return (num < 10 ? "0" : "") + num;
  }
});

function addEvent() {
  let currentDate = new Date();
  currentDate = formatDate(currentDate);
  console.log("clické");
  createsForm(currentDate);
}

function createsForm(date) {
  $(".form").empty();
  let form = $("<form id='addTask' method='POST'></form>");

  let dateAndHour = date;
  let dateAndHourDiv = $(
    "<div class='form__hour'><p>Date et jour de l'évenèment </p><p class='form__hour__content'>" +
      date +
      "</p></div>"
  );
  let dateAndHourInput = $(
    "<input type='hidden' id='dateHidden' value=" + dateAndHour + ">"
  );
  let inputs = {
    name: $(
      "<input type='text' placeholder='Entrer le nom de l évènement...' name='eventName' id='name' autocomplete='off'>"
    ),
    description: $("<textarea name='description' id='description'></textarea>"),
    submit: $("<input type='submit'>"),
  };
  let div = ".addTask";
  let close = $(
    `<a class='form__close' onclick='hideForm()'><i class='fa-solid fa-xmark'></i></a>`
  );

  $(".addTask").css("display", "block");

  form.append(close);
  form.append(inputs.name);
  form.append(dateAndHourDiv);
  form.append(dateAndHourInput);
  form.append(inputs.description);
  form.append(inputs.submit);
  $(".addTask").append(form);
}

function formatDate(date) {
  return [
    padTo2Digits(date.getDate()),
    padTo2Digits(date.getMonth() + 1),
    date.getFullYear(),
  ].join("/");
}

function padTo2Digits(num) {
  return num.toString().padStart(2, "0");
}
