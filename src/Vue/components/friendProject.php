<div class="friends__content">
    <div class="friends__top">
        <p><i class='fas fa-user'></i>Amis</p>
    </div>
    <div class="friends__wrapper">
    </div>
    <div class="friends__add">
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