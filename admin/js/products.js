$(() => {

    $("#productAdd form").submit(e => {
        e.preventDefault();
        $.ajax({
            url: "admin/services/add-product.php",
            contentType: false,
            processData: false,
            method: "POST",
            type: "POST",
            data: new FormData(e.target),
            beforeSend: () => {
                $("#productAdd .progress-holder, #productAdd .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });

                if (object.status == "success") {
                    e.target.reset();
                    e.target.varities.value = 1;
                    $("#productAdd .varients").html(`<div class="row enhanced">
                    <div class="input-field col s12 m4">
                        <input id="size_1" type="text" class="validate" name="size_1" required value="Normal">
                        <label for="size_1">Size Varient (1 Pound, 2 Pound, Small, Medium, Large etc.)</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input id="price_1" type="number" class="validate" name="price_1" required>
                        <label for="price_1">Price per piece (&#8377;)</label>
                    </div>
                    <div class="input-field col s9 m3">
                        <input id="quantity_1" type="number" class="validate" name="quantity_1" required>
                        <label for="quantity_1">Quantity</label>
                    </div>
                </div>`);
                    $("#productAdd #imageSwicth").prop("checked", false);
                    $("#productAdd .file-field").slideUp();
                    $("#productAdd #variety .btn").attr("onclick", "addMore(this,1)");
                    M.updateTextFields();
                    $("#productAdd").modal("close");
                }
            },
            error: (data, status) => {
                console.log(data, status);
            },
            complete: () => {
                $("#productAdd .progress-holder, #productAdd .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#imageSwitch").change(() => {
        $("#productAdd .file-field.input-field").slideToggle();
    });

    $("#productEdit form").submit(e => {
        e.preventDefault();
        $.ajax({
            url: "admin/services/edit-product.php",
            contentType: false,
            processData: false,
            method: "POST",
            type: "POST",
            data: new FormData(e.target),
            beforeSend: () => {
                $("#productEdit .progress-holder, #productEdit .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });

                if (object.status == "success") {
                    e.target.reset();
                    $("#productEdit").modal("close");
                }
            },
            error: (data, status) => {
                console.log(data, status);
            },
            complete: () => {
                $("#productEdit .progress-holder, #productEdit .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#eimageSwitch").change(() => {
        $("#productEdit .file-field.input-field").slideToggle();
    });
});

function deleteProduct(id, elem) {
    let answer = confirm("Are you sure, you want to delete this product?");
    if (answer) {
        elem = $(elem).closest("tr");
        $.ajax({
            url: "admin/services/delete-product.php",
            method: "POST",
            data: {
                _pid: id
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

function editProduct(id, title, cat_id, desc, image, has_image) {
    $("#productEdit #e_pid").val(id);
    $("#productEdit #etitle").val(title);

    $("#productEdit #ecatId option").removeAttr("selected");
    $("#productEdit #ecatId option[value='" + cat_id + "']").attr("selected", "selected");
    $("#productEdit #ecatId").closest(".input-field").find("input").val($("#productEdit #ecatId option[value='" + cat_id + "']").text());

    $("#productEdit #edescription").val(desc);

    if (has_image == 1) {
        $("#productEdit #eimageSwitch").prop("checked", true);
        $("#productEdit .file-field.input-field").show("hide");
        $("#productEdit #eholder").attr("src", image).removeClass("hide");
        $("#productEdit #prev_image").attr("type", "text").val(image);
    } else {
        $("#productEdit #eimageSwitch").prop("checked", false);
        $("#productEdit .file-field.input-field").hide();
        $("#productEdit #eholder").attr("src", "uploads/products/placeholder.jpg").removeClass("hide");
        $("#productEdit #prev_image").attr("type", "hidden").val("");
    }

    $.get("admin/services/get.php?what=varients&order=weight&filter=_pid&with=" + id, e => {
        let numCount = 1;
        $("#productEdit .varients").empty();
        JSON.parse(e).forEach(v => {
            if (!$("#productEdit .progress-holder, #productEdit .prevent-overlay").hasClass("hide")) {
                $("#productEdit .progress-holder, #productEdit .prevent-overlay").addClass("hide");
            }
            $("#productEdit .varients").append(`<div class="row enhanced">
                <div class="input-field col s12 m4">
                    <input id="esize_${numCount}" type="text" class="validate" name="size_${numCount}" required value="${v.size}">
                    <label for="esize_${numCount}">Size Varient (1 Pound, 2 Pound, Small, Medium, Large etc.)</label>
                </div>
                <div class="input-field col s12 m4">
                    <input id="eprice_${numCount}" type="number" class="validate" name="price_${numCount}" required  value="${v.price}">
                    <label for="eprice_${numCount}">Price per piece (&#8377;)</label>
                </div>
                <div class="input-field col s9 m3">
                    <input id="equantity_${numCount}" type="number" class="validate" name="quantity_${numCount}" required  value="${v.qty}">
                    <label for="equantity_${numCount}">Quantity</label>
                </div>
                ${numCount > 1 ? '<div class="col s3 m1"><button type="button" class="transparent btn-flat circle btn-floating btn-medium tooltipped waves-effect waves-circle waves-dark my-4" onclick="deleteVarient(this)" data-tooltip="Delete Varient"><i class="material-icons black-text lh-5">delete</i></button></div>' : ''}
            </div>`);

            $("#productEdit #evarities").val(numCount);
            numCount++;
            M.updateTextFields();
        });
    });
    $("#productEdit").modal("open");
}

function viewProduct(id, title, cat_id, desc, image, has_image) {
    $("#productView .card-action table tbody").empty();
    $("#productView #vtitle").text(title);
    $("#productView #vcat").text(cat_id);
    $("#productView #vdesc").text(desc);

    if (has_image == 1) {
        $("#productView .card-image img").attr("src", image);
        $("#productView .card-image").show();
    } else {
        $("#productView .card-image").hide();
    }
    $.get("admin/services/get.php?what=varients&order=size&filter=_pid&with=" + id, e => {
        JSON.parse(e).forEach(v => {
            if (!$("#productView .card-action .loader").hasClass("hide")) {
                $("#productView .card-action .loader").addClass("hide");
            }
            $("#productView .card-action table tbody").append(`<tr><td><b>${v.size}</b></td><td>(Quantity: <b>${v.qty}</b>)</td><td>&#8377; <b>${(+v.price).toFixed(2).toLocaleString()}</b></td></tr>`);
        });
    });

    M.updateTextFields();
    $("#productView").modal("open");
}


function readURL(input, query) {
    var files = !!input.files ? input.files : [];
    if (!files.length || !window.FileReader) return;
    if (/^image/.test(files[0].type)) {
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        reader.onloadend = function () {
            $(query).attr("src", this.result);
            $(query).removeClass("hide");
        }
    }
}


function addMore(elem, num) {
    num++;
    $(elem).closest("form").find(".varients").append(`<div class="row enhanced" style='display:none'>
    <div class="input-field col s12 m4">
            <input id="size_${num}" type="text" class="validate" name="size_${num}" required>
            <label for="size_${num}">Size Varient</label>
        </div>
        <div class="input-field col s12 m4">
            <input id="price_${num}" type="number" class="validate" name="price_${num}" required>
            <label for="price_${num}">Price per piece (&#8377;)</label>
        </div>
        <div class="input-field col s9 m3">
        <input id="quantity_${num}" type="number" class="validate" name="quantity_${num}" required>
            <label for="quantity_${num}">Quantity</label>
        </div>
        <div class="col s3 m1">
        <button type="button"
                class="transparent btn-flat circle btn-floating btn-medium tooltipped waves-effect waves-circle waves-dark my-4"
                onclick="deleteVarient(this)" data-tooltip="Delete Varient"><i
                class="material-icons black-text lh-5">delete</i></button>
        </div>
        </div>`);
    $(elem).attr("onclick", "addMore(this, " + num + ")");
    var varieties = $(elem).closest("form").find("input[name='varities']");
    varieties.val(+varieties.val() + 1);
    $(elem).closest("form").find(".varients .row").slideDown();
    console.log($(elem).closest("form").find("input[name='varities']").val());
}

function deleteVarient(elem) {
    var varieties = $(elem).closest("form").find("input[name='varities']");
    varieties.val(+varieties.val() - 1);

    let parentE = $(elem).closest(".row.enhanced");
    parentE.slideUp(400, () => {
        parentE.remove();
    });
    console.log($(elem).closest("form").find("input[name='varities']").val());
}