<?php
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");

session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_REQUEST['aid']) and isset($_REQUEST['address'])) {
    error_reporting(0);
    extract($_REQUEST, EXTR_SKIP);
    // print_r($_POST);

    $sql = "UPDATE `addresses` SET `name` = '$rname', `number` = '$rnumber', `address` = '$address', `landmark` = '$landmark', `pincode` = '$pincode' WHERE `_aid`=$_REQUEST[aid]";

    // echo $sql;
    // return;

    require 'db.inc.php';
    $conn = DB::getConnection();
    if ($conn->query($sql) === true) {
        $data = array("message" => "Address Updated Successfully!", "status" => "success");
    } else {
        // echo $conn->error;
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
