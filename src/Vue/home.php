<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/calendar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./js/calendar.js"></script>
    <title>Accueil</title>

</head>

<body>
    <main>
        <?php require("./Vue/components/nav.php") ?>
        <div class="form" style="display: none">

        </div>
        <div class="todo__wrapper">
            <?php
            foreach ($lists as $item) { ?>
                <div class="todo__items">
                    <button class="btn__check uncheck"></button></button>
                    <p class="todo__name">
                        <?= $item["name"] ?>
                    </p>
                    <button class="btn__more"></button>

                </div>
                <?php
            }
            ?>
        </div>
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
    <script>
        $(document).ready(() => {
            $(document).on("submit", "#addTask", function (e) {
                e.preventDefault();
                let name = $("#name").val();
                let description = $("#description").val();
                $.ajax({
                    type: "POST",
                    url: "/postEvent",
                    data: {
                        name: name,
                        description: description,
                        data: "20/10/2023",
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (jqXHR) {
                        console.log(jqXHR);
                    },
                });
            });
        })
    </script>

    <script src="https://kit.fontawesome.com/c1cb64b22b.js" crossorigin="anonymous"></script>
</body>

</html>