<?php
require_once("database/helper.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}

if (isset($_POST['idDH'])) {
    $id = $_POST['idDH'];
    $sql = "SELECT Ten FROM donhang WHERE idDH = '$id' AND Status = 1";
    $result = executeResult($sql);
    $name = $result[0]['Ten'];
    if (count($result) < 0) {
        echo 0;
    } else {
        $sql1 = "DELETE FROM donhang_sanpham WHERE DonHang_idDH = '$id'";
        $res = execute($sql1);
        $sql2 = "DELETE FROM donhang WHERE Ten = '$name'";
        $result = execute($sql2);
        echo $result;
    }
}
