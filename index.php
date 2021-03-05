	<!-- jQuery 3 -->
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="plugins/jquery/jquery.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>
	<!--Sweetalerts-->
	<script src="plugins/sweetalerts/sweetalerts.js"></script>
	<!-- End Script-->


	<?php 
//database connection
include_once 'config/db_connection.php';
//Login Start 
session_start();
error_reporting(0);
if(isset($_POST['btn_login'])){
  $useremail = $_POST['txt_email'];
  $userpassword = $_POST['txt_password'];
  //echo $useremail . " - " .$userpassword;
  $select =$connection -> prepare("select * from user_table where email = '$useremail' AND password = '$userpassword' ");
  $select -> execute();
  $row = $select -> fetch(PDO::FETCH_ASSOC);

  if($row['email'] == $useremail AND $row['password'] == $userpassword AND $row['role'] == "Admin"){
    
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['role'] = $row['role'];
    
    echo '<script type ="text/javascript"> jQuery(function validation(){
      swal({
        title: " Good Job! '.$_SESSION['username'].'",
        text: " You have successfully logged in",
        icon: "success",
        button: "Ok",
      });
    });
      
    </script>';
    header('refresh:3;dashboard.php');
  }
  else if($row['email'] == $useremail AND $row['password'] == $userpassword AND $row['role'] == "Sales"){
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['role'] = $row['role'];
    echo '<script type ="text/javascript"> jQuery(function validation(){
      swal({
        title: " Good Job! '.$_SESSION['username'].'",
        text: " You have successfully logged in",
        icon: "success",
        button: "Ok",
      });
    });
      
    </script>';
    header('refresh:3;user.php');
   
  }
  else{
    echo '<script type ="text/javascript"> jQuery(function validation(){
      swal({
        title: " Your Email or Password is incorrect! ",
        text: "Enter the correct login information",
        icon: "error",
        button: "Ok",
      });
    });
      
    </script>';
  }
  
}
//Login End
?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Niendoo Tech POS | Log in</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="plugins/iCheck/square/blue.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

		<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>

	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<a href="dashboard.php"><b>POINT OF SALES</b> SYSTEM</a>
			</div>
			<!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg">Sign in to start your session</p>

				<form action="#" method="post">
					<div class="form-group has-feedback">
						<input type="email" class="form-control" placeholder="Email" name="txt_email" required>
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" class="form-control" placeholder="Password" name="txt_password" required>
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<div class="checkbox icheck">
								<a href="#" onClick=" swal(' To reset your password,', ' Please reach out to the Admin', 'error')">I forgot my password</a><br>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" name="btn_login">Sign In</button>
						</div>
						<!-- /.col -->
					</div>
				</form>


				<!-- /.social-auth-links -->




			</div>
			<!-- /.login-box-body -->
		</div>
		<!-- /.login-box -->


	</body>

	</html>
