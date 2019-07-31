<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['title']) and isset($_POST['price'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    $sql = "INSERT INTO `types` (`title`, `url`, `description`) VALUES('$title', '$url', '$desc')";
    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    if ($result) {
        $data = array("message" => "Category Added successfully", "status" => "success");
    } elseif (strpos($conn->error, "Duplicate entry") === 0) {
        $data = array("message" => "Already Added, try something else", "status" => "duplicate_error");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
