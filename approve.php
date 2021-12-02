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
    $sql = "UPDATE donhang SET Approved = '1' WHERE idDH = '$id'";
    $result = execute($sql);
    echo $result;
}
