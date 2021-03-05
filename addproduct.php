<?php

//database connection
include_once 'config/db_connection.php';
session_start();

if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
	header('location:index.php');
}

include_once'templates/header.php';

if(isset($_POST['btn_add_product'])){
$product_name = $_POST['product_name'];

$select_category = $_POST['select_category'];

$purchase_price = $_POST['purchase_price'];

$sale_price = $_POST['sale_price'];

$product_stock = $_POST['product_stock'];

$product_description = $_POST['product_description'];

$f_name = $_FILES['myfile']['name'];
$f_tmp = $_FILES['myfile']['tmp_name'];
	
$f_size = $_FILES['myfile']['size'];
	
$f_extension = explode('.',$f_name);
	
$f_extension = strtolower(end($f_extension));
	
$f_newfile = uniqid().'.'. $f_extension;

$store = "productimages/".$f_newfile;
	
	
	
	if($f_extension=='jpg' || $f_extension=='png' || $f_extension=='gif' || $f_extension=='jpeg' ){
		if($f_size>=1000000){
		
			   $error ='<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Error!",
          text: "Max file size should be 1MB",
          icon: "error",
          button: "Ok",
        }); 
      });
        
      </script>';
      echo $error;
			
		}  else {
			if(move_uploaded_file($f_tmp,$store)){
				
				$product_image = $f_newfile;
				
		
				}
		}
	}
	else
	{

		$error ='<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Error!",
          text: "Only JPG, PNG, GIF and JPEG Allowed",
          icon: "error",
          button: "Ok",
        }); 
      });
        
      </script>';
      echo $error;
			
	}

	
if(!isset($error))	{
	$insert = $connection->prepare("insert into product_table(product_name,product_category,purchase_price,sale_price,product_stock,product_description,product_image) 
	values(:product_name,:product_category,:purchase_price,:sale_price,:product_stock,:product_description,:product_image)");
	
	$insert->bindParam(':product_name',$product_name);
	$insert->bindParam(':product_category',$select_category);
	$insert->bindParam(':purchase_price',$purchase_price);
	$insert->bindParam(':sale_price',$sale_price);
	$insert->bindParam(':product_stock',$product_stock);
	$insert->bindParam(':product_description',$product_description);
	$insert->bindParam(':product_image',$product_image);
	
	
	if($insert->execute()){
		
		echo'<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Success!",
          text: "Product Added Successfully",
          icon: "success",
          button: "Ok",
        }); 
      });
        
      </script>';
     
	}
	else{
		
			
		echo'<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Failed!",
          text: "Product Adding Fails",
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
			Add Product
			<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Products</a></li>
			<li class="active">Add Product</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
		| Your Page Content Here |
		-------------------------->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> <a href="productlist.php" class="btn btn-info" role="button">
						Back To Product List </a>
				</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form action="" method="post" name="productForm" enctype="multipart/form-data">
				<div class="box-body">

					<div class="col-md-6">
						<div class="form-group">
							<label>Product Name</label>
							<input type="text" class="form-control" placeholder="Enter Product Name" name="product_name" required>
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

								<option>
									<?php echo $row['category'];?>
								</option>
								<?php } ?>

							</select>
						</div>
						<div class="form-group">
							<label>Purchase Price</label>

							<input type="number" min="0" pattern="[0-9] + ([\.,][0-9]+?)" formnovalidate step="0.01" class="form-control" placeholder="Enter Purchase Price" name="purchase_price" required>

						</div>
						<div class="form-group">
							<label>Sale Price</label>

							<input type="number" min="0" step="0.01" class="form-control" placeholder="Enter Sale Price" name="sale_price" required>

						</div>


					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Stock</label>
							<input type="number" min="1" step="1" class="form-control" placeholder="Enter Stock Quantity" name="product_stock" required>
						</div>
						<div class="form-group">
							<label>Product Description</label>
							<textarea class="form-control" name="product_description" cols="63" rows="5"></textarea>
						</div>
						<div class="form-group">
							<label>Product Image</label>
							<input type="file" class="form-control input-group" name="myfile" required>
							<p>Upload Product Image</p>
						</div>


					</div>




				</div><!-- /.box-body -->

				<div class="box-footer">
					<div class="button-group">

						<button type="submit" class="btn btn-primary" name="btn_add_product">Add Product</button>
					</div>
				</div>
			</form>
		</div> <!-- /.box box-primary -->

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
