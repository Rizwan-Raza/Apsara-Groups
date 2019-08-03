<?php
// print_r($_REQUEST);
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    error_reporting(0);

    $sql = "DELETE FROM `orders` WHERE `_oid`=$_POST[_oid]";

    // $data = array("message" => $sql, "status" => "success");
    // echo json_encode($data);
    // return;
    require 'db.inc.php';
    $conn = DB::getConnection();
    $result = $conn->query($sql);
    if ($result == true) {
        $data = array("message" => "Order Rollbacked Successfully!", "status" => "success");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
