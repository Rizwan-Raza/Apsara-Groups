<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['hash']) and isset($_POST['ig_id'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    $a = file_put_contents("../../services/hash.txt", $hash);
    $b = file_put_contents("../../services/ig_id.txt", $ig_id);

    if ($a == strlen($hash) and $b == strlen($ig_id)) {
        $data = array("message" => "Keys Updated successfully", "status" => "success");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
