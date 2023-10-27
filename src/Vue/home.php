<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/taskList.css">
    <link rel="stylesheet" href="./css/calendar.css">
    <link rel="stylesheet" href="./css/warning.css">
    <link rel="stylesheet" href="./css/forms.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./js/todoList.js"></script>
    <script src="./js/calendar.js"></script>
    <title>Accueil</title>

</head>

<body>
    <main>
        <?php require("./Vue/components/nav.php") ?>
        <div class="form" style="display: none">
            <button class='form__close' onclick='hideForm()'><i class='fa-solid fa-xmark'></i></button>
        </div>
        <?php require('./Vue/components/taskList.php') ?>
        <div id="calendar">
            <div id="date-selector">
                <button id="prev-day" class="btn"><i class="fas fa-angle-left"></i></button>
                <span id="selected-date"></span>
                <button id="next-day" class="btn"><i class="fas fa-angle-right"></i></button>
            </div>
            <div id="hours">
            </div>
        </div>
    </main>
    <script src="https://kit.fontawesome.com/c1cb64b22b.js" crossorigin="anonymous"></script>
</body>

</html>