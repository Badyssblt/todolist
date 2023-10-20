<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/register.css">
    <title>Se connecter</title>
</head>

<body>
    <form action="/login" method="POST">
        <h1 class="title">Se connecter.</h1>
        <div class="input name">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Entrer votre email" name="email" id="email" class="input-field"
                autocomplete="off">
        </div>
        <div class="input password">
            <i class="fas fa-key"></i>
            <input type="text" id="email" name="password" placeholder="Entre votre mot de passe..." class="input-field">
        </div>
        <input type="submit" value="Se connecter" class="submit">
    </form>
</body>

</html>