<aside>
    <menu class="menu">
        <li class="items"><i class="fas fa-calendar"></i>Aujourd'hui</li>
        <li class="items">Catégorie</li>
        <li class="items">Projets</li>
        <?php
        if (isset($_SESSION['ID'])) { ?>
            <li class="items">Mon compte</li>
            <?php
        } else { ?>
            <li class="items"><a href="/login">Se connecter</a></li>
        <?php }
        ?>

    </menu>
</aside>