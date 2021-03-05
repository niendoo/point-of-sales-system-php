<?php 

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] ==""  )
{
   header('location:index.php');
}

if($_SESSION['role'] =="Admin"  )
{
include_once'templates/header.php'; 
}
elseif($_SESSION['role'] =="Sales"  )
{
    include_once'templates/salesheader.php';
}   ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Admin Dashboard
			
		</h1>
		<ol class="breadcrumb">
			<li><a href="createorder.php"><i class="fa fa-dashboard"></i>Orders</a></li>
			<li class="active">Order List</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
        | Your Page Content Here |
        -------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Order List</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->

			<div class="box-body">
            <div style="overflow-x: auto">
				<table id="orderList" class="table table-striped">
					<thead>
						<tr>
							<th>Invoice ID</th>
							<th>Customer Name</th>
							<th>Order Date</th>
							<th>Total</th>
							<th>Amount Paid</th>
							<th>Due</th>
							<th>Payment Type</th>
							<th>Thermal Print</th>
							<th>Print A4</th>
							<th>Edit</th>
							<th>Delete</th>

						</tr>
					</thead>
					<tbody>
						<?php
	  $select = $connection -> prepare("select * from invoice_table order by invoice_id DESC");

	  $select -> execute();
	  while ($row=$select->fetch(PDO::FETCH_OBJ)){

		echo '  <tr>
		<td>'.$row-> invoice_id.'</td>
		<td>'.$row-> customer_name.'</td>
		<td>'.$row-> order_date.'</td>
		<td>'.number_format($row-> total,2).'</td>
		<td>'.number_format($row-> paid,2).'</td>
		<td>'.number_format($row-> due,2).'</td>
		<td>'.$row-> payment_type.'</td>
		

	  <td>
		<a href="thermal_invoice.php?id='.$row->invoice_id.'" class="btn btn-info" role="button" target="_blank"> <span> <i class="fa fa-print" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Print Invoice"></i>
		</span></a>
		</td>

		<td>
		<a href="invoice.php?id='.$row->invoice_id.'" class="btn btn-info" role="button" target="_blank"> <span> <i class="fa fa-print" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Print Invoice"></i>
		</span></a>
		</td>

	<td><a href="editorder.php?id='.$row->invoice_id.'" class="btn btn-primary" role="button"> <span> <i class="fa fa-edit" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Edit Product"></i>
		</span></a>
	</td>
	<td>	<button id='.$row->invoice_id.' class="btn btn-danger btndelete"> <span> <i class="fa fa-trash" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Delete Product"></i>
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
		$('#orderList').DataTable({
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

	$(document).ready(function() {
		$('.btndelete').click(function() {
			var tdh = $(this);
			var id = $(this).attr("id");


			swal({
					title: "Are you sure you want to delete this Order?",
					text: "Once deleted, you will not be able to recover this Order again!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {

						$.ajax({
							url: 'deleteorder.php',
							type: 'post',
							data: {
								pid: id
							},
							success: function(data) {
								tdh.parents('tr').hide();
							}
						});

						swal("Your Order has been deleted!", {
							icon: "success",
						});
					} else {
						swal("Your Order is safe!");
					}
				});




		});
	});






</script>
<?php include_once'templates/footer.php';    ?>
