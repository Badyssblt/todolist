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
    $(document).ready(() => {
        let isOpen;
        $('.filter__icon').click(function () {
            if (!isOpen) {
                $("#search").css("display", "block");
                $(".todo__filter").addClass("active");
                isOpen = true;
            } else {
                $("#search").css("display", "none");
                $(".todo__filter").removeClass("active");
                isOpen = false;
            }

        });

        $('.todo__wrapper__project').sortable({
            axis: 'y',
            update: function (event, ui) {
                var updatedOrder = $(this).sortable('toArray', { attribute: 'data-order' });
                let todoID = $(this).attr("data-id");
                updateTodoOrder(todoID, updatedOrder);
            }
        });

        $.ajax({
            type: "GET",
            url: "/getCategory",
            dataType: "JSON",
            success: function (response) {
                for (let i = 0; i < response.length; i++) {
                    $("#category").append($(`<option value='${response[i].id}'>${response[i].name}</option>`))
                }
            }
        });
        $(document).on("submit", "#search", function (e) {
            e.preventDefault();
        })
        $(document).on('change', '#search', function () {
            let category = $("#category").find(":selected").val();
            let name = $("#todoName").val();
            $.ajax({
                type: "POST",
                url: "/getTodo",
                data:
                {
                    category: category,
                    name: name
                },
                dataType: "JSON",
                success: function (response) {
                    renderFilteredTodoList(response);
                }, error: function (jqXHR) {
                    console.log(jqXHR);
                }
            });
        });

        $("#addTaskButton").click(() => {
            let date = new Date();
            addEvent(date);
        })

        function renderFilteredTodoList(filteredTodos) {
            const todoWrapper = $('.todo__wrapper__project');
            todoWrapper.empty();

            if (filteredTodos != null) {
                if (filteredTodos.length > 0) {
                    filteredTodos.forEach(function (item) {
                        var state = item.state === "1";
                        let color = item.color;
                        var categoryHtml = item.categoryName !== null ?
                            '<p>' + item.categoryName + '</p>' :
                            '<p onclick="defineCategory(' + item.id + ', this)">Définir une catégorie</p>';
                        let textColor = isColorDark(color) ? 'white' : 'black';
                        let todoHtml = `
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
                } else {
                    todoWrapper.append('<p class="warning__text">Aucune tâche trouvée pour les critères spécifiés.</p>');
                }
            } else {
                todoWrapper.append('<p class="warning__text">Une erreur s\'est produite lors de la récupération des tâches.</p>');
            }
        }

        function updateTodoOrder(todoID, newPosition) {
            var updatedOrderData = {};
            $('.todo__wrapper__project .todo__items').each(function (index) {
                var taskId = $(this).data('id');
                updatedOrderData[taskId] = index;
            });
            $.ajax({
                type: "POST",
                url: "/updateTodoOrderProject",
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


        // Mets à jour les todos
        function updateTodoList() {
            var url = window.location.href;
            let id = url.substring(url.lastIndexOf('/') + 1);
            $.ajax({
                type: "POST",
                url: "/homeProject",
                data: {
                    id: id
                },
                dataType: "JSON",
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
                var url = window.location.href;
                let projectID = url.substring(url.lastIndexOf('/') + 1);
                if (name.length < 3) {
                    if ($(".warning__form").length === 0) {
                        const warningText = $("<p class='warning__form'>Veuillez entrer un nom de tâche d'au moins 3 caractères</p>");
                        $(".form").append(warningText);
                    }

                } else {
                    $.ajax({
                        type: "POST",
                        url: "/postEventProject",
                        data: {
                            name: name,
                            description: description,
                            projectID: projectID,
                            owner: userID,
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
                    url: "/checkEventProject",
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
                    url: "/uncheckEventProject",
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
                $('.parameter').css('display', 'grid');
                let todoID = $(this).closest(".todo__items").data('id');
                $("#todoID").val(todoID);
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

    // RENDER LES TODOS AVEC LA REPONSE EN PARAMETRE
    function renderTodoList(data) {
        var todoWrapper = $('.todo__wrapper__project');
        todoWrapper.empty();
        if (data.type !== "noTodo" && data.type !== "NotAllowed") {
            if (data != null) {
                for (let i = 0; i < data.length; i++) {
                    let item = data[i];
                    var state = item.task_state === "1";
                    var categoryHtml = item.task_category !== null ?
                        '<p>' + item.task_category + '</p>' :
                        '<p onclick="defineCategory(' + item.task_id + ', this)">Définir une catégorie</p>';
                    var todoHtml = `
                                                                <div class="todo__items" data-id='${item.task_id}' data-order='${item.task_order}'>
                                                                    <button class="btn__check ${state ? 'check' : 'uncheck'}" data-id='${item.task_id}'></button>
                                                                    <div class='todo__name__content'>
                                                                        <p class="todo__name">${item.task_name}</p>
                                                                        <p style='font-size: .9em'>Par ${item.owner_username}</p>
                                                                    </div>
                                                                    
                                                                    <div class="todo__category">${categoryHtml}</div>
                                                                    <div class="todo__description">
                                                                        <p class="todo__description todo__description__title hidden">Description : </p>
                                                                        <p class="todo__description hidden">${item.task_description}</p>
                                                                    </div>
                                                                    <div class='todo__parameter'><i class='fas fa-gear'></i></div>
                                                                    ${item.task_description.length !== 0 ? `<button class="btn__more"><i class="fas fa-angle-down close"></i></button>` : ''}
                                                                </div>`;
                    todoWrapper.append(todoHtml);
                }



            } else {
                todoWrapper.append('<p class="warning__text">Vous n\'avez pas encore de tâche, commencez par en créer une.</p>');

            }
        } else if (data.type == "noTodo") {
            let message = $("<p class='warning__text'>Il n'y a pas de tâche pour le moment</p>");
            todoWrapper.append(message);
        } else if (data.type == "NotAllowed") {
            window.location.href = "/project";
            console.log("pas autorisé");
        }

    }

</script>