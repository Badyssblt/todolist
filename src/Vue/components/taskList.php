<div class="todo__wrapper">
    <p class="todo__title">Taches</p>
</div>
<script>
    $(document).ready(() => {
        function updateTodoList() {
            $.ajax({
                type: "GET",
                url: "/",
                success: function (response) {
                    renderTodoList(response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        updateTodoList();
        $(document).ready(() => {
            $(document).on("submit", ".addCategory", function (e) {
                e.preventDefault();
                let categoryID = $("#category").val();
                let todoID = $("#todoID").val();
                $.ajax({
                    type: "POST",
                    url: "/addCategory",
                    data: {
                        categoryID: categoryID,
                        todoID: todoID
                    },
                    success: function (response) {
                        updateTodoList();
                    }, error: function (jqXHR) {
                        console.log(jqXHR);
                    }
                });
            })
            $(document).on("submit", "#addTask", function (e) {
                e.preventDefault();
                let name = $("#name").val();
                let description = $("#description").val();
                let date = $("#dateHidden").val();
                $.ajax({
                    type: "POST",
                    url: "/postEvent",
                    data: {
                        name: name,
                        description: description,
                        data: date,
                    },
                    success: function (response) {
                        updateTodoList();
                    },
                    error: function (jqXHR) {
                        console.log(jqXHR);
                    },
                });
            });
            $(document).on('click', '.uncheck', function () {
                let id = $(this).attr("data-id");
                let btn = $(this);
                $.ajax({
                    type: "POST",
                    url: "/checkEvent",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        console.log(response);
                        btn.removeClass('uncheck');
                        btn.addClass('check');
                    },
                    error: function (jqXHR) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            });
            $(document).on('click', '.check', function () {
                let id = $(this).attr("data-id");
                let btn = $(this);
                $.ajax({
                    type: "POST",
                    url: "/uncheckEvent",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        console.log(response);
                        btn.removeClass('check');
                        btn.addClass('uncheck');
                    },
                    error: function (jqXHR) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            })
        })


    });

    // RENDER LES TODOS AVEC LA REPONSE EN PARAMETRE
    function renderTodoList(data) {
        var todoWrapper = $('.todo__wrapper');
        todoWrapper.empty();

        if (data.length > 0) {
            data.forEach(function (item) {
                var state = item.state === "1";
                var categoryHtml = item.categoryName !== null ?
                    '<p>' + item.categoryName + '</p>' :
                    '<p onclick="defineCategory(' + item.id + ', this)">Définir une catégorie</p>';

                var todoHtml = `
                <div class="todo__items">
                    <button class="btn__check ${state ? 'check' : 'uncheck'}" data-id='${item.id}'></button>
                    <p class="todo__name">${item.name}</p>
                    <div class="todo__category">${categoryHtml}</div>
                    <div class="todo__description">
                        <p class="todo__description todo__description__title hidden">Description : </p>
                        <p class="todo__description hidden">${item.description}</p>
                    </div>
                    <button class="btn__more">
                        <i class="fas fa-angle-down close"></i>
                    </button>
                </div>`;

                todoWrapper.append(todoHtml);
            });
        } else {
            todoWrapper.append('<p class="warning__text">Vous n\'avez pas encore de tâche, commencez par en créer une.</p>');
        }
    }
</script>