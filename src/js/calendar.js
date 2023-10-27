// $(document).ready(function () {
//   var currentDate = new Date();

//   function getCurrentHour() {
//     return new Date().getHours();
//   }

//   function padTo2Digits(num) {
//     return num.toString().padStart(2, "0");
//   }

//   function formatDate(date) {
//     return [
//       padTo2Digits(date.getDate()),
//       padTo2Digits(date.getMonth() + 1),
//       date.getFullYear(),
//     ].join("/");
//   }
//   function updateCalendar() {
//     $("#selected-date").text(formatDate(currentDate));

//     $("#hours").empty();

//     for (var hour = 8; hour <= 20; hour++) {
//       var hourElement = $(
//         "<div class='hour'><p class='hourItem'>" + hour + ":00</p></div>"
//       );

//       var lineElement = $("#current-hour-line");

//       const hourHeight = 71;

//       var currentHour = getCurrentHour();
//       var lineHeight = currentHour * hourHeight;
//       var totalHeight = 5;
//       var topPosition = totalHeight - lineHeight;
//       lineElement.css("top", topPosition + "px");

//       // Déplace la ligne vers le bas en fonction de l'heure actuelle
//       let event = $(
//         "<div class='events'><i class='fas fa-plus'></i></div>"
//       ).click(function () {
//         addEvent.call(this, hour);
//       });
//       hourElement.append(event);
//       $("#hours").append(hourElement);
//     }
//   }

//   function formatDate(date) {
//     var options = {
//       day: "2-digit",
//       month: "2-digit",
//       year: "2-digit",
//     };
//     return date.toLocaleDateString("fr-FR", options);
//   }

//   updateCalendar();

//   $("#prev-day").on("click", function () {
//     currentDate.setDate(currentDate.getDate() - 1);
//     updateCalendar();
//   });

//   $("#next-day").on("click", function () {
//     currentDate.setDate(currentDate.getDate() + 1);
//     updateCalendar();
//   });

//   function createForm(date, hour) {
//     let form = $("<form id='addTask' method='POST'></form>");
//     let dateAndHour = date + "," + hour;
//     let dateAndHourDiv = $(
//       "<div class='form__hour'><p>Date et jour de l'évenèment </p><p class='form__hour__content'>" +
//         date +
//         " " +
//         hour +
//         "</p></div>"
//     );
//     let dateAndHourInput = $(
//       "<input type='hidden' id='dateHidden' value=" + dateAndHour + ">"
//     );
//     let inputs = {
//       name: $(
//         "<input type='text' placeholder='Entrer le nom de l évènement...' name='eventName' id='name'>"
//       ),
//       description: $(
//         "<textarea name='description' id='description'></textarea>"
//       ),
//       submit: $("<input type='submit'>"),
//     };
//     $(".form").css("display", "block");
//     $(".form").append(form);
//     form.append(inputs.name);
//     form.append(dateAndHourDiv);
//     form.append(dateAndHourInput);
//     form.append(inputs.description);
//     form.append(inputs.submit);
//   }

//   function addEvent() {
//     let hour = $(this).closest(".hour").find(".hourItem").text();
//     currentDate = formatDate(currentDate);
//     createForm(currentDate, hour);
//   }
// });
$(document).ready(function () {
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
        $("#hours").append(
          '<div class="hour" data-hour="' +
            hour +
            '">' +
            pad(hour) +
            ":00</div>"
        );
        let hourElement = $(
          '<div class="hour" data-hour="' +
            hour +
            '">' +
            pad(hour) +
            ":00</div>"
        );
      }

      // Ajout de la ligne pour l'heure actuelle
      updateCurrentHourLine();
    }

    function updateCurrentHourLine() {
      const currentHour = new Date().getHours();
      const currentMinute = new Date().getMinutes();
      const topPosition = ((currentHour * 60 + currentMinute) / 60) * 45; // 50px par heure
      $(".current-hour-line").remove();

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
});
