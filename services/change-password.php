<?php
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");

session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_SESSION['u_id']) and isset($_REQUEST['newpassword'])) {
    error_reporting(0);
    extract($_REQUEST, EXTR_SKIP);
    // print_r($_POST);

    if ($newpassword != $rnewpassword) {
        echo json_encode(array("message" => "Password Mismatch", "status" => "new_password_error"));
        return;
    }

    $sql = "SELECT `password` FROM `users` WHERE `password`='" . md5("*WAMP*" . $oldpassword . "*WAMP*") . "' AND `_uid`=$_SESSION[u_id]";

    // echo $sql;
    // return;

    require 'db.inc.php';
    $conn = DB::getConnection();
    if ($conn->query($sql)->num_rows > 0) {
        $sql = "UPDATE `users` SET `password` = '" . md5("*WAMP*" . $newpassword . "*WAMP*") . "' WHERE `_uid`=$_SESSION[u_id]";

        if ($conn->query($sql) === true) {
            $data = array("message" => "Password changed Successfully!", "status" => "success");
        } else {
            $data = array("message" => "Something went wrong", "status" => "server_error");
        }
    } else {
        // echo $conn->error;
        $data = array("message" => "Old Password is incorrect", "status" => "old_password_error");
    }
}
echo json_encode($data);
return;
