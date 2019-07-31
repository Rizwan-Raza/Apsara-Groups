<?php
$data = array("message" => "Unknown method", "status" => "server_error");
function paddWithQuote($val)
{
    $valTR = "";
    foreach (explode(".", $val) as $parts) {
        $valTR .= "`$parts`";
    }
    $valTR = str_replace("``", "`.`", $valTR);
    return $valTR;
}
function dataFormat($val)
{
    if (is_numeric($val)) {
        return $val;
    } else {
        return "'$val'";
    }
}
// print_r($_REQUEST);
if ($_SERVER['REQUEST_METHOD'] === "GET" and isset($_GET['what'])) {
    error_reporting(0);
    extract($_REQUEST, EXTR_SKIP);
    $sql = "SELECT * FROM " . str_replace("{", "", str_replace("}", "", implode(",", array_map("paddWithQuote", explode(",", $what)))));
    if (isset($filter) and isset($with)) {
        $sql .= " WHERE ";
        $filter = str_replace("{", "", str_replace("}", "", $filter));
        $with = str_replace("{", "", str_replace("}", "", $with));
        $filters = explode(",", $filter);
        $data = explode(",", $with);
        $fCount = count($filters);
        for ($i = 0; $i < $fCount; $i++) {
            if ($filters[$i] == "v" and $data[$i] == -1) {
                continue;
            }
            $sql .= ($i != 0 ? " AND " : "") . paddWithQuote($filters[$i]) . "=" . dataFormat($data[$i]);
        }
    }
    // echo $sql;
    require 'db.inc.php';
    if ($result = DB::getConnection()->query($sql)) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row);
        }
    } else {
        $data = array("message" => "Something went wrong!", "status" => "server_error");
    }
}
echo json_encode($data);
