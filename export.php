<?php
require_once("database/helper.php");
require_once("Classes/PHPExcel.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}

if (isset($_POST['export'])) {
    $xls = new PHPExcel;
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet()->setTitle("orderList");

    $rowCount = 1;
    $sheet->setCellValue('A' . $rowCount, 'STT');
    $sheet->setCellValue('B' . $rowCount, 'Mã đơn');
    $sheet->setCellValue('D' . $rowCount, 'Tên đơn');
    $sheet->setCellValue('E' . $rowCount, 'Nhân viên phụ trách');
    $sheet->setCellValue('C' . $rowCount, 'Số sản phẩm');
    $sheet->setCellValue('F' . $rowCount, 'Tình trạng');
    $sheet->setCellValue('G' . $rowCount, 'Thời gian');

    $sql = "SELECT idDH, Ten, nhanvien.HoTen AS ten, SoLuong, Status, Approved, Time FROM donhang, nhanvien WHERE donhang.NhanVien_idNV = nhanvien.idNV";
    $result = executeResult($sql);
    if (count($result) > 0) {
        foreach ($result as $oder) {
            $rowCount++;
            $sheet->setCellValue('A' . $rowCount, $rowCount - 1);
            $sheet->setCellValue('B' . $rowCount, $oder['idDH']);
            $sheet->setCellValue('D' . $rowCount, $oder['Ten']);
            $sheet->setCellValue('E' . $rowCount, $oder['ten']);
            $sheet->setCellValue('C' . $rowCount, $oder['SoLuong']);
            $sheet->setCellValue('F' . $rowCount, $oder['Status']);
            $sheet->setCellValue('G' . $rowCount, $oder['Time']);
        }
        $objWriter = new PHPExcel_Writer_Excel2007($xls);
        $filename = 'oderList.xlsx';
        $objWriter->save($filename);

        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.ms-excel');

        readfile($filename);
        return;
    }
}
