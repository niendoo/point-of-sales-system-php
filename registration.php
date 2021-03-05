<?php 
//database connection
include_once 'config/db_connection.php';
session_start();

if($_SESSION['email'] == "" OR $_SESSION['role'] == "Sales")
{
    header('location:index.php'); 

}

include_once'templates/header.php';
error_reporting(0);

$id = $_GET['id'];
$delete = $connection -> prepare("delete from user_table where user_id =".$id);

if($delete->execute()){
    echo '<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Done!",
          text: "User Records Deleted Successfully",
          icon: "success",
          button: "Got it",
        });
      });
        
      </script>';
}
// else{
//     echo '<script type ="text/javascript"> jQuery(function validation(){
//         swal({
//           title: "Failed!",
//           text: "User Records Deletion Unsuccessful",
//           icon: "error",
//           button: "Try again",
//         });
//       });
        
//       </script>';
// }


if(isset($_POST['btn_register'])){
    $user_name = $_POST['user_name'];
    $user_email = $_POST['txt_email'];
    $user_password = $_POST['txt_password'];
    $user_role = $_POST['select_role'];


    if(isset($_POST['txt_email'])){
        $select = $connection->prepare("select email from user_table where email = '$user_email'");
        $select->execute();

        if($select->rowCount()>0){
            echo '<script type ="text/javascript"> jQuery(function validation(){
                swal({
                  title: "Duplicate Alert!",
                  text: "Email Already Exist",
                  icon: "error",
                  button: "Try again",
                });
              });
                
              </script>';
        }
        else{
              // echo $user_name ."". $user_email ."". $user_password ."". $user_role;
     $insert = $connection ->prepare ("insert into user_table(username,email,password,role)
     values(:name,:email,:pass,:role)");
    $insert ->bindParam(':name',$user_name);
    $insert ->bindParam(':email',$user_email);
    $insert ->bindParam(':pass',$user_password);
    $insert ->bindParam(':role',$user_role);


    if($insert -> execute()){
echo '<script type ="text/javascript"> jQuery(function validation(){
    swal({
      title: "Bravo!",
      text: "User Registration Successful",
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
              text: "User Registration Failed",
              icon: "error",
              button: "Ok",
            });
          });
            
          </script>';
         
    }

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
			<li><a href="#"><i class="fa fa-dashboard"></i> Admin Dashboard</a></li>
			<li class="active">New User Registration</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
        | Your Page Content Here |
        -------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Register New User</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form role="form" action="" method="post">
				<div class="box-body">
					<div class="col-md-4">
						<div class="form-group">
							<label>User Name</label>
							<input type="text" class="form-control" placeholder="Enter User Name" name="user_name" required>
						</div>
						<div class="form-group">
							<label>User Email</label>
							<input type="email" class="form-control" placeholder="Enter User Email" name="txt_email" required>
						</div>
						<div class="form-group">
							<label>User Password</label>
							<input type="password" class="form-control" placeholder="Enter User Password" name="txt_password" required>
						</div>
						<div class="form-group">
							<label>Choose User Role</label>
							<select class="form-control" name="select_role" required>
								<option value="" disabled selected>Select User Role</option>
								<option>Admin</option>
								<option>Sales</option>
								<!-- <option>Warehouse Manager</option> -->

							</select>
						</div>

						<div class="box-footer">
							<button type="submit" class="btn btn-primary" name="btn_register">Register</button>
						</div>
					</div>
					<div class="col-md-8">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Password</th>
									<th>Role</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php
    $select = $connection -> prepare("select * from user_table order by user_id asc");

    $select -> execute();
    
    while ($row=$select->fetch(PDO::FETCH_OBJ)){

        echo '  <tr>
        <td>'.$row-> user_id.'</td>
        <td>'.$row-> username.'</td>
        <td>'.$row-> email.'</td>
        <td>'.$row-> password.'</td>
        <td>'.$row-> role.'</td>
        
        <td>
        <a href="registration.php?id='.$row->user_id.'" class="btn btn-danger" role="button"> <span> <i class="fa fa-trash" aria-hidden="true" title="delete"></i>
        </span></a>
        </td>
    </tr>';
    }
    
    ?>


							</tbody>


						</table>
					</div>

				</div>
				<!-- /.box-body -->

			</form>
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
