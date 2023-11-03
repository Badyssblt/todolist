<div class="friends__content">
    <div class="friends__top">
        <p><i class='fas fa-user'></i>Amis</p>
        <div class="friends__top__bell">
            <p><i class='fas fa-bell'></i></p>
            <p class='bell__notif'></p>
            <div class="bell__menu">

            </div>
        </div>

    </div>
    <div class="friends__wrapper">
    </div>
    <div class="friends__add">
        <a class='friends__add__button'>Ajouter un ami</a>
        <div class="friends__form">
            <input type="text" name="friend-name" id="friend__name" placeholder="Entrer l'email...">
            <input type="submit" value="Rechercher">
        </div>
    </div>

</div>
<?php
$userID = $_SESSION['ID'];
echo '<script>';
echo "const userID = $userID";
echo '</script>';
?>