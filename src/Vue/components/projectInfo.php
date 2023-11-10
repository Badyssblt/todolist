<div class="participations">
    <div class="participations__top">
        <p class='participations__title'>Les participants de ce projet</p>
        <a id='addParticipantForm'>Ajouter un participant</a>
    </div>
    <div class="participation__wrapper">

    </div>
    <div class="participations__delete">
        <p style='font-family: Poppins; font-weight: bold; font-size: .9em'>Voulez vous supprimer ce participant du
            projet ?</p>
        <button class='participations__delete__accept'>Supprimer</button>
        <button class='participations__delete__denied'>Annuler</button>
    </div>
</div>
<div class="participations__form">
    <?php require('./Vue/components/friendProject.php'); ?>
    <div class="participations__form__confirm" data-friendID=''>
        <a>Ajouter l'ami au projet ?</a>
        <div class="participations__form__req">
            <a id='participations__accept'>Ajouter</a>
            <a id='participations__denied'>Annuler</a>
        </div>
    </div>

</div>