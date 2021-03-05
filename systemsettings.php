<?php 
//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" )
{
    header('location:index.php'); 

}
if($_SESSION['role']=='Admin'){
  include_once'templates/header.php'; 
}
else{
  include_once'templates/salesheader.php'; 
}
   

//When change password button is clicked, we take form input values and store them in a variable
if(isset($_POST['btn_change_password'])){

$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// echo $old_password. "-" .$new_password. "-" .$confirm_password;

//We then use select query to extract record of the user from the database by using the user email
$email = $_SESSION['email'];

$select = $connection -> prepare ("select * from user_table where email = '$email'");
$select -> execute();
$row = $select -> fetch(PDO::FETCH_ASSOC);
// echo $row['email'];
// echo $row['username'];

//Comparing user inputs with database values
$user_email_db_value = $row['email'];
$user_password_db_value = $row['password'];

if($old_password == $user_password_db_value){
  // echo "Password Matched";
  
  if($new_password == $confirm_password){
    // echo "Password Matched";
    $update = $connection -> prepare ("update user_table set password =:pass where email =:email");
    $update -> bindParam(':pass', $confirm_password);
    $update -> bindParam(':email', $email);

    if($update-> execute()){
      echo '<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Bravo!",
          text: "Your New Password Has Been Saved",
          icon: "success",
          button: "Got it",
        });
      });
        
      </script>';
    }
    else{
      echo '<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Failed!",
          text: "Password Update Failed",
          icon: "error",
          button: "Ok",
        });
      });
        
      </script>';
     
    }
  }
  else{
    echo '<script type ="text/javascript"> jQuery(function validation(){
      swal({
        title: "Error!",
        text: "Password Not Matched",
        icon: "error",
        button: "Ok",
      });
    });
      
    </script>';
    
  }
}



}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
			<li class="active">Here</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid  " style="width: 400px;">

		<!--------------------------
        | Your Page Content Here |
        -------------------------->
		<div class="register-box-body ">
			<p class="login-box-msg">You are only one step a way from your new password</p>

			<form action="" method="POST">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" placeholder="Old Password" name="old_password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" placeholder="New Password" name="new_password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" placeholder="Retype password" name="confirm_password" required>
					<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
				</div>
				<div class="row">

					<!-- /.col -->
					<div class="col-xs-6">
						<button type="submit" class="btn btn-primary btn-block btn-flat" name="btn_change_password">Change password</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
