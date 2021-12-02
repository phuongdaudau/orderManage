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

    <title>Dolly - Nhân viên</title>

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

            <div class="menu">
                <ul class="list">
                    <li class="header">Nội dung</li>
                    <li class="">
                        <a href="dashboard.php">
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
                    <li class="active">
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
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                NHÂN LỰC
                                <span class="badge bg-blue">4</span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Chức vụ</th>
                                            <th>Đánh giá</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Chức vụ</th>
                                            <th>Đánh giá</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php $sql = "SELECT idNV, HoTen, ChucVu, Rate FROM nhanvien";
                                        $result = executeResult($sql);
                                        if ($result > 0) {
                                            $xhtml = '';
                                            foreach ($result as $person) {
                                                $xhtml .= '<tr>
                                                <td>' . $person['idNV']  . '</td>
                                                <td>' . $person['HoTen']  . '</td>
                                                <td>' . $person['ChucVu']  . '</td>
                                                <td>' . $person['Rate']  . '</td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger waves-effect" type="button" onclick="deleteEmployee()">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </td>
                                            </tr>';
                                            }
                                            echo $xhtml;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        function deleteEmployee() {
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'red',
                cancelButtonColor: '##ffffff',
                confirmButtonText: 'Tôi chắc chắn!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Xóa thành công!',
                        'Thông tin nhân viên đã được xóa.',
                        'success'
                    )
                }
            })
        }
    </script>

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