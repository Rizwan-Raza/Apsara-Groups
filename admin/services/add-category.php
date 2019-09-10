<?php
$data = array("message" => "Unknown method", "status" => "server_error");
// print_r($_REQUEST);
// return;
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['title']) and isset($_POST['parent'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    $sql = "INSERT INTO `types` (`title`, `parent`, `have_child`, `page_title`, `url`, `keywords`, `description`) VALUES('$title',$parent, " . ((isset($have_child) and $have_child == "on") ? "1" : "0") . ", '$ptitle', '$url', '$keywords', '$desc')";
    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    if ($result) {
        $data = array("message" => "Category Added successfully", "status" => "success");
    } elseif (strpos($conn->error, "Duplicate entry") === 0) {
        $data = array("message" => "Already Added, try something else", "status" => "duplicate_error");
    } else {
        $data = array("message" => "Something went wrong!" . $sql, "status" => "server_error");
    }
}
echo json_encode($data);
return;
