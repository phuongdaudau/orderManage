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

    <title>Dolly - Đơn hàng</title>
    <div>

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
    </div>
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
            <a class="btn btn-primary waves-effect" href="them_donhang.php">
                <i class="material-icons">add</i>
                <span>TẠO ĐƠN HÀNG</span>
            </a><br>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                ĐƠN HÀNG
                                <span class="badge bg-blue"><?php $sql = "SELECT * FROM donhang";
                                                            $result = executeResult($sql);
                                                            echo count($result); ?></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                    <div class="dt-buttons">
                                        <form action="export.php" method="POST">
                                            <input type="submit" class="dt-button buttons-excel buttons-html5" name="export" value="Excel">
                                        </form>
                                    </div>
                                    <div id="DataTables_Table_1_filter" class="dataTables_filter"><label>Tìm kiếm:<input type="search" id="search" name="search" class="form-control input-sm" placeholder="" aria-controls="DataTables_Table_1"></label></div>
                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="DataTables_Table_1" role="grid" aria-describedby="DataTables_Table_1_info">
                                        <thead>
                                            <tr>
                                                <th>Mã</th>
                                                <th>Tên đơn</th>
                                                <th>Nhân viên</th>
                                                <th>Số sản phẩm</th>
                                                <th>Xác nhận</th>
                                                <th>Tình trạng</th>
                                                <th>Thời gian</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="danhsach">
                                            <?php
                                            $limit = 6;
                                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                            $start = ($page - 1) * $limit;

                                            $sql = "SELECT idDH, Ten, nhanvien.HoTen AS ten, SoLuong, Status, Approved, Time FROM donhang, nhanvien
                                                    where donhang.NhanVien_idNV = nhanvien.idNV LIMIT $start, $limit";
                                            $result = executeResult($sql);

                                            $sql = "SELECT * FROM donhang";
                                            $count = executeResult($sql);


                                            $total = count($count);
                                            $pages = ceil($total / $limit);

                                            $Previous = $page - 1;
                                            $Next = $page + 1;

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
                                            } ?>

                                        </tbody>
                                    </table>

                                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_1_paginate">
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?php if ($page == 1) {
                                                                                    echo "disabled";
                                                                                } ?>" id="DataTables_Table_1_previous">
                                                <a href="don-hang.php?page=<?= $Previous; ?>" aria-controls="DataTables_Table_1">Trước</a>
                                            </li>
                                            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                                <li class="paginate_button <?php if ($i == $page) {
                                                                                echo "active";
                                                                            } ?>">
                                                    <a href="don-hang.php?page=<?= $i; ?>" aria-controls="DataTables_Table_1"><?= $i; ?></a>
                                                </li>
                                            <?php }  ?>

                                            <li class="paginate_button next <?php if ($page == $pages) {
                                                                                echo "disabled";
                                                                            } ?>" id="DataTables_Table_1_next">
                                                <a href="don-hang.php?page=<?= $Next; ?>" aria-controls="DataTables_Table_1">Sau</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        function deleteForm(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn xóa đơn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f91f3',
                cancelButtonColor: '#777',
                confirmButtonText: 'Tôi chắc chắn!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("delete.php", {
                        idDH: id
                    }, function(data) {
                        console.log("vào đến function");
                        console.log(data);
                        if (data == 1) {
                            icon = "success";
                            data = "Đã xóa đơn hàng!";
                        } else {
                            icon = "error";
                            data = "Không thể xoá đơn hàng này!";
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
        $(document).ready(function() {
            $("#search").keyup(function() {
                var key = $("#search").val();
                console.log(key);
                $.post("search.php", {
                    key: key
                }, function(data) {
                    console.log(data);
                    $(".danhsach").html(data);
                });
            })
        })
    </script>

</body>

</html>