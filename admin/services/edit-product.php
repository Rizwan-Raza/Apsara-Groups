<?php
$data = array("message" => "Unknown method", "status" => "server_error");
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['title']) and isset($_POST['pid'])) {
    error_reporting(0);
    extract($_POST, EXTR_SKIP);

    if (isset($has_image) and $has_image == "on") {
        if (isset($_FILES['image']['size']) and $_FILES['image']['size'] > 0) {
            $directory = "uploads/products/" . $cat_id . "-" . $_FILES['image']['name'];
            if (!move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $directory)) {
                echo json_encode(array("message" => "File upload failed", "status" => "server_error"));
                return;
            }
        } else {
            $removeImage = true;
        }
    } else {
        $removeImage = true;
    }

    $sql = "UPDATE `products` SET `title`='$title', `_tid` = $cat_id, `description`='$desc'" . (isset($directory) ? ", `image`='$directory', `has_image`=1" : ((isset($removeImage) and $removeImage) ? ", `has_image`=0" : "")) . " WHERE `_pid`=$pid";

    require '../../services/db.inc.php';

    $conn = DB::getConnection();
    $result = $conn->query($sql);

    $numCount = 1;
    $counter = 1;
    $sqlV = "DELETE FROM `varients` WHERE `_pid`=$pid;";
    while ($counter <= $varities) {
        if (isset(${"size_$numCount"}) and isset(${"price_$numCount"}) and isset(${"quantity_$numCount"})) {
            $elem = array("size" => ${"size_$numCount"}, "price" => ${"price_$numCount"}, "quantity" => ${"quantity_$numCount"});
            $sqlV .= "INSERT INTO `varients` (`_pid`, `size`, `price`, `qty`) VALUES($pid, '" . ${"size_$numCount"} . "', " . ${"price_$numCount"} . "," . ${"quantity_$numCount"} . ");";
            $counter++;
        }
        $numCount++;
    }

    if ($result) {
        if (isset($prev_image) and (strlen($prev_image) > 0) and isset($removeImage) and $removeImage) {
            unlink("../../" . $prev_image);
        }

        $result2 = $conn->multi_query($sqlV);
        if ($result2) {
            $data = array("message" => "Product Updated successfully", "status" => "success");
        } else {
            echo $sqlV;
            $data = array("message" => "Product Updated with issues, edit again", "status" => "success");
        }
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
return;
