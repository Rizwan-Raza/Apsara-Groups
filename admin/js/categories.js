$(() => {
    $("#categoryAdd form #title").keyup(() => {
        $("#categoryAdd form #url").val($("#categoryAdd form #title").val().toLowerCase().replace(/ /g, "-"));
        M.updateTextFields();
    });

    $("#categoryAdd form #etitle").keyup(() => {
        $("#categoryAdd form #eurl").val($("#categoryAdd form #etitle").val().toLowerCase().replace(/ /g, "-"));
        M.updateTextFields();
    });


    $("#categoryAdd form").submit(e => {
        e.preventDefault();
        $.ajax({
            url: "admin/services/add-category.php",
            method: "POST",
            data: $(e.target).serialize(),
            beforeSend: () => {
                $("#categoryAdd .progress-holder, #categoryAdd .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });

                if (object.status == "duplicate_error") {
                    e.target.title.focus();
                    return;
                } else if (object.status == "success") {
                    e.target.reset();
                    $("#categoryAdd").modal("close");
                }
            },
            error: (data, status) => {
                console.log(data, status);
            },
            complete: () => {
                $("#categoryAdd .progress-holder, #categoryAdd .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#categoryEdit form").submit(e => {
        e.preventDefault();
        $.ajax({
            url: "admin/services/edit-category.php",
            method: "POST",
            data: $(e.target).serialize(),
            beforeSend: () => {
                $("#categoryEdit .progress-holder, #categoryEdit .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });

                if (object.status == "duplicate_error") {
                    e.target.title.focus();
                    return;
                } else if (object.status == "success") {
                    e.target.reset();
                    $("#categoryEdit").modal("close");
                }
            },
            error: (data, status) => {
                console.log(data, status);
            },
            complete: () => {
                $("#categoryEdit .progress-holder, #categoryEdit .prevent-overlay").addClass("hide");
            }
        });
    });
});

function deleteCategory(id, elem) {
    let answer = confirm("Are you sure, you want to delete this category?");
    if (answer) {
        elem = $(elem).closest("tr");
        $.ajax({
            url: "admin/services/delete-category.php",
            method: "POST",
            data: {
                _tid: id
            },
            beforeSend: () => {
                elem.css("opacity", 0.5);
                // $("#progress, .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });
                if (object.status == "server_error") {
                    elem.css("opacity", 1);
                    return;
                } else if (object.status == "success") {
                    elem.slideUp();
                }
            },
            error: (data, status) => {
                elem.css("opacity", 1);
                console.log(data, status);
            }
        });
    }
}

function editCategory(id, title, url, desc) {
    $("#categoryEdit #e_tid").val(id);
    $("#categoryEdit #etitle").val(title);
    $("#categoryEdit #eurl").val(url);
    $("#categoryEdit #edescription").val(desc);
    M.updateTextFields();
    $("#categoryEdit").modal("open");
}