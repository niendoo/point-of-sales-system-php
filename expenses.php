<?php

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
	header('location:index.php');
}

include_once'templates/header.php';

if(isset($_POST['btn_add_expense'])){
$created_by = $_POST['created_by'];
$expense_amount = $_POST['expense_amount'];
$expense_date = $_POST['expense_date'];
$expense_reason = $_POST['expense_reason'];

$insert = $connection ->prepare ("insert into expenses(created_by, expense_amount, expense_date,expense_reason)
values(:created_by,:expense_amount,:expense_date,:expense_reason)");
$insert ->bindParam(':created_by',$created_by);
$insert ->bindParam(':expense_amount',$expense_amount);
$insert ->bindParam(':expense_date',$expense_date );
$insert ->bindParam(':expense_reason',$expense_reason);
if($insert -> execute()){
  echo '<script type ="text/javascript"> jQuery(function validation(){
      swal({
        title: "Bravo!",
        text: "Expense Added Successful",
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
                text: "Expense Saving Failed",
                icon: "error",
                button: "Ok",
              });
            });
              
            </script>';
           
      }
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Expenditure
			<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">Add Expense</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
		| Your Page Content Here |
		-------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> <a href="dashboard.php" class="btn btn-info" role="button">
						Back To Dashboard </a>
				</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form action="" method="post" name="productForm" enctype="multipart/form-data">
				<div class="box-body">

					<div class="col-md-6">	
						<div class="form-group">
							<label>Expense By</label>

							<input type="text" class="form-control" placeholder="Expense by" name="created_by" required>
						</div>
						<div class="form-group">
							<label>Expense Amount</label>

							<input type="number" min="0" pattern="[0-9] + ([\.,][0-9]+?)" formnovalidate step="0.01" class="form-control" placeholder="Enter Expense Amount" name="expense_amount" required>
						</div>
						<div class="form-group">
							<label>Expense Date</label>
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker" name="expense_date"  value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd" required>
                </div>

						</div>

            <div class="form-group">
							<label>Expense Reason</label>
							<textarea class="form-control" name="expense_reason" cols="63" rows="5" placeholder="Write Expense Reason Here"></textarea>
						</div>
					</div>
					<div class="col-md-6">


					</div>
				</div><!-- /.box-body -->

				<div class="box-footer">
					<div class="button-group">
						<button type="submit" class="btn btn-primary" name="btn_add_expense">Add Expense</button>
					</div>
				</div>
			</form>
      <div style="overflow-x: auto">
				<table id="productList" class="table table-striped">
					<thead>
						<tr>
							<th>#ID</th>
							<th>Expense By</th>
							<th>Expense Amount</th>
              <th>Expense Reason</th>
							<th>Expense Date</th>
							<th>View</th>
							<th>Edit</th>
							<th>Print</th>
							<th>Delete</th>

						</tr>
					</thead>
					<tbody>
						<?php
	  $select = $connection -> prepare("select * from expenses order by expense_id DESC");

	  $select -> execute();
	  while ($row=$select->fetch(PDO::FETCH_OBJ)){

		echo '  <tr>
		<td>'.$row-> expense_id.'</td>
		<td>'.$row-> created_by.'</td>
		<td>'.$row-> expense_amount.'</td>
		<td>'.$row-> expense_reason.'</td>
		<td>'.$row-> expense_date.'</td>

	  <td>
		<a href="viewexpense.php?id='.$row->expense_id.'" class="btn btn-info" role="button"> <span> <i class="fa fa-eye" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="View Expense"></i>
		</span></a>
		</td>

	<td><a href="editexpense.php?id='.$row->expense_id.'" class="btn btn-primary" role="button"> <span> <i class="fa fa-edit" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Edit Expense"></i>
		</span></a>
	</td>
	<td>
		<a href="print_expense.php?id='.$row->expense_id.'" class="btn btn-info" role="button" target="_blank"> <span> <i class="fa fa-print" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Print"></i>
		</span></a>
		</td>
	<td>	<button id='.$row->expense_id.' class="btn btn-danger btndelete"> <span> <i class="fa fa-trash" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Delete Expense"></i>
		</span></button></td>
		</tr>';


	  }


		?>
					</tbody>


				</table>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!--Data table plugin-->
<script>
	$(document).ready(function() {
		$('#productList').DataTable({
			"order": [
				[0, "desc"]
			]
		});
	});

</script>

<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});

</script>
<script>
	$(document).ready(function() {
		$('.btndelete').click(function() {
			var tdh = $(this);
			var id = $(this).attr("id");


			swal({
					title: "Are you sure you want to delete this Expense record?",
					text: "Once deleted, you will not be able to recover this record again!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {

						$.ajax({
							url: 'deleteexpense.php',
							type: 'post',
							data: {
								pid: id
							},
							success: function(data) {
								tdh.parents('tr').hide();
							}
						});

						swal("Your Expense has been deleted!", {
							icon: "success",
						});
					} else {
						swal("Your Expense is safe!");
					}
				});




		});
	});

</script>
		</div> <!-- /.box box-primary -->

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
