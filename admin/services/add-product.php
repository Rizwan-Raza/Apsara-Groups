<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['title']) and isset($_POST['price_1'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    if (isset($has_image) and $has_image == "on" and isset($_FILES['image'])) {
        $directory = "uploads/products/" . $cat_id . "-" . $_FILES['image']['name'];
        if (!move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $directory)) {
            echo json_encode(array("message" => "File upload failed", "status" => "server_error"));
            return;
        }
    }
    $title = htmlspecialchars($title);
    $desc = htmlspecialchars($desc);

    $sql = "INSERT INTO `products` (`title`, `_tid`, `url`, `description`, `image`, `has_image`) VALUES('$title', $cat_id, '$url', '$desc', " . (isset($directory) ? "'$directory', 1" : "NULL, 0") . ")";

    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    $numCount = 1;
    $sqlV = "";
    while ($numCount <= $varities) {
        if (isset(${"size_$numCount"}) and isset(${"price_$numCount"}) and isset(${"quantity_$numCount"})) {
            ${"size_$numCount"} = htmlspecialchars(${"size_$numCount"});
            $elem = array("size" => ${"size_$numCount"}, "price" => ${"price_$numCount"}, "quantity" => ${"quantity_$numCount"});
            $sqlV .= "INSERT INTO `varients` (`_pid`, `size`, `price`, `qty`) VALUES(HERE_GOES_PRODUCT_ID, '" . ${"size_$numCount"} . "', " . ${"price_$numCount"} . "," . ${"quantity_$numCount"} . ");";
            $numCount++;
        }
    }

    if ($result) {
        $sqlV = str_ireplace("HERE_GOES_PRODUCT_ID", $conn->insert_id, $sqlV);
        $result2 = $conn->multi_query($sqlV);
        if ($result2) {
            $data = array("message" => "Product Added successfully", "status" => "success");
        } else {
            $data = array("message" => "Product Added with issues delete it and try again", "status" => "success");
        }
    } elseif (strpos($conn->error, "Duplicate entry") === 0) {
        $data = array("message" => "Already Added, try something else", "status" => "duplicate_error");
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
