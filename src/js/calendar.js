$(document).ready(function () {
  var currentDate = new Date();

  function padTo2Digits(num) {
    return num.toString().padStart(2, "0");
  }

  function formatDate(date) {
    return [
      padTo2Digits(date.getDate()),
      padTo2Digits(date.getMonth() + 1),
      date.getFullYear(),
    ].join("/");
  }
  function updateCalendar() {
    $("#selected-date").text(formatDate(currentDate));

    $("#hours").empty();

    for (var hour = 8; hour <= 20; hour++) {
      var hourElement = $(
        "<div class='hour'><p class='hourItem'>" + hour + ":00</p></div>"
      );
      let event = $(
        "<div class='events'><i class='fas fa-plus'></i></div>"
      ).click(function () {
        addEvent.call(this, hour);
      });
      hourElement.append(event);
      $("#hours").append(hourElement);
    }
  }

  function formatDate(date) {
    var options = {
      day: "2-digit",
      month: "2-digit",
      year: "2-digit",
    };
    return date.toLocaleDateString("fr-FR", options);
  }

  updateCalendar();

  $("#prev-day").on("click", function () {
    currentDate.setDate(currentDate.getDate() - 1);
    updateCalendar();
  });

  $("#next-day").on("click", function () {
    currentDate.setDate(currentDate.getDate() + 1);
    updateCalendar();
  });

  function createForm(date, hour) {
    let form = $("<form id='addTask' method='POST'></form>");
    let dateAndHour = date + "," + hour;
    let dateAndHourDiv = $(
      "<div class='form__hour'><p>Date et jour de l'évenèment </p><p class='form__hour__content'>" +
        date +
        " " +
        hour +
        "</p></div>"
    );
    let dateAndHourInput = $(
      "<input type='hidden' id='dateHidden' value=" + dateAndHour + ">"
    );
    let inputs = {
      name: $(
        "<input type='text' placeholder='Entrer le nom de l évènement...' name='eventName' id='name'>"
      ),
      description: $(
        "<textarea name='description' id='description'></textarea>"
      ),
      submit: $("<input type='submit'>"),
    };
    $(".form").css("display", "block");
    $(".form").append(form);
    form.append(inputs.name);
    form.append(dateAndHourDiv);
    form.append(dateAndHourInput);
    form.append(inputs.description);
    form.append(inputs.submit);
  }

  function addEvent() {
    let hour = $(this).closest(".hour").find(".hourItem").text();
    currentDate = formatDate(currentDate);
    createForm(currentDate, hour);
  }
});
