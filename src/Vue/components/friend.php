<div class="friends__content">
    <div class="friends__top">
        <p><i class='fas fa-user'></i>Amis</p>
    </div>
    <div class="friends__wrapper">
        <?php
        foreach ($friendsList as $item) { ?>
            <div class="friend">
                <div class="img__container">
                    <img src="./images/user.png" alt="">
                </div>
                <p>
                    <?= $item['friend_name'] ?>
                </p>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="friends__add">
        <a class='friends__add__button'>Ajouter un ami</a>
    </div>
</div>