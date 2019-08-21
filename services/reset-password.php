<?php
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");

session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_REQUEST['uid']) and isset($_REQUEST['new_password']) and isset($_REQUEST['new_rpassword'])) {
    error_reporting(0);
    extract($_REQUEST, EXTR_SKIP);
    // print_r($_POST);

    if ($new_password != $new_rpassword) {
        echo json_encode(array("message" => "Password Mismatch!", "status" => "password_error"));
        return;
    }

    $sql = "UPDATE `users` SET `password` = '" . md5("*WAMP*" . $new_password . "*WAMP*") . "' WHERE `_uid`=$uid";

    // echo $sql;
    // return;

    require 'db.inc.php';
    $conn = DB::getConnection();
    if ($conn->query($sql) === true) {
        $_SESSION['u_' . $what] = $$what;
        $data = array("message" => "Password reset Successfully!", "status" => "success");
    } else {
        // echo $conn->error;
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
