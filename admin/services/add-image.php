<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_FILES['image']) and isset($_FILES['image']['name'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    $directory = "uploads/gallery/" . $_FILES['image']['name'];
    if (!move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $directory)) {
        echo json_encode(array("message" => "Image Upload Failed", "status" => "server_error"));
        return;
    }

    $sql = "INSERT INTO `gallery` (`image`) VALUES('$directory')";

    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    if ($result) {
        $data = array("message" => "Image Added successfully", "status" => "success");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
