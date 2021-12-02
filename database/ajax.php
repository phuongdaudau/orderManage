<?php
require_once("helper.php");

$name = $_POST['name'];

$check = "SELECT SĐT,Email,DiaChi FROM khachhang WHERE HoTen = '$name'";
$exist = executeResult($check);
$message = [];

$message['phone'] = $exist[0]['SĐT'];
$message['email'] = $exist[0]['Email'];
$message['address'] = $exist[0]['DiaChi'];

echo json_encode($message);
