<?php
header('Access-Control-Allow-Origin: *');
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");
session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_SESSION['u_id'])) {
    if (isset($_POST['rname']) and isset($_POST['rnumber']) and isset($_POST['tag']) and isset($_POST['address'])) {
        error_reporting(0);
        extract($_POST, EXTR_SKIP);

        $sql = "INSERT INTO `addresses`(`_uid`, `name`, `number`, `tag`, `address`, `landmark`, `pincode`, `time`) VALUES ($_SESSION[u_id], '$rname','$rnumber', $tag, '$address', '$landmark', '$pincode', CONVERT_TZ(CURRENT_TIMESTAMP, '-07:00', '+05:30'))";
        require 'db.inc.php';

        $conn = DB::getConnection();
        if ($conn->query($sql) === true) {
            $data = array("message" => "Address Added successfully", "status" => "success");
        } else {
            $data = array("message" => "Something went wrong! Try again", "status" => "server_error");
        }
    } else {
        $data = array("message" => "Parameters missing", "status" => "server_error");
    }
}
echo json_encode($data);
return;
