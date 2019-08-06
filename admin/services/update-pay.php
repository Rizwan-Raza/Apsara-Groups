<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['status']) and isset($_POST['oid'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    $sql = "UPDATE `orders` SET `paid` = '$status' WHERE `_oid`=$oid";
    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    if ($result) {
        $data = array("message" => "Order Payment updated successfully", "status" => "success");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
