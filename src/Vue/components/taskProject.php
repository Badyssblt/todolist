<div class="todo__all">
    <div class="todo__top">
        <div class="todo__top__content">
            <p class="todo__title">Tâche du projet</p>
            <p><i class="fa-solid fa-ellipsis"></i></p>
        </div>

        <a id='addTaskButton'>Ajouter une tâche</a>
    </div>
    <div class="todo__wrapper__project">

    </div>

    <div class="addTask" style='display: none'>

    </div>

</div>
<form class='parameter' style='display: none;'>
    <a class='form__class' onclick='hideFormParameter()'>
        <i class='fa-solid fa-xmark'></i>
    </a>
    <p class="form__title">Paramètre</p>
    <div id="span__color">
        <input type="color" id="form__color">
    </div>
    <input type="hidden" name="todoID" value='' id='todoID'>
    <div class="delete">
        <a id='deleteTask' id='delete__todo'>Supprimer la tâche</a>
    </div>
    <input type="submit" value="Envoyer">
</form>

<?php
$userID = $_SESSION['ID'];
echo '<script>';
echo "const userID = $userID";
echo '</script>';
?>
<script>


</script>