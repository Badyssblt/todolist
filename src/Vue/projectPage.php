<?php
// Vérifie si l'utilisateur n'est pas connecter
if (!isset($_SESSION['ID'])) {
    header('Location: /login');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/js/todolistProject.js"></script>
    <script src="/js/calendar.js"></script>
    <script src="/js/projectInfo.js"></script>
    <script src="/js/friendProject.js"></script>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/warning.css">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/friend.css">
    <link rel="stylesheet" href="/css/taskList.css">
    <link rel="stylesheet" href="/css/project.css">
    <link rel="stylesheet" href="/css/friend.css">
    <link rel="stylesheet" href="/css/forms.css">
    <link rel="stylesheet" href="/css/participations.css">
    <title>{Project Name}</title>
</head>

<body>
    <main>
        <?php require('./Vue/components/nav.php') ?>
        <?php require('./Vue/components/taskProject.php') ?>
        <?php require('./Vue/components/projectInfo.php') ?>
    </main>
</body>
<script src="https://kit.fontawesome.com/c1cb64b22b.js" crossorigin="anonymous"></script>

</html>