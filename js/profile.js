$(() => {

    $("form[data-id]").submit(e => {
        e.preventDefault();
        // console.log();
        saveDetail(e.target, $(e.target).data("id"));
    });

    $("#newAddress form").submit(e => {
        e.preventDefault();
        // console.log($(e.target).serialize());

        $.ajax({
            url: "services/new-address.php",
            method: "POST",
            data: $(e.target).serialize(),
            beforeSend: () => {
                $("#newAddress .progress-holder, #newAddress .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                // return;
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });
                if (object.status == "success") {
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            },
            error: (data, status) => {
                M.toast({
                    html: data
                });
                console.log(data, status);
            },
            complete: () => {
                $("#newAddress .progress-holder, #newAddress .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#changePassword form").submit(e => {
        e.preventDefault();
        // console.log($(e.target).serialize());
        if (e.target.newpassword.value != e.target.rnewpassword.value) {
            M.toast({
                html: "Password Mismatch"
            });
            e.target.newpassword.focus();
            return;
        }


        $.ajax({
            url: "services/change-password.php",
            method: "POST",
            data: $(e.target).serialize(),
            beforeSend: () => {
                $("#changePassword .progress-holder, #changePassword .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                // return;
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });
                if (object.status == "success") {
                    e.target.reset();
                }
            },
            error: (data, status) => {
                M.toast({
                    html: data
                });
                console.log(data, status);
            },
            complete: () => {
                $("#changePassword .progress-holder, #changePassword .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#editAddress form").submit(e => {
        e.preventDefault();
        // console.log($(e.target).serialize());

        $.ajax({
            url: "services/edit-address.php",
            method: "POST",
            data: $(e.target).serialize(),
            beforeSend: () => {
                $("#editAddress .progress-holder, #editAddress .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                // return;
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });
                if (object.status == "success") {
                    e.target.reset();
                    $("#editAddress").modal("close");
                }
            },
            error: (data, status) => {
                M.toast({
                    html: data
                });
                console.log(data, status);
            },
            complete: () => {
                $("#editAddress .progress-holder, #editAddress .prevent-overlay").addClass("hide");
            }
        });
    });
});

function addNewAddress() {
    if ($("#notice").length > 0) {
        $("#notice").slideUp();
    }

    $("#newAddress").slideDown();
}

function wipeItOut(elem) {
    $(elem).closest("#newAddress").slideUp();
    if ($("#notice").length > 0) {
        $("#notice").slideDown();
    }
}

function deleteAddress(aid, elem) {
    let answer = confirm("Are you sure, you want to delete this address?");
    if (answer) {
        elem = $(elem).closest(".grey.p-4");
        $.ajax({
            url: "services/delete-address.php",
            method: "POST",
            data: {
                _aid: aid
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

function edit(elem) {
    $(elem).closest(".field").find("input").removeAttr("disabled");
    $(elem).closest(".field").find(".btn, .btn-flat").toggleClass("hide");
}

function editCancel(elem) {
    $(elem).closest(".field").find("input").attr("disabled", "disabled");
    $(elem).closest(".field").find(".btn, .btn-flat").toggleClass("hide");
}

function saveDetail(elem, what) {
    // console.log($(elem).closest(".field").find("form").serialize());
    $.ajax({
        url: "services/update-detail.php?what=" + what,
        method: "POST",
        data: $(elem).closest(".field").find("form").serialize(),
        beforeSend: () => {
            M.toast({
                html: "Updating, Please wait..."
            });
        },
        success: (data, status) => {
            // console.log(data, status);
            // return;
            var object = JSON.parse(data);
            M.toast({
                html: object.message
            });
        },
        error: (data, status) => {
            M.toast({
                html: data
            });
            console.log(data, status);
        },
        complete: () => {
            editCancel(elem);
        }
    });

}

function editAddress(data) {
    console.log(data);
    let obj = JSON.parse(data);
    $("#editAddress #e_aid").val(obj._aid);
    $("#editAddress #ername").val(obj.name);
    $("#editAddress #ernumber").val(obj.number);
    $("#editAddress #eaddress").val(obj.address);
    $("#editAddress #elandmark").val(obj.landmark);
    $("#editAddress #epincode").val(obj.pincode);

    $("#editAddress #e_address_type option").removeAttr("selected");
    $("#editAddress #e_address_type option[value='" + obj.tag + "']").attr("selected", "selected");
    $("#editAddress #e_address_type input").val($("#editAddress #e_address_type option[value='" + obj.tag + "']").text());
    M.updateTextFields();
    $("#editAddress").modal("open");
}