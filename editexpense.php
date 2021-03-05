<?php

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales" )
{
header('location:index.php');
}

include_once'templates/header.php';

$id = $_GET['id'];

$select = $connection->prepare("select * from expenses where expense_id = $id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);
$db_id = $row['expense_id'];
$db_expense_amount = $row['expense_amount'];
$db_expense_reason = $row['expense_reason'];
$db_expense_date = $row['expense_date'];
$db_created_by = $row['created_by'];

if (isset($_POST['btn_update_expense'])) {
$expense_amount_text = $_POST['expense_amount'];
$expense_reason_text = $_POST['expense_reason'];
$expense_date_text = $_POST['expense_date'];
$created_by_text = $_POST['created_by'];

  $update = $connection->prepare("update expenses set expense_amount=:expense_amount, expense_date=:expense_date, expense_reason=:expense_reason, created_by=:created_by where expense_id =$id");

$update->bindParam(':expense_amount',$expense_amount_text);
$update->bindParam(':created_by',$created_by_text);
$update->bindParam(':expense_reason',$expense_reason_text);
$update->bindParam(':expense_date',$expense_date_text);
	if($update->execute()){
	  $error ='<script type ="text/javascript"> jQuery(function validation(){
			swal({
			  title: "Expense Updated!",
			  text: "This Expense Have Been Updated Successfully",
			  icon: "success",
			  button: "Got it",
			});
		  });

		  </script>';
echo $error;
	}
	else{  $error = '<script type ="text/javascript"> jQuery(function validation(){
			swal({
			  title: "Product Update Failed!",
			  text: "This Expense Update Was Unsuccessful",
			  icon: "error",
			  button: "Got it",
			});
		  });

		  </script>';
	echo $error;}
}
$select = $connection->prepare("select * from expenses where expense_id = $id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);
$db_id = $row['expense_id'];
$db_created_by = $row['created_by'];
$db_expense_amount = $row['expense_amount'];
$db_expense_reason = $row['expense_reason'];
$db_expense_date = $row['expense_date'];

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<h3 class="box-title"> <a href="expenses.php" class="btn btn-info" role="button">
					Back To Expenses </a>
				<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Expenses</a></li>
			<li class="active">Expense Update</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
		| Your Page Content Here |
		-------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Edit Expense</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->

			<form action="" method="post" name="expenseForm" enctype="multipart/form-data">
				<div class="box-body">

					<div class="col-md-6">
						<div class="form-group">
							<label>Expense By</label>
							<input type="text" class="form-control" value="<?php echo $db_created_by;?>" name="created_by" required>
						</div>
						<div class="form-group">
							<label>Expense Amount</label>
							<input type="text" class="form-control" placeholder="Enter Expense Amount" name="expense_amount" value="<?php echo $db_expense_amount; ?>">
						</div>
            <div class="form-group">
							<label>Expense Date</label>
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker" name="expense_date"  value="<?php echo $db_expense_date;?>" data-date-format="yyyy-mm-dd" required>
                </div>

						</div>
            <div class="form-group">
							<label>Expense Reason</label>
							<textarea class="form-control" name="expense_reason" cols="63" rows="5"><?php echo $db_expense_reason; ?></textarea>
						</div>
					</div>

				</div><!-- /.box-body -->

				<div class="box-footer">
					<div class="button-group">

						<button type="submit" class="btn btn-primary" name="btn_update_expense">Update Product</button>
					</div>
				</div>
			</form>
		</div>

	</section>
	<!-- /.content -->
</div>
<script>
  $('#datepicker').datepicker({
      autoclose: true
    })
</script>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
