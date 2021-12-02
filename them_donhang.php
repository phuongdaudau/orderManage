<?php
require_once("database/helper.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}

if (isset($_POST['add-btn'])) {
    $title = $_POST['title'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $item = implode(',', $_POST['item']);
    $idSPselected = explode(',', $item);
    $SoLuong = count($idSPselected);
    $status = $_POST['status'];

    $check = "SELECT * FROM khachhang WHERE HoTen = '$name'";
    $exist = executeResult($check);

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

    //lấy idNV đã đăng nhập
    $emailAdmin = $_SESSION['Email'];
    $sql0 = "SELECT idNV FROM nhanvien WHERE Email = '$emailAdmin'";
    $idNV = executeResult($sql0);
    $nv = $idNV[0]['idNV'];

    if ($error) {
        $message = "Thông tin không hợp lệ";
    } else if (count($exist) > 0) {
        //idKH đã có
        $sql = "SELECT idKH FROM khachhang WHERE SĐT = '$phone'";
        $idKH = executeResult($sql);
        $kh = $idKH[0]['idKH'];

        $today = date("Y-m-d");
        $sql1 = "INSERT INTO donhang (idDh, Ten, SoLuong , Status, Approved, Time, NhanVien_idNV, KhachHang_idKH ) VALUES 
                                    (NULL, '$title', '$SoLuong' , '$status','0', '$today', '$nv', '$kh' )";
        $insertDH = execute($sql1);

        $sql2 = "SELECT idDH FROM donhang WHERE Ten = '$title'";
        $idDH = executeResult($sql2);
        $dh = $idDH[0]['idDH'];

        foreach ($idSPselected as $idSp) {
            $sql3 = "INSERT INTO donhang_sanpham (DonHang_idDH, SanPham_idSP) VALUES ('$dh', '$idSp')";
            $insertDHSP = execute($sql3);
        }

        if ($insertDH && $insertDHSP) {
            $_SESSION['add-success'] = true;
        }
        header("Location: don-hang.php");
    } else {
        //thông tin khách hàng chưa có trong db 
        $sql1 = "INSERT INTO khachhang (HoTen, SĐT , email, DiaChi) VALUES ('$name', '$phone' , '$email','$address')";
        $insertKH = execute($sql1);

        $sql = "SELECT idKH FROM khachhang WHERE SĐT = '$phone'";
        $idKH = executeResult($sql);
        $kh = $idKH[0]['idKH'];

        $today = date("Y-m-d");
        $sql1 = "INSERT INTO donhang (Ten, SoLuong , Status, Approved, Time, NhanVien_idNV, KhachHang_idKH ) VALUES 
                                    ('$title', '$SoLuong' , '$status','0', '$today', '$nv', '$kh' )";
        $insertDH = execute($sql1);

        $sql2 = "SELECT idDH FROM donhang WHERE Ten = '$title'";
        $idDH = executeResult($sql2);
        $dh = $idDH[0]['idDH'];

        foreach ($idSPselected as $idSp) {
            $sql3 = "INSERT INTO donhang_sanpham (DonHang_idDH, SanPham_idSP) VALUES ('$dh', '$idSp')";
            $insertDHSP = execute($sql3);
        }

        if ($insertKH && $insertDH && $insertDHSP) {
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

    <title>Dolly - Thêm đơn hàng</title>

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
    <link href="assets/backend/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <script type="text/javascript" src="assets/backend/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#phone').click(function() {
                $.ajax({
                    url: 'database/ajax.php',
                    type: 'POST',
                    data: {
                        'name': $('#info-form [name="name"]').val()
                    },
                    timeout: 3000, // 1s
                    cache: false,
                    dataType: 'json',
                    success: function(data, status) {
                        console.log(data);
                        if (status == 'success') {
                            $.each(data, function(i, value) {
                                var element = '#info-form [name="' + i + '"]';
                                $(element).val(value);
                            })
                        } else {
                            console.log("No data");
                        }
                    }
                })
            });
        });
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
                                        THÊM ĐƠN HÀNG
                                    </h2>
                                </div>
                                <div class="body">
                                    <label for="title">Tên đơn hàng</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tên đơn hàng">
                                        </div>
                                        <span style="color: red;"><?php echo isset($error['title']) ? $error['title'] : ''; ?></span>
                                    </div>
                                    <label for="name">Tên khách hàng</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên khách hàng">
                                        </div>
                                        <span style="color: red;"><?php echo isset($error['name']) ? $error['name'] : ''; ?></span>
                                    </div>
                                    <label for="phone">Số điện thoại</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập SĐT">
                                        </div>
                                        <span style="color: red;"><?php echo isset($error['phone']) ? $error['phone'] : ''; ?></span>
                                    </div>
                                    <label for="phone">Email</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Nhập email">
                                        </div>
                                        <span style="color: red;"><?php echo isset($error['email']) ? $error['email'] : ''; ?></span>
                                    </div>
                                    <label for="address">Địa chỉ</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="address" name="address" class="form-control" placeholder="Nhập địa chỉ">
                                        </div>
                                        <span style="color: red;"><?php echo isset($error['address']) ? $error['address'] : ''; ?></span>
                                    </div>

                                    <div class="form-group form-float">
                                        <div class="form-line ">
                                            <label for="item">Chọn sản phẩm</label>

                                            <select name="item[]" id="item" class="form-control show-tick" multiple>
                                                <?php $sql = "select idSP, Ten from sanpham";
                                                $result = executeResult($sql);
                                                if ($result > 0) {
                                                    $xhtml = '';
                                                    foreach ($result as $item) {
                                                        $xhtml .= '<option value="' . $item['idSP'] . '">' . $item['Ten'] . '</option>';
                                                    }
                                                    echo $xhtml;
                                                } ?>
                                            </select>
                                        </div>
                                        <span style="color: red;"><?php echo isset($error['item']) ? $error['item'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" id="publish" class="filled-in" name="status" value="1">
                                        <label for="publish">Xác nhận</label>
                                    </div>

                                    <a class="btn btn-danger m-t-15 waves-effect" href="#">QUAY LẠI</a>
                                    <input type="submit" value="THÊM" class="btn btn-primary m-t-15 waves-effect add-btn" name="add-btn">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>

        <!-- Jquery Core Js -->
        <script src="assets/backend/plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core Js -->
        <script src="assets/backend/plugins/bootstrap/js/bootstrap.js"></script>

        <!-- Slimscroll Plugin Js -->
        <script src="assets/backend/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

        <!-- Waves Effect Plugin Js -->
        <script src="assets/backend/plugins/node-waves/waves.js"></script>

        <!-- Custom Js -->
        <script src="assets/backend/js/admin.js"></script>


        <!-- Demo Js -->
        <script src="assets/backend/js/demo.js"></script>

        <!-- Jquery CountTo Plugin Js -->
        <script src="assets/backend/plugins/jquery-countto/jquery.countTo.js"></script>

        <!-- Morris Plugin Js -->
        <script src="assets/backend/plugins/raphael/raphael.min.js"></script>
        <script src="assets/backend/plugins/morrisjs/morris.js"></script>

        <!-- ChartJs -->
        <script src="assets/backend/plugins/chartjs/Chart.bundle.js"></script>

        <!-- Flot Charts Plugin Js -->
        <script src="assets/backend/plugins/flot-charts/jquery.flot.js"></script>
        <script src="assets/backend/plugins/flot-charts/jquery.flot.resize.js"></script>
        <script src="assets/backend/plugins/flot-charts/jquery.flot.pie.js"></script>
        <script src="assets/backend/plugins/flot-charts/jquery.flot.categories.js"></script>
        <script src="assets/backend/plugins/flot-charts/jquery.flot.time.js"></script>

        <!-- Sparkline Chart Plugin Js -->
        <script src="assets/backend/plugins/jquery-sparkline/jquery.sparkline.js"></script>
        <script src="assets/backend/js/pages/index.js"></script>
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

        <!-- Select Plugin Js -->
        <script src="assets/backend/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    </div>
</body>

</html>