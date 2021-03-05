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
			Product Details
			<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Products</a></li>
			<li class="active">Product Details</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
		| Your Page Content Here |
		-------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><a href="productlist.php" class="btn btn-info" role="button">
						Back To Product List </a></h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->

			<div class="box-body">
				<?php

$id = $_GET['id'];
$select = $connection ->prepare ("select * from product_table where product_id = $id");
$select ->execute();
while($row=$select-> fetch(PDO::FETCH_OBJ)){
echo'

<div class=col-md-6>
<ul class="list-group">
<center><p class="list-group-item list-group-item-success"><b>Product Details</b></p></center>

<li class="list-group-item"><b>Product ID: </b><span class="badge badge-info pull-right">'.$row->product_id.'</span></li>
<li class="list-group-item"><b>Product Name: </b><span class="label label-primary pull-right">'.$row->product_name.'</span></li>
<li class="list-group-item"><b>Product Category: </b><span class="label label-primary pull-right">'.$row->product_category.'</span></li>
<li class="list-group-item"><b>Purchase Price: </b><span class="label label-warning pull-right">'.$row->purchase_price.'</span></li>
<li class="list-group-item"><b>Sale Price: </b><span class="label label-warning pull-right">'.$row->sale_price.'</span></li>
<li class="list-group-item"><b>Product Profit: </b><span class="label label-success pull-right">'.($row->sale_price - $row->purchase_price).'</span></li>
<li class="list-group-item"><b>Stock: </b><span class="label label-danger pull-right">'.$row->product_stock.'</span></li>
<li class="list-group-item"><b>Product Description: </b><span class="pull-right">'.$row->product_description.'</span></li>

</ul>
</div>
<div class=col-md-6>
<ul class="list-group">
<center><p class="list-group-item list-group-item-success"><b>Product Image</b></p></center>
<img src = "productimages/'.$row->product_image.'" class="img-responsive" "/>

</ul>
</div>
';
}









				?>





				<form role="form" action="" method="post"></form>
			</div>
		</div>

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
