<div class="todo__all">
    <p class="todo__title">Taches</p>
    <div class="todo__wrapper">

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
    <div class="share">
        <p style='font-family: Poppins; color: white; font-size: 1em;'>Partager la tâche</p>
        <input type="text" name="share" id="share" value='http://todolist.test/share/uid'>
    </div>
    <div class="delete">
        <a id='deleteTask' id='delete__todo'>Supprimer la tâche</a>
    </div>
    <input type="submit" value="Envoyer">
</form>


<script>
    $(document).ready(() => {
        $('.todo__wrapper').sortable({
            axis: 'y',
            update: function (event, ui) {
                var updatedOrder = $(this).sortable('toArray', { attribute: 'data-order' });
                let todoID = $(this).attr("data-id");
                updateTodoOrder(todoID, updatedOrder);
            }
        });

        function updateTodoOrder(todoID, newPosition) {
            var updatedOrderData = {};
            $('.todo__wrapper .todo__items').each(function (index) {
                var taskId = $(this).data('id');
                updatedOrderData[taskId] = index;
            });
            $.ajax({
                type: "POST",
                url: "/updateTodoOrder",
                data: {
                    orderData: updatedOrderData
                },
                success: function (response) {
                    updateTodoList();
                },
                error: function (jqXHR) {
                    console.log(jqXHR);
                }
            });
        }

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
                if (name.length < 3) {
                    if ($(".warning__form").length === 0) {
                        const warningText = $("<p class='warning__form'>Veuillez entrer un nom de tâche d'au moins 3 caractères</p>");
                        $(".form").append(warningText);
                    }

                } else {
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
                            hideForm();
                        },
                        error: function (jqXHR) {
                            console.log(jqXHR);
                        },
                    });
                }

            });

            $(document).on('submit', '.parameter', function (e) {
                e.preventDefault();
                let todoID = $("#todoID").val();
                let color = $("#form__color").val();
                $.ajax({
                    type: "POST",
                    url: "/editTodo",
                    data:
                    {
                        todoID: todoID,
                        color: color
                    },
                    success: function (response) {
                        updateTodoList();
                        console.log(response);
                    }, error: function (jqXHR) {
                        console.log(jqXHR);
                    }
                });
            })


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
                        btn.removeClass('check');
                        btn.addClass('uncheck');
                    },
                    error: function (jqXHR) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            })
            $(document).on('click', '.todo__parameter', function () {
                $('.parameter').css('display', 'block');

            })

            $('#deleteTask').click(function () {
                let todoID = $("#todoID").val();
                $.ajax({
                    type: "POST",
                    url: "/deleteTodo",
                    data: { id: todoID },
                    success: function (response) {
                        updateTodoList();
                    }, error: function (jqXHR) {
                        console.log(jqXHR);
                    }
                });
            })
        })


    });

    function isColorDark(hexColor) {
        const r = parseInt(hexColor.slice(1, 3), 16);
        const g = parseInt(hexColor.slice(3, 5), 16);
        const b = parseInt(hexColor.slice(5, 7), 16);

        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance < 0.5;
    }

    // RENDER LES TODOS AVEC LA REPONSE EN PARAMETRE
    function renderTodoList(data) {
        var todoWrapper = $('.todo__wrapper');
        todoWrapper.empty();
        if (data != null) {
            if (data.length > 0 && data != null) {
                data.forEach(function (item) {
                    var state = item.state === "1";
                    let color = item.color;
                    let textColor = isColorDark(color) ? 'white' : 'black';
                    var categoryHtml = item.categoryName !== null ?
                        '<p>' + item.categoryName + '</p>' :
                        '<p onclick="defineCategory(' + item.id + ', this)">Définir une catégorie</p>';

                    var todoHtml = `
                                                                <div class="todo__items" data-id='${item.id}' data-order='${item.orderTodo}' style='background: ${color}; color: ${textColor}'>
                                                                    <button class="btn__check ${state ? 'check' : 'uncheck'}" data-id='${item.id}'></button>
                                                                    <p class="todo__name">${item.name}</p>
                                                                    <div class="todo__category">${categoryHtml}</div>
                                                                    <div class="todo__description">
                                                                        <p class="todo__description todo__description__title hidden">Description : </p>
                                                                        <p class="todo__description hidden">${item.description}</p>
                                                                    </div>
                                                                    <div class='todo__parameter'><i class='fas fa-gear'></i></div>
                                                                    <button class="btn__more">
                                                                        <i class="fas fa-angle-down close"></i>
                                                                    </button>
                                                                </div>`;


                    todoWrapper.append(todoHtml);
                });
            }
        } else {
            todoWrapper.append('<p class="warning__text">Vous n\'avez pas encore de tâche, commencez par en créer une.</p>');

        }
    }

</script>