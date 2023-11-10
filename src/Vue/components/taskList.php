<div class="todo__all">
    <div class="todo__top">
        <div class="todo__top__container">
            <p class="todo__title">Mes taches</p>
            <div class="todo__filter">
                <div class="filter__icon">
                    <i class="fas fa-search"></i>
                </div>
                <form id='search'>
                    <input type="text" name="name" id="todoName" placeholder="Nom de l'évenèment">
                    <select name="category" id="category">

                    </select>
                </form>
            </div>
        </div>
        <a id='addTaskButton'>Ajouter une tâche</a>
    </div>
    <div class="todo__wrapper">

    </div>

    <div class="addTask" style='display: none'>

    </div>

</div>
<form class='parameter' style='display: none;'>
    <a class='form__close' onclick='hideFormParameter()'>
        <i class='fa-solid fa-xmark'></i>
    </a>
    <p class="form__title">Paramètre</p>
    <input type="hidden" name="todoID" value='' id='todoID'>
    <div class="delete">
        <a id='deleteTask' id='delete__todo'>Supprimer la tâche</a>
    </div>
</form>