<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/verify.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Vérifier votre adresse email</title>
</head>

<body>
    <main>
        <h1 id="title">Entrer votre code à 5 chiffres.</h1>
        <form id="submit">
            <div class="input__code">
                <label for="code" id="code__label">Entrer ici votre code à 5 chiffres</label>
                <input type="text" name="code" id="code">
            </div>

        </form>
        <div class="warning__container">
            <p class="warning__text"></p>
        </div>
    </main>
</body>
<script src="./js/verify.js"></script>

</html>