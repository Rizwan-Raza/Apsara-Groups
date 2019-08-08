$(() => {
    M.AutoInit();
});

function deleteUser(_uid, elem) {
    let answer = confirm("Are you sure, you want to delete this user?");
    if (answer) {
        elem = $(elem).closest("tr");
        $.ajax({
            url: "admin/services/delete-user.php",
            method: "POST",
            data: {
                _uid: _uid,
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
                console.log(data, status);
            },
            complete: () => {
                // $("#progress, .prevent-overlay").addClass("hide");
            }
        });
    }
}


function viewProfile(uid, name, email, number) {
    $("#profileModal h4").text(name);
    $("#profileModal #p_name").html(name);
    $("#profileModal #p_email").html(email);
    $("#profileModal #p_number").html(number);
    $.ajax({
        url: "admin/services/get.php",
        method: "GET",
        data: {
            what: "addresses",
            filter: "_uid",
            with: uid
        },
        success: (data, status) => {
            var obj = JSON.parse(data);
            var str = "";
            obj.forEach(elm => {
                str += `<div class="grey lighten-3 p-4 my-2">
                <div><span class="fw-500">${elm.address}<span class="chip right bg-primary">${(elm.tag == 1 ? 'Home' : (elm.tag == 2 ? 'Work' : 'Friend&apos;s Home'))}</span></span></div>
                <div>Landmark: <span class="fw-500">${elm.landmark}</span></div>
                <div>Pincode: <span class="fw-500">${elm.pincode}</span></div>
                <div>Receiver&apos;s Name: <span class="fw-500">${elm.name}</span></div>
                <div>Receiver&apos;s Name: <span class="fw-500">${elm.number}</span></div>
                </div>`;
            });
            $("#profileModal #p_adds").html(str);
            // console.log(data, status);
        },
        error: (data, status) => {
            console.log(data, status);
        }
    });
    $(".modal").modal('open');
}