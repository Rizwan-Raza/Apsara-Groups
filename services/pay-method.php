<?php
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");

session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_REQUEST['order_id']) and isset($_REQUEST['pay_method'])) {
    error_reporting(0);
    extract($_REQUEST, EXTR_SKIP);
    // print_r($_POST);

    $sql = "UPDATE `orders` SET `p_method` = $pay_method WHERE `_oid`=$order_id";

    // echo $sql;
    // return;

    require 'db.inc.php';
    $conn = DB::getConnection();
    if ($conn->query($sql) === true) {
        $data = array("message" => "Product Ordered Successfully!", "status" => "success");
    } else {
        // echo $conn->error;
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
