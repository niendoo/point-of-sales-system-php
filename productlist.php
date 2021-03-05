<?php

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales" )
{
header('location:index.php');
}

include_once'templates/header.php';    ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Product List
			<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Products</a></li>
			<li class="active">Products Lists</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
		| Your Page Content Here |
		-------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Product List</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->

			<div class="box-body">
				<!--
				<form role="form" action="" method="post">
				</form>
--><div style="overflow-x: auto">
				<table id="productList" class="table table-striped">
					<thead>
						<tr>
							<th>#ID</th>
							<th>Product Name</th>
							<th>Category</th>
							<th>Purchase Price</th>
							<th>Sale Price</th>
							<th>Stock</th>
							<th>Description</th>
							<th>Image</th>
							<th>View</th>
							<th>Edit</th>
							<th>Delete</th>

						</tr>
					</thead>
					<tbody>
						<?php
	  $select = $connection -> prepare("select * from product_table order by product_id DESC");

	  $select -> execute();
	  while ($row=$select->fetch(PDO::FETCH_OBJ)){

		echo '  <tr>
		<td>'.$row-> product_id.'</td>
		<td>'.$row-> product_name.'</td>
		<td>'.$row-> product_category.'</td>
		<td>'.$row-> purchase_price.'</td>
		<td>'.$row-> sale_price.'</td>
		<td>'.$row-> product_stock.'</td>
		<td>'.$row-> product_description.'</td>
		<td><img src = "productimages/'.$row->product_image.'" class="img-rounded" width="80px" height="80px"/></td>

	  <td>
		<a href="viewproduct.php?id='.$row->product_id.'" class="btn btn-info" role="button"> <span> <i class="fa fa-eye" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="View Product"></i>
		</span></a>
		</td>

	<td><a href="editproduct.php?id='.$row->product_id.'" class="btn btn-primary" role="button"> <span> <i class="fa fa-edit" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Edit Product"></i>
		</span></a>
	</td>
	<td>	<button id='.$row->product_id.' class="btn btn-danger btndelete"> <span> <i class="fa fa-trash" style="color:#fff" data-toggle="tooltip" aria-hidden="true" title="Delete Product"></i>
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
					title: "Are you sure you want to delete this Product?",
					text: "Once deleted, you will not be able to recover this Product again!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {

						$.ajax({
							url: 'deleteproduct.php',
							type: 'post',
							data: {
								pid: id
							},
							success: function(data) {
								tdh.parents('tr').hide();
							}
						});

						swal("Your Product has been deleted!", {
							icon: "success",
						});
					} else {
						swal("Your product is safe!");
					}
				});




		});
	});

</script>
<?php include_once'templates/footer.php';    ?>
