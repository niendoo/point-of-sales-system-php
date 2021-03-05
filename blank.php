<?php 

//database connection
include_once 'config/db_connection.php';
session_start();
//if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
//{
//    header('location:index.php');
//}

include_once'templates/header.php';    ?>


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
	<section class="content container-fluid">

		<!--------------------------
        | Your Page Content Here |
        -------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Add New Category</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->

			<div class="box-body">
				<form role="form" action="" method="post"></form>
			</div>
		</div>

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
