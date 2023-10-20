<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/register.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>
    <form action="/register" method="POST">
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
            <input type="text" id="email" name="password" placeholder="Entre votre mot de passe..." class="input-field">
        </div>
        <input type="submit" value="S'inscrire" class="submit">
    </form>
</body>
<script src="https://kit.fontawesome.com/c1cb64b22b.js" crossorigin="anonymous"></script>


</html>