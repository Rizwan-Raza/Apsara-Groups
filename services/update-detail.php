<?php
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");

session_start();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_SESSION['u_id']) and isset($_REQUEST['what'])) {
    error_reporting(0);
    extract($_REQUEST, EXTR_SKIP);
    // print_r($_POST);

    $sql = "UPDATE `users` SET `$what` = '" . $$what . "' WHERE `_uid`=$_SESSION[u_id]";

    // echo $sql;
    // return;

    require 'db.inc.php';
    $conn = DB::getConnection();
    if ($conn->query($sql) === true) {
        $_SESSION['u_' . $what] = $$what;
        $data = array("message" => "Profile Updated Successfully!", "status" => "success");
    } else {
        // echo $conn->error;
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
