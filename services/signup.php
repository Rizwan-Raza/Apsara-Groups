<?php
header('Access-Control-Allow-Origin: *');
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['name']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['rpassword'])) {
        error_reporting(0);
        extract($_POST, EXTR_SKIP);
            if ($password != $rpassword) {
                echo json_encode(array("message" => "Password Mismatch", "status" => "password_error"));
                return;
            }

        $sql = "INSERT INTO `users`(`name`, `email`, `password`, `number`, `time`) VALUES ('$name','$email','" . md5("*WAMP*" . $password . "*WAMP*") . "', '$number', CONVERT_TZ(CURRENT_TIMESTAMP, '-07:00', '+05:30'))";
        require 'db.inc.php';

        $conn = DB::getConnection();
        if ($conn->query($sql) === true) {
            $data = array("message" => "User registered successfully", "status" => "success");
        } elseif (strpos($conn->error, "Duplicate entry") === 0) {
            $data = array("message" => "Email already exist, try to login.", "status" => "email_error");
        } else {
            $data = array("message" => "Something went wrong! Try again", "status" => "server_error");
        }
    } else {
        $data = array("message" => "Parameters missing", "status" => "server_error");
    }
}
echo json_encode($data);
return;
