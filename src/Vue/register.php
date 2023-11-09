<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/register.css">
    <link rel="stylesheet" href="./css/warning.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>
    <form action="/register" method="POST" id='register'>
        <h1 class="title">Inscription.</h1>
        <div class="input name">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Entrer votre nom." name="username" id="name" class="input-field"
                autocomplete="off">
        </div>
        <div class="input email">
            <i class="fas fa-envelope"></i>
            <input type="text" name="email" id="email" placeholder="Entre votre email." class="input-field"
                autocomplete="off">
        </div>
        <div class="input password">
            <i class="fas fa-key"></i>
            <input type="text" id="password" name="password" placeholder="Entre votre mot de passe..."
                class="input-field">
        </div>
        <input type="submit" value="S'inscrire" class="submit">
    </form>
    <div class="errors__content">

    </div>

</body>
<script src="https://kit.fontawesome.com/c1cb64b22b.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(() => {

        $("#register").submit(function (e) {
            let username = $("#name").val();
            let email = $("#email").val();
            let password = $("#password").val();
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/register",
                data: {
                    username: username,
                    email: email,
                    password: password
                },
                dataType: "JSON",
                success: function (response) {
                    renderResponse(response);
                }, error: function (jqXHR) {
                    console.log(jqXHR);
                }
            });
        });

        function renderResponse(data) {
            if (data.type === "cancel") {
                let content = $(".errors__content");
                content.empty();
                let errors = data.message;
                let errorsDiv = $(`<p class='warning__text'>${errors}</p>`);
                content.append(errorsDiv);
            } else if (data.type === "ok") {
                window.location.href = '/login';
            }
        }
    })
</script>

</html>