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

$select = $connection->prepare("select * from product_table where product_id = $id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);
$db_id = $row['product_id'];
$db_product_name = $row['product_name'];
$db_product_category = $row['product_category'];
$db_purchase_price = $row['purchase_price'];
$db_sale_price = $row['sale_price'];
$db_product_stock = $row['product_stock'];
$db_product_description = $row['product_description'];
$db_product_image = $row['product_image'];



if (isset($_POST['btn_update_product'])) {
$product_name_text = $_POST['product_name'];

$select_category_text = $_POST['select_category'];

$purchase_price_text = $_POST['purchase_price'];

$sale_price_text = $_POST['sale_price'];

$product_stock_text = $_POST['product_stock'];

$product_description_text = $_POST['product_description'];

$f_name = $_FILES['myfile']['name'];

if(!empty($f_name))
{


$f_tmp = $_FILES['myfile']['tmp_name'];

$f_size = $_FILES['myfile']['size'];

$f_extension = explode('.', $f_name);

$f_extension = strtolower(end($f_extension));

$f_newfile = uniqid().'.'. $f_extension;

$store = "productimages/".$f_newfile;


if($f_extension == 'jpg' || $f_extension == 'jpeg' || $f_extension == 'png' || $f_extension == 'gif'){

if($f_size >= 1000000){

	  $error = '<script type ="text/javascript"> jQuery(function validation(){
			swal({
			  title: "Max File Size Exceeded!",
			  text: "Max file should be 1MB",
			  icon: "warning",
			  button: "Got it",
			});
		  });

		  </script>';
	echo $error;
}	else{
	if(move_uploaded_file($f_tmp, $store)){
		$f_newfile;

	if(!isset($error)){

	$update = $connection->prepare("update product_table set product_name=:product_name, product_category=:product_category, purchase_price=:purchase_price, sale_price=:sale_price, product_stock=:product_stock, product_description=:product_description, product_image=:product_image where product_id =$id");



$update->bindParam(':product_name',$product_name_text);
$update->bindParam(':product_category',$select_category_text);
$update->bindParam(':purchase_price',$purchase_price_text);
$update->bindParam(':sale_price',$sale_price_text);
$update->bindParam(':product_stock',$product_stock_text);
$update->bindParam(':product_description',$product_description_text);
$update->bindParam(':product_image',$f_newfile);

	if($update->execute()){

		   echo '<script type ="text/javascript"> jQuery(function validation(){
		   swal({
			  title: "Product Updated!",
			  text: "This Product Have Been Updated Successfully",
			  icon: "success",
			  button: "Got it",
			});
		 });

		  </script>';




	}else{
		echo '<script type ="text/javascript"> jQuery(function validation(){
			swal({
			  title: "Product Update Failed!",
			  text: "This Product Update Was Unsuccessful",
			  icon: "error",
			  button: "Got it",
			});
		  });

		  </script>';}

	}

	}
}

}
else{

	  $error =  '<script type ="text/javascript"> jQuery(function validation(){
			swal({
			  title: "Unsupported Image Format!",
			  text: "Only jpg, png and gif supported",
			  icon: "error",
			  button: "Got it",
			});
		  });

		  </script>';
	echo $error;
}


} else{
$update = $connection->prepare("update product_table set product_name=:product_name, product_category=:product_category, purchase_price=:purchase_price, sale_price=:sale_price, product_stock=:product_stock, product_description=:product_description, product_image=:product_image where product_id =$id");



$update->bindParam(':product_name',$product_name_text);
$update->bindParam(':product_category',$select_category_text);
$update->bindParam(':purchase_price',$purchase_price_text);
$update->bindParam(':sale_price',$sale_price_text);
$update->bindParam(':product_stock',$product_stock_text);
$update->bindParam(':product_description',$product_description_text);
$update->bindParam(':product_image',$db_product_image);




	if($update->execute()){

	  $error ='<script type ="text/javascript"> jQuery(function validation(){
			swal({
			  title: "Product Updated!",
			  text: "This Product Have Been Updated Successfully",
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
			  text: "This Product Update Was Unsuccessful",
			  icon: "error",
			  button: "Got it",
			});
		  });

		  </script>';
	echo $error;}
}


}


$select = $connection->prepare("select * from product_table where product_id = $id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);
$db_id = $row['product_id'];
$db_product_name = $row['product_name'];
$db_product_category = $row['product_category'];
$db_purchase_price = $row['purchase_price'];
$db_sale_price = $row['sale_price'];
$db_product_stock = $row['product_stock'];
$db_product_description = $row['product_description'];
$db_product_image = $row['product_image'];

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<h3 class="box-title"> <a href="productlist.php" class="btn btn-info" role="button">
					Back To Product List </a>
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
				<h3 class="box-title">Edit Product</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->

			<form action="" method="post" name="productForm" enctype="multipart/form-data">
				<div class="box-body">

					<div class="col-md-6">
						<div class="form-group">
							<label>Product Name</label>
							<input type="text" class="form-control" placeholder="Enter Product Name" name="product_name" value="<?php echo $db_product_name; ?>">
						</div>
						<div class="form-group">
							<label>Select Product Category</label>
							<select class="form-control" name="select_category" required>
								<option value="" disabled selected>Select Product Category</option>


								<?php
								$select = $connection -> prepare("select * from category_table order by cat_id desc");
								$select->execute();

								while($row=$select->fetch(PDO::FETCH_ASSOC)){
									extract($row);
										?>

								<option <?php if($row['category'] == $db_product_category){?> selected="selected" <?php }?>>


									<?php echo $row['category'];?> </option>
								<?php } ?>

							</select>
						</div>
						<div class="form-group">
							<label>Purchase Price</label>

							<input type="number" min="0" pattern="[0-9] + ([\.,][0-9]+?)" formnovalidate step="0.01" class="form-control" placeholder="Enter Purchase Price" name="purchase_price" value="<?php echo $db_purchase_price; ?>">

						</div>
						<div class="form-group">
							<label>Sale Price</label>

							<input type="number" min="0" step="0.01" class="form-control" placeholder="Enter Sale Price" name="sale_price" value="<?php echo $db_sale_price; ?>">

						</div>


					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Stock</label>
							<input type="number" min="1" step="1" class="form-control" placeholder="Enter Stock Quantity" name="product_stock" value="<?php echo $db_product_stock; ?>">
						</div>
						<div class="form-group">
							<label>Product Description</label>
							<textarea class="form-control" name="product_description" cols="63" rows="5"><?php echo $db_product_description; ?></textarea>
						</div>
						<div class="form-group">
							<label>Product Image</label>
							<img src="productimages/<?php echo $db_product_image; ?>" class="img-responsive" width="80px" height="80px" />
							<input type="file" class="form-control input-group" name="myfile">
							<p>Upload Product Image</p>
						</div>


					</div>




				</div><!-- /.box-body -->

				<div class="box-footer">
					<div class="button-group">

						<button type="submit" class="btn btn-primary" name="btn_update_product">Update Product</button>
					</div>
				</div>
			</form>
		</div>

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
