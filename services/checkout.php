<?php
header('Access-Control-Allow-Origin: *');
// print_r($_REQUEST);
// return;
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['type']) and isset($_POST['cart'])) {
        error_reporting(0);
        extract($_POST, EXTR_SKIP);

        session_start();
        require 'db.inc.php';
        $conn = DB::getConnection();
        $uid = $_SESSION['u_id'];
        if ($type == "new") {
            $sql = "INSERT INTO `addresses`(`_uid`, `name`, `number`, `address`, `landmark`, `pincode`) VALUES($uid, '$rname', '$rnumber', '$address', '$landmark', '$pincode')";
            if (!($conn->query($sql))) {
                echo json_encode(array("message" => "Something went wrong, try to login", "status" => "address_error"));
                return;
            } else {
                $add_id = $conn->insert_id;
            }
        }
        $aid = $add_id;
        if (isset($aid)) {
            $sql = "INSERT INTO `orders` (`_uid`, `_aid`, `time`) VALUES($uid, $aid, , CONVERT_TZ(CURRENT_TIMESTAMP, '+00:00', '+05:30'))";
            if ($conn->query($sql)) {
                $oid = $conn->insert_id;
                $sql = "";
                foreach ($cart as $item) {
                    $sql .= "INSERT INTO `order_items` (`_oid`, `_pid`, `_vid`, `p_qty`) VALUES($oid, $item[id], $item[v], $item[qty]);";
                }
                if ($conn->multi_query($sql)) {
                    $data = array("message" => "Order added successfully", "status" => "success", "oid" => $oid);
                } else {
                    // echo $sql;
                    $data = array("message" => "Something went wrong, try to login", "status" => "item_error", "oid" => $oid);
                }
            } else {
                $data = array("message" => "Something went wrong, try to login", "status" => "order_error");
            }
        } else {
            $data = array("message" => "Something went wrong, try to login", "status" => "address_error");
        }
    } else {
        $data = array("message" => "Parameters missing", "status" => "server_error");
    }
}
echo json_encode($data);
return;
