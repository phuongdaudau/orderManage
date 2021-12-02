<?php
require_once("database/helper.php");
session_start();

if (!isset($_SESSION['Email'])) {
    session_destroy();
    header("Location: login/index.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="assets/favicon.png">

    <title>Dolly - Thống kê</title>

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
        <aside class="sidebar">
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
                    <li class="active">
                        <a href="#">
                            <i class="material-icons">dashboard</i>
                            <span>Thống kê</span>
                        </a>
                    </li>
                    <li class="">
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
                    <li class="">
                        <a href="#">
                            <i class="material-icons">input</i>
                            <span>Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="legal">
                <div class="copyright">
                    &copy; 2021 <a href="#">Cửa hàng Dolly</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
        </aside>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>THỐNG KÊ</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 ">
                    <div class="info-box bg-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">TỔNG ĐƠN HÀNG</div>
                            <div class="number count-to" data-from="0" data-to="<?php $sql = "SELECT * FROM donhang";
                                                                                $result = executeResult($sql);
                                                                                echo count($result); ?>" data-speed="1000" data-fresh-interval="20">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 ">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <div class="content">
                            <div class="text">ĐƠN ĐÃ GIAO</div>
                            <div class="number count-to" data-from="0" data-to="<?php $sql = "SELECT * FROM donhang WHERE Status=4";
                                                                                $result = executeResult($sql);
                                                                                echo count($result); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 ">
                    <div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">trending_up</i>
                        </div>
                        <div class="content">
                            <div class="text">ĐƠN ĐANG GIAO</div>
                            <div class="number count-to" data-from="0" data-to="<?php $sql = "SELECT * FROM donhang WHERE Status=3";
                                                                                $result = executeResult($sql);
                                                                                echo count($result); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 ">
                    <div class="info-box bg-red hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">cancel</i>
                        </div>
                        <div class="content">
                            <div class="text">ĐƠN BỊ HỦY</div>
                            <div class="number count-to" data-from="0" data-to="<?php $sql = "SELECT * FROM donhang WHERE Status=0";
                                                                                $result = executeResult($sql);
                                                                                echo count($result); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>ĐƠN HÀNG MỚI</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã</th>
                                        <th>Tên</th>
                                        <th>Người xác nhận</th>
                                        <th>Số sản phẩm</th>
                                        <th>Tình trạng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sql = "SELECT idDH, Ten, nhanvien.HoTen AS ten, SoLuong, Status FROM donhang, nhanvien
                                                    where donhang.NhanVien_idNV = nhanvien.idNV 
                                                    ORDER BY idDH DESC";
                                    $result = executeResult($sql);
                                    if ($result > 0) {
                                        $i = 1;
                                        $xhtml = '';
                                        foreach ($result as $order) {
                                            $id = $order['idDH'];
                                            $status = $order['Status'];
                                            switch ($status) {
                                                case 0:
                                                    $status = 'Bị hủy';
                                                    break;
                                                case 1:
                                                    $status = 'Lên đơn';
                                                    break;
                                                case 2:
                                                    $status = 'Đóng gói';
                                                    break;
                                                case 3:
                                                    $status = 'Đang giao';
                                                    break;
                                                case 4:
                                                    $status = 'Đã giao';
                                                    break;
                                            }
                                            $xhtml .= '<tr>
                                                        <td>' . $i . '</td>
                                                        <td>' . $id . '</td>
                                                        <td>' . $order['Ten'] . '</td>
                                                        <td>' . $order['ten'] . '</td>
                                                        <td>' . $order['SoLuong'] . '</td>
                                                        <td>' . $status . '</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-primary waves-effect" target="_blank" href="chi-tiet-don.php?idDH=' . $id  . '"">Xem</a>
                                                        </td>
                                                    </tr>';
                                            $i++;
                                        }
                                        echo $xhtml;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <!-- Task Info -->
                <div class=" col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>TOP NHÂN VIÊN</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Tên nhân viên</th>
                                            <th>Chức vụ</th>
                                            <th>Số đơn hàng</th>
                                            <th>Đánh giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT  HoTen, ChucVu, tongDon, Rate
                                                        FROM nhanvien,
                                                            (SELECT NhanVien_idNV, COUNT(idDH) AS tongDon
                                                            FROM donhang
                                                            GROUP BY NhanVien_idNV) AS T
                                                        WHERE nhanvien.idNV = T.NhanVien_idNV
                                                        ORDER BY tongDon DESC";
                                        $result = executeResult($sql);
                                        if ($result > 0) {
                                            $i = 1;
                                            $xhtml = '';
                                            foreach ($result as $info) {

                                                $xhtml .= '<tr>
                                                            <td>' . $i . '</td>
                                                            <td>' . $info['HoTen'] . '</td>
                                                            <td>' . $info['ChucVu'] . '</td>
                                                            <td>' . $info['tongDon'] . '</td>
                                                            <td>' . $info['Rate'] . '</td>
                                                        </tr>';
                                                $i++;
                                            }
                                            echo $xhtml;
                                        } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Task Info -->
            </div>
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
</body>

</html>