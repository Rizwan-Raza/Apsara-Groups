<?php
header('Access-Control-Allow-Origin: *');
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['email']) and isset($_POST['password'])) {
        error_reporting(0);
        extract($_POST, EXTR_SKIP);

        $sql = "SELECT * FROM `users` WHERE `email`='$email' AND `password`='" . md5("*WAMP*" . $password . "*WAMP*") . "'";
        require 'db.inc.php';

        $conn = DB::getConnection();
        $result = $conn->query($sql);
        if ($result != false) {
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                session_start();
                $_SESSION['u_id'] = $user['_uid'];
                $_SESSION['u_name'] = $user['name'];
                $_SESSION['u_email'] = $user['email'];
                $data = array("message" => "Logged in successfully", "status" => "success", "user" => json_encode($user));
            } else {
                $data = array("message" => "Email and Password combination mismatch!", "status" => "pass_error");
            }
        } else {
            $data = array("message" => "Something went wrong! Try again", "status" => "server_error");
        }
    } else {
        $data = array("message" => "Parameters missing", "status" => "server_error");
    }
}
echo json_encode($data);
return;
