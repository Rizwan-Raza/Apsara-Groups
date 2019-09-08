<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['title']) and isset($_POST['tid'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    $sql = "UPDATE `types` SET `title` = '$title', `page_title` = '$ptitle', `keywords` = '$keywords', " . ((isset($url) and (strlen($url) > 0)) ? "`url`= '$url', " : "") . " `description` = '$desc' WHERE `_tid`=$tid";
    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    if ($result) {
        $data = array("message" => "Category Updated successfully", "status" => "success");
    } elseif (strpos($conn->error, "Duplicate entry") === 0) {
        $data = array("message" => "Already Added, try something else", "status" => "duplicate_error");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error", "debug" => $sql);
    }
}
echo json_encode($data);
return;
