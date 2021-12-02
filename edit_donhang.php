<?php
require_once("database/helper.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}

if (isset($_POST['add-btn'])) {
    $dh = $_POST['idDH'];
    $title = $_POST['title'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $item = implode(',', $_POST['item']);
    $idSPselected = explode(',', $item);
    $SoLuong = count($idSPselected);
    $status = $_POST['status'];

    $error = [];
    if (empty($title)) {
        $error['title'] = "Bạn chưa nhập tên đơn hàng ";
    }
    if (empty($name)) {
        $error['name'] = "Bạn chưa nhập tên khách hàng";
    }
    if (empty($email)) {
        $error['email'] = "Bạn chưa nhập email";
    } else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
        $error['email'] = "Email không đúng định dạng";
    }
    if (empty($phone)) {
        $error['phone'] = "Bạn chưa nhập số điện thoại";
    } else if (!preg_match("/^[0-9]+$/", $phone)) {
        $error['phone'] = "Số điện thoại không hợp lệ";
    }
    if (empty($address)) {
        $error['address'] = "Bạn chưa nhập địa chỉ";
    }
    if ($SoLuong < 0) {
        $error['item'] = "Bạn chưa chọn sản phẩm";
    }
    //lấy id của KH tương ứng vs đơn
    $sql = "SELECT k.idKH FROM donhang AS d,  khachhang AS k WHERE d.idDH = '$dh' AND d.KhachHang_idKH = k.idKH";
    $result = executeResult($sql);
    $kh = $result[0]['idKH'];

    //lấy idNV đã đăng nhập
    $emailAdmin = $_SESSION['Email'];
    $sql0 = "SELECT idNV FROM nhanvien WHERE Email = '$emailAdmin'";
    $idNV = executeResult($sql0);
    $nv = $idNV[0]['idNV'];

    if ($error) {
        $message = "Thông tin không hợp lệ";
    } else if (count($result) > 0) {
        $sql1 = "UPDATE khachhang SET HoTen = '$name', SĐT = '$phone' , email = '$email', DiaChi = '$address' WHERE idKH = '$kh'";
        echo $updateKH = execute($sql1);

        $today = date("Y-m-d");
        $sql2 = "UPDATE donhang SET  Ten = '$title' , SoLuong = '$SoLuong' , Status = '$state' , Approved = '1', Time = '$today', NhanVien_idNV = '$nv', KhachHang_idKH = '$kh' WHERE idDH = '$dh'";
        echo $updateDH = execute($sql2);

        $sql4 = "SELECT d.id FROM donhang AS h, donhang_sanpham AS d WHERE  h.idDH = d.DonHang_idDH AND DonHang_idDH = '$dh'";
        $idDHSP = executeResult($sql4);
        if (count($idDHSP) == count($idSPselected)) {
            foreach ($idDHSP as $id) {
                $x = $id['id'];
                $idSp = pos($idSPselected);
                next($idSPselected);
                $sql3 = "UPDATE donhang_sanpham SET SanPham_idSP = '$idSp' WHERE id = '$x'";
                $updateDHSP = execute($sql3);
            }
        } elseif (count($idDHSP) < count($idSPselected)) {
            $length = count($idDHSP);
            $arr1 = array_slice($idSPselected, 0, $length);
            $arr2 = array_slice($idSPselected, $length);
            foreach ($arr1 as $id) {
                $x = $id['id'];
                $idSp = pos($idSPselected);
                next($idSPselected);
                $sql3 = "UPDATE donhang_sanpham SET SanPham_idSP = '$idSp' WHERE id = '$x'";
                $updateDHSP = execute($sql3);
            }
            foreach ($arr2 as $idItem) {
                $sql5 = "INSERT INTO donhang_sanpham (DonHang_idDH, SanPham_idSP) VALUE('$dh', '$idItem')";
                $updateDHSP = execute($sql5);
            }
        } else {
            $length = count($idSPselected);
            $arr1 = array_slice($idDHSP, 0, $length);
            $arr2 = array_slice($idDHSP, $length);
            foreach ($arr1 as $id) {
                $x = $id['id'];
                $idSp = pos($idSPselected);
                next($idSPselected);
                $sql3 = "UPDATE donhang_sanpham SET SanPham_idSP = '$idSp' WHERE id = '$x'";
                $updateDHSP = execute($sql3);
            }
            foreach ($arr2 as $idItem) {
                $idx = $id['id'];
                $sql5 = "DELETE FROM donhang_sanpham WHERE id = '$idx'";
                $updateDHSP = execute($sql5);
            }
        }


        if ($updateKH && $updateDH && $updateDHSP) {
            $_SESSION['add-success'] = true;
        }
        header("Location: don-hang.php");
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="assets/favicon.png">

    <title>Dolly - Chỉnh sửa đơn hàng</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" />

    <!-- Bootstrap Core Css -->
    <link href="assets/backend/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="assets/backend/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="assets/backend/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="assets/backend/css/style.css" rel="stylesheet">

    <!--theme -->
    <link href="assets/backend/css/themes/all-themes.css" rel="stylesheet" />

    <link href="assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="assets/backend/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />ư

    <script type="text/javascript" src="assets/backend/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
    </script>
</head>

<body class="theme-blue">
    <div class="search-bar">
        <form action="">
            <div class="search-icon">
                <i class="material-icons">search</i>
            </div>
            <input type="text" name="query" placeholder="Nhập từ khóa...">
            <div class="close-search">
                <i class="material-icons">close</i>
            </div>
        </form>
    </div>

    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">Quản lý đơn hàng</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">

                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>

                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <aside id="leftsidebar" class="sidebar">
            <div class="user-info">
                <div class="image">
                    <img src="assets/avatar.jpg" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <?php
                    $email = $_SESSION['Email'];
                    $sql = "SELECT HoTen from nhanvien where Email = '$email' ";
                    $res = executeResult($sql);
                    ?>
                    <div class="name"><?php echo $res[0]['HoTen']; ?></div>
                    <div class="email"><?php echo $email; ?></div>
                </div>
            </div>
            <div class="menu">
                <ul class="list">
                    <li class="header">Nội dung</li>
                    <li class="">
                        <a href="dashboard.php">
                            <i class="material-icons">dashboard</i>
                            <span>Thống kê</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="don-hang.php">
                            <i class="material-icons">library_books</i>
                            <span>Đơn hàng</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="nhan-vien.php">
                            <i class="material-icons">account_circle</i>
                            <span>Nhân viên</span>
                        </a>
                    </li>
                    <li class="header">Hệ thống</li>

                    <li class="">
                        <a href="#">
                            <i class="material-icons">settings</i>
                            <span>Cài đặt</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="material-icons">input</i><span>Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="legal">
                <div class="copyright">
                    &copy; 2021 <a href="javascript:void(0);">Cửa hàng Dolly</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
        </aside>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="info-form" id="info-form" method="POST">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row clearfix">
                            <div class="card">
                                <div class="header">
                                    <h2>
                                        CHỈNH SỬA ĐƠN HÀNG
                                        <?php
                                        $idDh = $_GET['idDH'];
                                        $sql = "SELECT d.idDh, d.Ten, d.Status, k.HoTen, k.SĐT, k.Email, k.DiaChi
                                                        FROM donhang AS d,  khachhang AS k WHERE d.idDH = '$idDh' AND d.KhachHang_idKH = k.idKH";
                                        $result = executeResult($sql);
                                        $res = $result[0]; ?>
                                    </h2>
                                </div>
                                <div class="body">
                                    <label for="title">Tên đơn hàng</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tên đơn hàng" value="<?php echo $res['Ten'] ?>">
                                        </div>
                                    </div>
                                    <label for="name">Tên khách hàng</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên khách hàng " value="<?php echo $res['HoTen'] ?>">
                                        </div>
                                    </div>
                                    <label for="phone">Số điện thoại</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập SĐT" value="<?php echo $res['SĐT'] ?>">
                                        </div>
                                    </div>
                                    <label for="phone">Email</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Nhập email" value="<?php echo $res['Email'] ?>">
                                        </div>
                                    </div>
                                    <label for="address">Địa chỉ</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="address" name="address" class="form-control" placeholder="Nhập địa chỉ" value="<?php echo $res['DiaChi'] ?>">
                                        </div>
                                    </div>

                                    <div class="form-group form-float">
                                        <div class="form-line ">
                                            <label for="state">Tình trạng đơn hàng</label>

                                            <select name="state" id="state" class="form-control show-tick">
                                                <?php
                                                $arr = ['0' => 'Bị hủy', '1' => 'Lên đơn', '2' => 'Đóng gói', '3' => 'Đang giao', '4' => 'Đã giao'];
                                                $xhtml = '';
                                                $status = $res['Status'];
                                                foreach ($arr as $key => $value)
                                                    if ($key == $status) {
                                                        $xhtml .= '<option value="' . $status . '" selected>' . $arr[$status] . '</option>';
                                                    } else {
                                                        $xhtml .= '<option value="' . $key . '">' . $value . '</option>';
                                                    }
                                                echo $xhtml;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-group form-float">
                                            <div class="form-line ">
                                                <label for="item">Chọn sản phẩm</label>

                                                <select name="item[]" id="item" class="form-control show-tick" multiple>
                                                    <?php $sql = "select idSP, Ten from sanpham";
                                                    $result = executeResult($sql);
                                                    $sql1 = "SELECT s.idSP, s.Ten
                                                                FROM donhang AS h, donhang_sanpham AS d, sanpham AS s
                                                                WHERE  h.idDH = d.DonHang_idDH AND s.idSP = d.SanPham_idSP AND idDH = '$idDh'";
                                                    $selected = executeResult($sql1);
                                                    if ($result > 0) {
                                                        $xhtml = '';
                                                        foreach ($result as $item) {
                                                            if (in_array($item, $selected)) {
                                                                $xhtml .= '<option value="' . $item['idSP'] . '" selected>' . $item['Ten'] . '</option>';
                                                            } else {
                                                                $xhtml .= '<option value="' . $item['idSP'] . '">' . $item['Ten'] . '</option>';
                                                            }
                                                        }
                                                        echo $xhtml;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" id="publish" class="filled-in" name="status" value="<?php echo $res['Status'] ?>" checked>
                                            <label for="publish">Xác nhận</label>
                                        </div>
                                        <input type="hidden" id="idDH" name="idDH" value="<?php echo $res['idDh'] ?>">

                                        <a class="btn btn-danger m-t-15 waves-effect" href="#">QUAY LẠI</a>
                                        <input type="submit" value="LƯU" class="btn btn-primary m-t-15 waves-effect add-btn" name="add-btn">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>

    <!-- Jquery Core Js -->
    <script async="" src="https://www.google-analytics.com/analytics.js"></script>
    <script src="assets/backend/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="assets/backend/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="assets/backend/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="assets/backend/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="assets/backend/plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    <script src="assets/backend/js/admin.js"></script>
    <script src="assets/backend/js/pages/tables/jquery-datatable.js"></script>

    <!-- Demo Js -->
    <script src="assets/backend/js/demo.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="assets/backend/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <script src="assets/backend/js/pages/tables/jquery-datatable.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>