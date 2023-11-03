<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/taskList.css">
    <link rel="stylesheet" href="./css/warning.css">
    <link rel="stylesheet" href="./css/forms.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./js/todoList.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégorie</title>
</head>

<body>
    <main>
        <?php require("./Vue/components/nav.php") ?>
        <div class="todo__all">
            <p class="todo__title">Filtrer vos tâches</p>
            <div class="todo__wrapper">
                <form id='filterByCategory'>
                    <select name="category" id="category"></select>
                </form>
            </div>
        </div>
    </main>
</body>
<script>
    $(document).ready(() => {
        $.ajax({
            type: "GET",
            url: "/getCategory",
            dataType: "json",
            success: function (response) {
                const adaptedResponse = response.map((category) => ({
                    value: category.id,
                    label: category.name,
                }));
                for (let i = 0; i < adaptedResponse.length; i++) {
                    let options = adaptedResponse[i];
                    $("#category").append($("<option>").attr("value", options.value).text(options.label));
                }
            }
        });
        $.ajax({
            type: "GET",
            url: "/getTodoByCategory",
            data: {
                categoryID: categoryID
            },
            dataType: "JSON",
            success: function (response) {
                console.log(response);
            }
        });
    })
</script>

</html>