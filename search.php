<?php
require_once("database/helper.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}

if (isset($_POST['key'])) {
    $key = trim($_POST['key']);
    $sql = " SELECT idDH, Ten, nhanvien.HoTen AS ten, SoLuong, Status, Approved, Time FROM donhang, nhanvien
    WHERE donhang.NhanVien_idNV = nhanvien.idNV AND (Ten like '%{$key}%') ";
    $result = executeResult($sql);
    if ($result > 0) {
        $xhtml = '';
        $is_approved = '';
        foreach ($result as $order) {

            if ($order['Approved'] == 1) {
                $is_approved = '<span class="badge bg-blue">Xác nhận</span>';
            } else {
                $is_approved = '<span class="badge bg-gray">Pending</span>';
            }

            $status = $order['Status'];
            switch ($status) {
                case 0:
                    $status = '<span class="badge bg-gray">Bị hủy</span>';
                    break;
                case 1:
                    $status = '<span class="badge bg-green">Lên đơn</span>';
                    break;
                case 2:
                    $status = '<span class="badge bg-green">Đóng gói</span>';
                    break;
                case 3:
                    $status = '<span class="badge bg-pink">Đang giao</span>';
                    break;
                case 4:
                    $status = '<span class="badge bg-blue">Đã giao</span>';
                    break;
            }
            $xhtml .= '<tr>
                    <td>' . $order['idDH']  . '</td>
                    <td>' . $order['Ten'] . '</td>
                    <td>' . $order['ten'] . '</td>
                    <td>' . $order['SoLuong'] . '</td>
                    <td>' . $is_approved . '</td>
                    <td>' . $status . '</td>
                    <td>' . $order['Time'] . '</td>
                    <td class="text-center">
                        <a href="chi-tiet-don.php?idDH=' . $order['idDH']  . '" class="btn btn-success waves-effect">
                            <i class="material-icons">visibility</i>
                        </a>
                        <a href="edit_donhang.php?idDH=' . $order['idDH']  . ' " class="btn btn-info waves-effect">
                            <i class="material-icons">edit</i>
                        </a>
                        <button class="btn btn-danger waves-effect" type="button" onclick="deleteForm(' . $order['idDH']  . ')">
                            <i class="material-icons">delete</i>
                        </button>
                    </td>
                </tr>';
        }
        echo $xhtml;
    }
}
