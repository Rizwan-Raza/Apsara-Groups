$(function () {

    // preventing page from redirecting
    $("html").on("dragover", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(".upload-area h4").text("Drag here");
        $(".upload-area").addClass("grey");
        $(".upload-area h4").addClass("grey-text");
        $(".upload-area").removeClass("green-text");
        $(".upload-area h4").removeClass("green-text");
    });

    $("html").on("drop", function (e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(".upload-area h4").text("Drop");
    });

    // Drag over
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(".upload-area h4").text("Drop");
        $(".upload-area h4").removeClass("grey-text");
        $(".upload-area h4").addClass("green-text");
        $(".upload-area").removeClass("grey");
        $(".upload-area").addClass("green");
    });

    // Drop
    $('.upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();

        $(".upload-area h4").text("Please wait ...");

        var file = e.originalEvent.dataTransfer.files;
        if (file[0].type == "application/pdf") {

            var fd = new FormData();

            fd.append('file', file[0]);

            uploadData(fd);
        } else {
            M.toast({
                html: "Please choose PDF Brochure"
            });
            $(".upload-area h4").html("<div class='w-full my-4'><i class='material-icons' style='font-size: 5rem;'>save_alt</i></div><div class='w-full'>Drag and Drop brochure here<br />Or<br />Click to select file</div>");
            $(".upload-area h4").addClass("grey-text");
            $(".upload-area").addClass("grey");
            $(".upload-area").removeClass("green");
            $(".upload-area h4").removeClass("green-text");
        }
    });

    // Open file selector on div click
    $("#uploadfile").click(function () {
        $("#file").click();
    });

    // file selected
    $("#file").change(function () {
        var fd = new FormData();

        var files = $('#file')[0].files[0];

        fd.append('file', files);

        uploadData(fd);
    });
});

// Sending AJAX request and upload file
function uploadData(formdata) {

    $.ajax({
        url: 'admin/services/upload.php',
        beforeSend: () => {
            $(".upload-area h4").text("Uploading");
        },
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            $(".upload-area h4").text("Brochure Uploaded and Updated");
            M.toast({
                html: response.name + " is uploaded."
            });
            console.log(response);

        }, error: () => {
            $(".upload-area h4").html("<div class='w-full my-4'><i class='material-icons' style='font-size: 5rem;'>save_alt</i></div><div class='w-full'>Drag and Drop brochure here<br />Or<br />Click to select file</div>");
            $(".upload-area h4").addClass("grey-text");
            $(".upload-area").addClass("grey");
            $(".upload-area").removeClass("green");
            $(".upload-area h4").removeClass("green-text");
            M.toast({
                html: "Upload failed, try again"
            });
            console.log(response);
        }
    });
}
