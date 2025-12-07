<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login Labolatorium NCS</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/image1.png);">
					<span class="login100-form-title-1">
						Lab NCS
					</span>
				</div>

				<form action="authenticate.php" method="post" autocomplete="off" class="login100-form validate-form">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Username</span>
						<input class="input100" id="username" type="text" name="username" placeholder="Enter username" required autofocus>
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate="Password is required" style="position: relative;">
						<span class="label-input100">Password</span>
						<div class="password-field">
							<input id="password" class="input100" type="password" name="password" placeholder="Enter password" required>
							<span class="toggle-password">
								<i class="fa fa-eye" id="togglePasswordIcon"></i>
							</span>
						</div>
						<span class="focus-input100"></span>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="errorModalLabel">Login Error</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="errorModalBody">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script src="js/main.js"></script>
	<script>
		<?php if (isset($_GET['error'])): ?>
			$(document).ready(function() {
				$('#errorModalBody').text("<?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?>");
				$('#errorModal').modal('show');
			});
		<?php endif; ?>

		$(document).ready(function() {
			$('.toggle-password').on('click', function() {
				const passwordInput = $('#password');
				const icon = $('#togglePasswordIcon');
				const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
				passwordInput.attr('type', type);

				// ganti icon
				if (type === 'text') {
					icon.removeClass('fa-eye').addClass('fa-eye-slash');
				} else {
					icon.removeClass('fa-eye-slash').addClass('fa-eye');
				}
			});
		});
	</script>
</body>

</html>