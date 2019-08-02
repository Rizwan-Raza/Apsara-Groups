<?php
header('Access-Control-Allow-Origin: *');
print_r($_REQUEST);
return;
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['sname']) and isset($_POST['email']) and isset($_POST['password']) and isset($_POST['address'])) {
        error_reporting(0);
        extract($_POST, EXTR_SKIP);

        $sql = "INSERT INTO `users`(`name`, `email`, `number`, `password`, `time`) VALUES ('$sname','$email', '$snumber', '" . md5("*WAMP*" . $password . "*WAMP*") . "', CONVERT_TZ(CURRENT_TIMESTAMP, '-07:00', '+05:30'))";
        require 'db.inc.php';


        $conn = DB::getConnection();
        if ($conn->query($sql) === true) {
            $uid = $conn->insert_id;
            $sql = "INSERT INTO `addresses`(`_uid`, `name`, `number`, `address`, `landmark`, `pincode`) VALUES($uid, '$rname', '$rnumber', '$address', '$landmark', '$pincode')";
            if ($conn->query($sql)) {
                $aid = $conn->insert_id;
                $sql = "INSERT INTO `orders` (`_uid`, `_aid`, `is_guest`) VALUES($uid, $aid, 1)";
                if ($conn->query($sql)) {
                    $data = array("message" => "Order added successfully", "status" => "success", "oid" => $conn->insert_id);
                } else {
                    $data = array("message" => "Something went wrong, try to login", "status" => "order_error");
                }
            } else {
                $data = array("message" => "Something went wrong, try to login", "status" => "address_error");
            }
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
