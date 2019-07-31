<?php
if (isset($_REQUEST['log']) and $_REQUEST['log'] == true) {
    session_start();
    unset($_SESSION["u_id"]);
    unset($_SESSION["u_name"]);
    unset($_SESSION["u_email"]);
}
header("Location: " . $_SERVER['HTTP_REFERER']);
