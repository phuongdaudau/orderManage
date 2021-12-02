<?php
require_once("../database/helper.php");
session_start();

if (isset($_SESSION['Email'])) {
	header("Location: ../dashboard.php");
	die();
}
if (isset($_POST["login"])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$sql = "SELECT * FROM nhanvien WHERE Email = '$email' LIMIT 1";
	$result = executeResult($sql);

	if (count($result) > 0) {
		foreach ($result as $value) {
			if ($password == $value['Password']) {
				$_SESSION['Email'] = $value['Email'];
				header("Location: ../dashboard.php");
			}
		}
	}
}
?>


<!doctype html>
<html lang="en">

<head>
	<title>Dolly | Login</title>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
						<div class="icon d-flex align-items-center justify-content-center">
							<span class="fa fa-user-o"></span>
						</div>
						<h3 class="text-center mb-4">Xin chào Admin</h3>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-form">
							<div class="form-group">
								<input type="text" name="email" id="email" class="form-control rounded-left" placeholder="Email" required>
							</div>
							<div class="form-group d-flex">
								<input type="password" name="password" id="password" class="form-control rounded-left" placeholder="Password" required>
							</div>
							<div class="form-group d-md-flex">
								<div class="w-50">
									<label class="checkbox-wrap checkbox-primary">Remember Me
										<input type="checkbox" checked>
										<span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#">Quên mật khẩu</a>
								</div>
							</div>
							<button name="login" type="submit" class="btn btn-primary rounded submit p-3 px-5 ">Đăng nhập</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

</body>

</html>