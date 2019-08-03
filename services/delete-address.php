<?php
// print_r($_REQUEST);
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    error_reporting(0);

    $sql = "DELETE FROM `addresses` WHERE `_aid`=$_POST[_aid]";

    // $data = array("message" => $sql, "status" => "success");
    // echo json_encode($data);
    // return;
    require 'db.inc.php';
    $conn = DB::getConnection();
    $result = $conn->query($sql);
    if ($result == true) {
        $data = array("message" => "Address Deleted Successfully!", "status" => "success");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
