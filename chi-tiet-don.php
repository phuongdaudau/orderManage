<?php
require_once("database/helper.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}
$idDh = $_GET['idDH'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="assets/favicon.png">

    <title>Dolly - Chi tiết đơn hàng</title>

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
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
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
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
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
            <!-- #User Info -->
            <!-- Menu -->
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
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2021 <a href="javascript:void(0);">Cửa hàng Dolly</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php $sql = "SELECT Status, Approved FROM donhang WHERE idDH ='$idDh'";
            $result = executeResult($sql);
            $step = $result[0]['Status'];
            $approve =  $result[0]['Approved']; ?>

            <a href="don-hang.php" class="btn btn-danger waves-effect">Quay lại</a>
            <?php $button = '';
            if ($approve == 1) {
                $button = '<button type="button" class="btn btn-success pull-right disabled">
                                    <i class="material-icons">done</i>
                                    <span>Đã xác nhận</span>
                                </button>';
            } else {
                $button = '<button type="button" class="btn btn-success pull-right" onclick="approved(' . $idDh . ')">
                                    <i class="material-icons">done</i>
                                    <span>Chưa xác nhận</span>
                                </button>';
            }
            echo $button;
            ?>
            <br> <br> <br>
            <div class="card card-timeline">
                <br>

                <?php
                $xhtml = '';
                switch ($step) {
                    case 1:
                        $xhtml = '<h3 class="text-center">ĐÃ LÊN ĐƠN</h3>
                                    <ul class="bs4-order-tracking">
                                    <li class="step active">
                                    <div><i class="fa fa-user"></i></div> Lên đơn
                                </li>
                                <li class="step ">
                                    <div><i class="fa fa-user"></i></div> Gói hàng
                                </li>
                                <li class="step ">
                                    <div><i class="fa fa-truck"></i></div> Giao cho đơn vị vận chuyển
                                </li>
                                <li class="step ">
                                    <div><i class="fa fa-birthday-cake"></i></div> Giao hàng thành công
                                </li>
                                    </ul>';
                        break;
                    case 2:
                        $xhtml = '<h3 class="text-center">ĐÃ GÓI HÀNG</h3>
                                    <ul class="bs4-order-tracking">
                                    <li class="step active">
                                    <div><i class="fa fa-user"></i></div> Lên đơn
                                </li>
                                <li class="step active">
                                    <div><i class="fa fa-user"></i></div> Gói hàng
                                </li>
                                <li class="step ">
                                    <div><i class="fa fa-truck"></i></div> Giao cho đơn vị vận chuyển
                                </li>
                                <li class="step ">
                                    <div><i class="fa fa-birthday-cake"></i></div> Giao hàng thành công
                                </li>
                                    </ul>';
                        break;
                    case 3:
                        $xhtml = '<h3 class="text-center">ĐANG GIAO HÀNG</h3>
                                    <ul class="bs4-order-tracking">
                                    <li class="step active">
                                    <div><i class="fa fa-user"></i></div> Lên đơn
                                </li>
                                <li class="step active">
                                    <div><i class="fa fa-user"></i></div> Gói hàng
                                </li>
                                <li class="step active">
                                    <div><i class="fa fa-truck"></i></div> Giao cho đơn vị vận chuyển
                                </li>
                                <li class="step ">
                                    <div><i class="fa fa-birthday-cake"></i></div> Giao hàng thành công
                                </li>
                                    </ul>';
                        break;
                    case 4:
                        $xhtml = '<h3 class="text-center">ĐÃ GIAO HÀNG</h3>
                                    <ul class="bs4-order-tracking">
                                        <li class="step active">
                                            <div><i class="fa fa-user"></i></div> Lên đơn
                                        </li>
                                        <li class="step active">
                                            <div><i class="fa fa-user"></i></div> Gói hàng
                                        </li>
                                        <li class="step active">
                                            <div><i class="fa fa-truck"></i></div> Giao cho đơn vị vận chuyển
                                        </li>
                                        <li class="step active">
                                            <div><i class="fa fa-birthday-cake"></i></div> Giao hàng thành công
                                        </li>
                                    </ul>';
                        break;
                }
                echo $xhtml;
                ?>

                <br>
            </div>
            <div class="container-fluid">
                <div class="card">
                    <div class="header text-center">
                        <h2>
                            THÔNG TIN ĐƠN HÀNG
                        </h2>
                    </div>
                    <div class="body">
                        <div class="card">

                            <?php $sql = "SELECT Hoten, SĐT, DiaChi FROM khachhang, donhang WHERE donhang.NhanVien_idNV = khachhang.idKH
                                          AND donhang.idDH = '$idDh'";
                            $result = executeResult($sql);
                            // echo '<pre>';
                            // print_r($result);
                            // echo '</pre>';
                            ?>
                            <div class="header bg-cyan">
                                <h2>
                                    Địa chỉ người nhận
                                </h2>
                            </div>
                            <div class="body">
                                <p><strong><?php echo $result[0]['Hoten'] ?>, SĐT: <?php echo $result[0]['SĐT'] ?></strong></p>
                                <p>Địa chỉ: <?php echo $result[0]['DiaChi'] ?></p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="header bg-green">
                                <h2>
                                    Danh sách sản phẩm
                                </h2>
                            </div>
                            <div class="body" style="display: flex;">
                                <?php $sql = "SELECT s.ten, s.Image, s.Gia FROM donhang AS h, donhang_sanpham AS d, sanpham AS s
                                            WHERE  h.idDH = d.DonHang_idDH AND s.idSP = d.SanPham_idSP AND idDH = '$idDh'";
                                $result = executeResult($sql);
                                $xhtml = '';
                                $tong = 0;
                                foreach ($result as $item) {
                                    $xhtml .= '<div class="body">
                                                            <img class="img-responsive thumbnail" src="assets/backend/images/cart-page/' . $item['Image'] . '" alt="">
                                                            <div class="text-center">
                                                                <strong>' . $item['ten'] . '</strong>
                                                                <p>' . number_format($item['Gia']) . 'đ</p>
                                                                <p>x1</p>
                                                            </div>
                                                        </div>';
                                    $tong += $item['Gia'];
                                }
                                echo $xhtml;
                                ?>
                            </div>
                        </div>
                        <div class="card">
                            <div class="header bg-amber">
                                <h2>
                                    Số tiền thanh toán
                                </h2>
                            </div>
                            <div class="body">
                                <p>Tổng tiền hàng: <?php echo  number_format($tong) ?>đ</p>
                                <p>Voucher: -50.000đ</p>
                                <p>Phí vận chuyển: 30.000đ</p>
                                <p><strong>THÀNH TIỀN: <?php echo  number_format($tong - 50000 + 30000) ?>đ</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
        </div>
    </section>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function approved(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn xác nhận đơn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#blue',
                cancelButtonColor: '##ffffff',
                confirmButtonText: 'Tôi chắc chắn!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("approve.php", {
                        idDH: id
                    }, function(data) {
                        if (data === "1") {
                            icon = "success";
                            data = "Xác nhận đơn hàng thành công!";
                        }
                        Swal.fire({
                            title: data,
                            icon: icon,
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setInterval('location.reload()', 2000);
                    })
                }
            })
        }
    </script>
</body>

</html>