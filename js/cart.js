$(() => {
    const cart = localStorage.getItem("cart");
    if (cart != null && cart.length != 0 && cart.length > 2) {
        const list = JSON.parse(cart);
        list.sort((a, b) => {
            return a - b;
        });
        $("#cartPanel .loader").removeClass("hide");
        $(".cart-items").empty();
        var tPrice = 0;
        localStorage.removeItem("c_purpose");
        list.forEach(elem => {
            var query = elem.v == -1 ? "?what={products}&filter={products._pid}&with={" + elem.id + "}" : "?what={products,varients}&filter={products._pid,varients._vid}&with={" + elem.id + "," + elem.v + "}";
            $.get("services/get.php" + query, (data, status) => {
                let dataObj = JSON.parse(data)[0];
                $("#cartPanel .loader").addClass("hide");
                $("#pricePanel .loader").addClass("hide");
                $(".cart-items").append(getCartItem(dataObj, elem.qty));
                $(".price-brief").append(`<h6><span>${dataObj.title} x ${elem.qty}</span><span class="right">&#8377; ${(elem.qty * dataObj.price).toFixed(2).toLocaleString()}</h6>`);
                tPrice += elem.qty * dataObj.price;
                localStorage.setItem("c_purpose", `${localStorage.getItem("c_purpose") != null ? localStorage.getItem("c_purpose") + ',' : ''} ${dataObj.title} x ${elem.qty}`);
                localStorage.setItem("c_price", tPrice.toFixed(2).toLocaleString());
                $(".t-price b").text(tPrice.toFixed(2).toLocaleString());
            });
        });
        $("#pricePanel").removeClass("hide");
    } else {
        $("#cartPanel .loader").addClass("hide");
        $(".cart-items").html("<div class='my-2'>No Items Added in Cart.</div>");
        $(".price-brief").closest(".col").remove();
    }

    // M.Modal.init(document.querySelectorAll('#payMethod.modal'), {
    //     dismissible: false
    // });

    $('#payMethod.modal').modal({
        dismissible: false
    });

    $("#guestCheckout form#guestForm").submit(e => {
        e.preventDefault();
        // console.log($(e.target).serialize());

        $.ajax({
            url: "services/guest-checkout.php",
            method: "POST",
            data: $(e.target).serialize() + "&" + $.param({
                cart: JSON.parse(localStorage.getItem("cart"))
            }),
            beforeSend: () => {
                $("#guestCheckout .progress-holder, #guestCheckout .prevent-overlay").removeClass("hide");
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
                    $("#guestCheckout").modal("close");
                    $("#payMethod #order_id").val(object.oid);
                    $("#payMethod .rollback").attr("onclick", "rollbackOrder(" + object.oid + ")");
                    $("#payMethod").modal("open");
                }
            },
            error: (data, status) => {
                M.toast({
                    html: data
                });
                console.log(data, status);
            },
            complete: () => {
                $("#guestCheckout .progress-holder, #guestCheckout .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#checkout form#checkoutForm").submit(e => {
        var type = "new"
        if (e.target.add_id != null) {
            type = "old";
        }
        e.preventDefault();
        // console.log($(e.target).serialize());

        $.ajax({
            url: "services/checkout.php",
            method: "POST",
            data: $(e.target).serialize() + "&" + $.param({
                cart: JSON.parse(localStorage.getItem("cart"))
            }) + "&type=" + type,
            beforeSend: () => {
                $("#checkout .progress-holder, #checkout .prevent-overlay").removeClass("hide");
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
                    $("#checkout").modal("close");
                    $("#payMethod #order_id").val(object.oid);
                    $("#payMethod .rollback").attr("onclick", "rollbackOrder(" + object.oid + ")");
                    $("#payMethod").modal("open");
                }
            },
            error: (data, status) => {
                M.toast({
                    html: data
                });
                console.log(data, status);
            },
            complete: () => {
                $("#checkout .progress-holder, #checkout .prevent-overlay").addClass("hide");
            }
        });
    });

    $("#payMethod form").submit(e => {
        e.preventDefault();
        // console.log($(e.target).serialize());

        $.ajax({
            url: "services/pay-method.php",
            method: "POST",
            data: $(e.target).serialize(),
            beforeSend: () => {
                $("#payMethod .progress-holder, #payMethod .prevent-overlay").removeClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                // return;
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });
                if (object.status == "success") {
                    // e.target.reset();
                    $("#payMethod").modal("close");
                    if (e.target.pay_method.value == 2) {
                        $.ajax({
                            url: "services/pay-api.php",
                            method: "POST",
                            data: {
                                purpose: localStorage.getItem("c_purpose"),
                                amount: localStorage.getItem("c_price")
                            },
                            success: (data, status) => {
                                console.log("Something goes here");
                            }
                        });
                    } else {
                        localStorage.removeItem("cart");
                        window.location.href = "/orders";
                    }
                }
            },
            error: (data, status) => {
                M.toast({
                    html: data
                });
                console.log(data, status);
            },
            complete: () => {
                $("#payMethod .progress-holder, #payMethod .prevent-overlay").addClass("hide");
            }
        });
    });


    $("#add_id").change(e => {
        var addData = $(e.target).find("[value='" + e.target.value + "']").data("fields");
        if (addData != null) {
            $("#co_address").val(addData['address']);
            $("#co_landmark").val(addData['landmark']);
            $("#co_pincode").val(addData['pincode']);
            $("#co_r_name").val(addData['name']);
            $("#co_r_number").val(addData['number']);
        }
    });
});


function getCartItem(e, qty) {
    return `<div class="py-4 cart-item">
                <div class="row valign-wrapper m-0">
                    <div class="col s3">
                        <img src="${e.has_image == 0 ? 'uploads/products/placeholder-' + e._tid + '.jpg' : e.image}" alt="${e.title}" class="responsive-img w-100 grey lighten-3" style="object-fit:${e.has_image == 0 ? 'contain' : 'cover'}" />
                    </div>
                    <div class="col s9 row">
                        <div class="col s12 l8">
                            <h5 class="m-0 fw-500">${e.title}</h5>
                            <p class="black-text">${e.description}</p>
                            <h6 class="fw-700">&#8377; ${(+e.price).toFixed(2).toLocaleString()}</h6>
                        </div>
                        <div class="col s12 l4">
                            <button class="btn-floating btn-small white waves-effect waves-dark remove" ${qty <= 1 ? 'disabled' : ''} onclick="changeQ(this, -1, ${e.qty}, ${e._pid})"><i class="material-icons black-text">remove</i></button>
                            <input type="number" name="qty" class="cart-qty" value="${qty}" min="1" max="${e.qty}" onblur="check(this, ${e._pid})" />
                            <button class="btn-floating btn-small white waves-effect waves-dark add mr-2" ${qty >= e.qty ? 'disabled' : ''} onclick="changeQ(this, 1, ${e.qty}, ${e._pid})"><i class="material-icons black-text">add</i></button>
                            <button class="btn red waves-effect my-2" onclick="removeItem(${e._pid}, this)"><i class="material-icons left">delete</i>Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>`;
}

function changeQ(elem, num, max, id) {
    let inputElem = $(elem).closest("div").find("input");
    const newNum = +inputElem.val() + num;
    inputElem.val(newNum);

    if (newNum == 1) {
        if ($(elem).hasClass("remove")) {
            $(elem).attr("disabled", "disabled");
        } else {
            $(elem).siblings(".remove").attr("disabled", "disabled");
        }
    } else {
        if ($(elem).hasClass("remove")) {
            $(elem).removeAttr("disabled");
        } else {
            $(elem).siblings(".remove").removeAttr("disabled");
        }
    }

    if (newNum == max) {
        if ($(elem).hasClass("add")) {
            $(elem).attr("disabled", "disabled");
        } else {
            $(elem).siblings(".add").attr("disabled", "disabled");
        }
    } else {
        if ($(elem).hasClass("add")) {
            $(elem).removeAttr("disabled");
        } else {
            $(elem).siblings(".add").removeAttr("disabled");
        }
    }
    var list = localStorage.getItem("cart");
    list = JSON.parse(list);
    list.forEach(item => {
        if (item.id == id) {
            item.qty = +newNum;
        }
    });
    localStorage.setItem("cart", JSON.stringify(list));
    M.toast({
        html: "Quantity updated to " + newNum
    });
    changePricePanel();
}

function removeItem(id, elem) {
    var list = localStorage.getItem("cart");
    list = JSON.parse(list);
    var done = false;
    list.forEach(item => {
        if (item.id == id) {
            list.splice(list.indexOf(item), 1);
            localStorage.setItem("cart", JSON.stringify(list));
            $(".cart .num").text(list.length);
            $(elem).closest(".cart-item").slideUp();
            done = true;
        }
    });
    if (!done) {
        M.toast({
            html: "Something went wrong, try again"
        })
    }
    changePricePanel();

}

function check(elem, id) {
    let newNum = elem.value;
    const max = elem.getAttribute("max");
    const min = elem.getAttribute("min");
    if (elem.value >= +max) {
        elem.value = max;
        $(elem).siblings(".add").attr("disabled", "disabled");
        $(elem).siblings(".remove").removeAttr("disabled");
        M.toast({
            html: "Quantity updated Max: " + max
        });
        newNum = max;
    } else if (elem.value < +min) {
        elem.value = min;
        $(elem).siblings(".remove").attr("disabled", "disabled");
        $(elem).siblings(".add").removeAttr("disabled");
        M.toast({
            html: "Quantity updated Min: " + min
        });
        newNum = min;
    } else {
        $(elem).siblings(".remove").removeAttr("disabled");
        $(elem).siblings(".add").removeAttr("disabled");
        M.toast({
            html: "Quantity updated to " + elem.value
        });
    }
    var list = localStorage.getItem("cart");
    list = JSON.parse(list);
    list.forEach(item => {
        if (item.id == id) {
            item.qty = newNum;
        }
    });
    localStorage.setItem("cart", JSON.stringify(list));
    changePricePanel();
}

function changePricePanel() {
    const cart = localStorage.getItem("cart");
    if (cart != null && cart.length != 0 && cart.length > 2) {
        const list = JSON.parse(cart);
        list.sort((a, b) => {
            return a - b;
        });
        var tPrice = 0;
        $(".price-brief").empty();
        $("#pricePanel .loader").removeClass("hide");
        localStorage.removeItem("c_purpose");
        list.forEach(elem => {
            var query = elem.v == -1 ? "?what={products}&filter={products._pid}&with={" + elem.id + "}" : "?what={products,varients}&filter={products._pid,varients._vid}&with={" + elem.id + "," + elem.v + "}";
            $.get("services/get.php" + query, (data, status) => {
                let dataObj = JSON.parse(data)[0];
                $("#pricePanel .loader").addClass("hide");
                $(".price-brief").append(`<h6><span>${dataObj.title} x ${elem.qty}</span><span class="right">&#8377; ${(elem.qty * dataObj.price).toFixed(2).toLocaleString()}</h6>`);
                tPrice += elem.qty * dataObj.price;
                localStorage.setItem("c_purpose", `${localStorage.getItem("c_purpose") != null ? localStorage.getItem("c_purpose") + ',' : ''} ${dataObj.title} x ${elem.qty}`);
                localStorage.setItem("c_price", tPrice.toFixed(2).toLocaleString());
                $(".t-price b").text(tPrice.toFixed(2).toLocaleString());
            });
        });
        $("#pricePanel").removeClass("hide");
    } else {
        $(".cart-items").html("<div class='my-2'>No Items Added in Cart.</div>");
        $(".price-brief").closest(".col").remove();
    }
}

function rollbackOrder(oid) {
    let answer = confirm("Are you sure, you want to delete this order?");
    if (answer) {
        // elem = $(elem).closest("tr");
        $.ajax({
            url: "services/delete-order.php",
            method: "POST",
            data: {
                _oid: oid
            },
            beforeSend: () => {
                $("#payMethod .progress-holder, #payMethod .prevent-overlay").addClass("hide");
            },
            success: (data, status) => {
                // console.log(data, status);
                var object = JSON.parse(data);
                M.toast({
                    html: object.message
                });
                if (object.status == "success") {
                    $("#payMethod").modal("close");
                }
            },
            error: (data, status) => {
                console.log(data, status);
            },
            complete: () => {
                $("#payMethod .progress-holder, #payMethod .prevent-overlay").addClass("hide");
            }
        });
    }
}