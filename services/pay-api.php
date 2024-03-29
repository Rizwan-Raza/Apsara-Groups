<?php
require("src/Instamojo.php");
require 'db.inc.php';
$conn = DB::getConnection();
session_start();

$purpose = $_REQUEST['purpose'];
$name = $_SESSION['u_name'];
$email = $_SESSION['u_email'];
$phone = $_SESSION['u_number'];
$amount = $_REQUEST['amount'];
// TODO: Update Instamojo Merchant ID with valid one
$api = new Instamojo\Instamojo("130191b5fe64c71741350c7758d8cb20", "cf815c6b1d8ad8fa9598c5fd5561155b");

try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => $purpose,
        "buyer_name" => $name,
        "amount" => $amount,
        "phone" => $phone,
        "send_email" => true,
        "send_sms" => true,
        "email" => $email,
        "redirect_url" => "https://www.apsarafoods.in/thanks"
    ));
    $id = $conn->real_escape_string($response['id']);
    # print_r($response);
    $sql = "UPDATE `orders` SET `paid`=1 WHERE `_oid`=$oid";
    $conn->query($sql);
    header("Location:$response[longurl]");
} catch (Exception $e) {
    print('Error: ' . $e->getMessage());
}
