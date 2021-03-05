<?php 

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == ""  )
{
    header('location:index.php');
}
include_once'templates/header.php';    
error_reporting(0);

if(isset($_POST['btn_save'])){
 
$category = $_POST['category_name'];
if(empty($category)){
    $error ='<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Empty Field!",
          text: "Kindly enter a Category name",
          icon: "error",
          button: "Ok",
        }); 
      });
        
      </script>';
      echo $error;
}
if(!isset($error)){
    $insert = $connection ->prepare ("insert into category_table(category)
     values(:category)");
    $insert ->bindParam(':category',$category);
    if($insert -> execute()){

        echo '<script type ="text/javascript"> jQuery(function validation(){
            swal({
              title: "Bravo!",
              text: "Category Saved Successfully",
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
              text: "Category Save Failed",
              icon: "error",
              button: "Ok",
            });
          });
            
          </script>';

    }
}

} //End of Add Button Code

if(isset($_POST['btn_update'])){
	
	$category = $_POST['category_name'];
	$id = $_POST['txt_hidden_id'];
if(empty($category)){
	
	$updateError = '<script type ="text/javascript"> jQuery(function validation(){
        swal({
          title: "Empty Field!",
          text: "Kindly enter a Category name",
          icon: "error",
          button: "Ok",
        }); 
      });
        
      </script>';
      echo $updateError;
} //end of empty check code
	
	
if(!isset($updateError)){
	
	$update = $connection ->prepare ("update category_table set category=:category where cat_id=".$id);
   
    $update ->bindParam(':category',$category);
	if($update ->execute()){
		echo '<script type ="text/javascript"> jQuery(function validation(){
            swal({
              title: "Bravo!",
              text: "Category Updated Successfully",
              icon: "success",
              button: "Got it",
            });
          });
            
          </script>';
		
	}else{
		
		 echo '<script type ="text/javascript"> jQuery(function validation(){
            swal({
              title: "Failed!",
              text: "Category Update Failed",
              icon: "error",
              button: "Ok",
            });
          });
            
          </script>';
	}
	
}
	
	
	
}//end of update button code

if(isset($_POST['btn_delete'])){
	
	$delete = $connection -> prepare("delete from category_table where cat_id=".$_POST['btn_delete']);
	
	if($delete->execute()){
			echo '<script type ="text/javascript"> jQuery(function validation(){
            swal({
              title: "Done!",
              text: "Category Deleted Successfully",
              icon: "success",
              button: "Got it",
            });
          });
            
          </script>';
		}
	else{

	echo '<script type="text/javascript">
		jQuery(function validation() {
			swal({
				title: "Failed!",
				text: "Category Deletion Failed",
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
			Dashboard
			<small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">category</li>
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
				<form role="form" action="" method="post">
					<?php

 if(isset($_POST['btn_edit'])){
 
    $select = $connection ->prepare ("select * from category_table where cat_id=".$_POST['btn_edit']);
    $select -> execute();
if($select){

    $row=$select->fetch(PDO::FETCH_OBJ); 
    echo' <div class="col-md-4">
	<div class="form-group">
    <label>Category Name</label>
    <input type="hidden" class="form-control" placeholder="Enter Category Name"
        name="txt_hidden_id" value="'.$row->cat_id.'">
    </div>
	<div class="form-group">
    
    <input type="text" class="form-control" placeholder="Enter Category Name"
        name="category_name" value="'.$row->category.'">
    </div>
 
    <button type="submit" class="btn btn-primary" name="btn_update">Update</button>
    </div>'; 

}

 }
 else{
echo' <div class="col-md-4">
 <div class="form-group">
<label>Category Name</label>
<input type="text" class="form-control" placeholder="Enter Category Name"
    name="category_name">
</div>

<button type="submit" class="btn btn-primary" name="btn_save">Save</button>
</div>
 ';
 }

?>
					<div class="col-md-8">
						<table id="categoryTable" class="table table-striped">
							<thead>
								<tr>
									<th>#ID</th>
									<th>Name</th>
									<th>Edit</th>
									<th>Delete</th>

								</tr>
							</thead>
							<tbody>
								<?php
      $select = $connection -> prepare("select * from category_table order by cat_id DESC");

      $select -> execute();
      while ($row=$select->fetch(PDO::FETCH_OBJ)){

        echo '  <tr>
        <td>'.$row-> cat_id.'</td>
        <td>'.$row-> category.'</td>
        <td>
        <button type="submit" value="'.$row-> cat_id.'" class="btn btn-primary" name="btn_edit" >Edit<span> <i class="fa fa-edit"  title="Edit"></i>
        </span></a>
        </td>
        
        <td>
        <button type="submit" value="'.$row->cat_id.'" class="btn btn-danger" name="btn_delete" >Delete<span> <i class="fa fa-trash" aria-hidden="true" title="Delete"></i>
        </span></a>
        </td>
        </tr>';


      }
       
  
        ?>
							</tbody>


						</table>
					</div>
				</form>
			</div>
			<!-- /.box-body -->


		</div>


	</section>
	<!-- /.content -->
</div>
<!--Data table plugin-->
<script>
	$(document).ready(function() {
		$('#categoryTable').DataTable({
			"order": [
				[0, "desc"]
			]
		});
	});

</script>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>
