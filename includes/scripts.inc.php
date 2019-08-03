<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    $(() => {
        const cart = localStorage.getItem("cart")
        if (cart != null && cart.length != 0) {
            var list = JSON.parse(cart);
            if (list.length > 0) {
                $(".cart .num").text(list.length);
                $(".cart .num").removeClass("hide");
            }
        }
        M.AutoInit();

        $("#explore .carousel").carousel({
            fullWidth: true,
            numVisible: 3
        });


        $("#signUp form#signUpForm").submit(e => {
            e.preventDefault();
            // console.log($(e.target).serialize());
            if (e.target.password.value != e.target.rpassword.value) {
                M.toast({
                    html: "Password Mismatch"
                });
                e.target.password.focus();
                return;
            }

            $.ajax({
                url: "services/signup.php",
                method: "POST",
                data: $(e.target).serialize(),
                beforeSend: () => {
                    $("#signUp .progress-holder, #signUp .prevent-overlay").removeClass("hide");
                },
                success: (data, status) => {
                    console.log(data, status);
                    var object = JSON.parse(data);
                    M.toast({
                        html: object.message
                    });
                    if (object.status == "success") {
                        e.target.reset();
                    } else if (object.status == "password_error") {
                        e.target.password.focus();
                    }
                },
                error: (data, status) => {
                    M.toast({
                        html: data
                    });
                    console.log(data, status);
                },
                complete: () => {
                    $("#signUp .progress-holder, #signUp .prevent-overlay").addClass("hide");
                }
            });
        });

        $("#signIn form#signInForm").submit(e => {
            e.preventDefault();
            // console.log($(e.target).serialize());

            $.ajax({
                url: "services/signin.php",
                method: "POST",
                data: $(e.target).serialize(),
                beforeSend: () => {
                    $("#signIn .progress-holder, #signIn .prevent-overlay").removeClass("hide");
                },
                success: (data, status) => {
                    console.log(data, status);
                    var object = JSON.parse(data);
                    M.toast({
                        html: object.message
                    });
                    if (object.status == "success") {
                        window.location.reload();
                    }
                },
                error: (data, status) => {
                    M.toast({
                        html: data
                    });
                    console.log(data, status);
                },
                complete: () => {
                    $("#signIn .progress-holder, #signIn .prevent-overlay").addClass("hide");
                }
            });
        });

    });

    function popOut(elem, heightNum) {
        let element = $(elem).find(".small-drop");
        if (element.css("height") == 0 || element.css("height") == "0px") {
            element.animate({
                    height: (heightNum * 48) + "px",
                    opacity: "1"
                },
                200
            );
        } else {
            element.animate({
                    height: "0",
                    opacity: "0"
                },
                200
            );
        }
    }

    function passVisibility(elem, id) {
        elem = $(elem);
        var pass = $(id);
        var icon = elem.find("i");
        if (icon.text().endsWith("_off")) {
            elem.attr("data-tooltip", "Hide Password");
            icon.text(icon.text().replace("_off", ""));

            pass.attr("type", "text");
        } else {
            elem.attr("data-tooltip", "Show Password");
            icon.text(icon.text() + "_off");
            pass.attr("type", "password");
        }
    }

    // var shrinked = false;

    // $(document).scroll((e) => {

    //     if ($(e.target).scrollTop() > 50) {
    //         if (!shrinked) {
    //             $(".fixed-logo.circle").animate({
    //                 "height": window.innerWidth > 600 ? "65px" : "56px",
    //             }, 200).removeClass("z-depth-2");
    //             shrinked = true;
    //         }
    //     } else if (0 < $(e.target).scrollTop() <= 50) {
    //         if (shrinked) {
    //             $(".fixed-logo.circle").animate({
    //                 "height": window.innerWidth > 600 ? "130px" : "112px",
    //             }, 200).addClass("z-depth-2");
    //             shrinked = false;
    //         }
    //     }
    // });

    function next(elem) {
        $(elem).carousel("next");
    }

    function prev(elem) {
        $(elem).carousel("prev");
    }

    function addToCart(id, v) {
        const stringList = localStorage.getItem("cart");
        var list = [];
        if (stringList != null && stringList.length != 0) {
            list = JSON.parse(stringList);
        }
        var added = false;
        list.forEach(item => {
            if (item.id == id && item.v == v) {
                M.toast({
                    html: "Product Already Added, try to increase quantity from cart"
                });
                added = true;
            }
        });
        if (!added) {
            list.push({
                id: id,
                qty: 1,
                v: v
            });
            localStorage.setItem("cart", JSON.stringify(list));
            $(".cart .num").text(list.length);
            $(".cart .num").removeClass("hide");
            M.toast({
                html: "Product Added to cart"
            });
        }
    }

    function openModal(prev, next) {
        $(prev).closest(".modal").modal("close");
        $(next).modal("open");
    }
</script>