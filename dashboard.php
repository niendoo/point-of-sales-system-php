<?php 

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
    header('location:index.php');
}

include_once'templates/header.php';   

$select=$connection->prepare("select sum(total) as total, count(invoice_id) as invoice from invoice_table");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$total_order=$row->invoice;
$net_total=$row->total;










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
      <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li> -->
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

    <!--------------------------
        | Your Page Content Here |
		-------------------------->
    <div class="box-body">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($total_order) ;?></h3>

              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="orderlist.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo "GH&#8373;".' '. number_format($net_total,2) ;?></h3>

              <p>Total Sales</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="graphreport.php.php" class="small-box-footer">More info <i
                class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php

$select=$connection->prepare("select count(product_name) as ptotal from product_table");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$total_products=$row->ptotal;



?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo number_format($total_products) ;?></h3>

              <p>Total Products</p>
            </div>
            <div class="icon">
              <i class="fa fa-product-hunt"></i>
            </div>
            <a href="productlist.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <?php

$select=$connection->prepare("select count(category) as cat_total from category_table");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$cat_total=$row->cat_total;



?>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo number_format($cat_total) ;?></h3>

              <p>Total Product Categories</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="category.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

      </div>
      <!-- /.row -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Sales By Date</h3>
        </div>
        <div class="box-body">

          <?php $select = $connection->prepare("select order_date, total from invoice_table group by order_date LIMIT 30");


$select -> execute();
$total_t=[];
$date_d=[];
while ($row=$select->fetch(PDO::FETCH_ASSOC)){
extract($row);
$total_t[]=$total;
$date_d[]=$order_date;


} 
?>
          <div class="chart"> <canvas id="salesByDate" style="height: 300px;"></canvas></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Best Selling Products</h3>
            </div>


            <div class="box-body">

              <table id="bestList" class="table table-striped">
                <thead>
                  <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>


                  </tr>
                </thead>
                <tbody>
                  <?php
	  $select = $connection->prepare("select product_id,product_name,price,sum(qty) as q, sum(qty*price) as total from invoice_details_table group by product_id order by sum(qty) DESC LIMIT 20");

	  $select->execute();
	  while ($row=$select->fetch(PDO::FETCH_OBJ)){

		echo '<tr>
		<td>'.$row->product_id.'</td>
		<td>'.$row->product_name.'</td>
		<td>'.$row->q.'</td>
		<td>'."GH&#8373; ".number_format($row-> price,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> total,2).'</td>
	
		</tr>';


	  }


		?>
                </tbody>


              </table>

            </div>
          </div>

        </div>
        <div class="col-md-6">


          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Orders</h3>
            </div>


            <div class="box-body">
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


                  </tr>
                </thead>
                <tbody>
                  <?php
	  $select = $connection -> prepare("select * from invoice_table order by invoice_id DESC LIMIT 20");

	  $select -> execute();
	  while ($row=$select->fetch(PDO::FETCH_OBJ)){

		echo '  <tr>
		<td> <a href="editorder.php?id='.$row-> invoice_id.'">'.$row-> invoice_id.'</a></td>
		<td>'.$row-> customer_name.'</td>
		<td>'.$row-> order_date.'</td>
		<td>'."GH&#8373; ".number_format($row-> total,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> paid,2).'</td>
		<td>'."GH&#8373; ".number_format($row-> due,2).'</td>
		
		


		';
		if($row->payment_type=="cash")
		{
		echo'<td> <span  class="label label-primary">'.$row->payment_type.'</span></td>';
		}
		elseif($row->payment_type=="card")
		{
			echo'<td> <span class="label label-info">'.$row->payment_type.'</span></td>';
		}
		elseif($row->payment_type=="check")
		{
			echo'<td> <span class="label label-danger">'.$row->payment_type.'</span></td>';
		}
		else{
			echo'<td> <span class="label label-success">'.$row->payment_type.'</span></td>';
		}
		
			  }

	 


			 ?> </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
<script>
var ctx = document.getElementById('salesByDate').getContext('2d');
var chart = new Chart(ctx, {
  // The type of chart we want to create
  type: 'bar',

  // The data for our dataset
  data: {
    labels: <?php echo json_encode($date_d);?>,
    datasets: [{
      label: 'Total Sales',
      backgroundColor: 'rgb(241,196,15)',
      borderColor: 'rgb(241,196,15)',
      data: <?php echo json_encode($total_t);?>
    }]
  },

  // Configuration options go here
  options: {}
});

//--Data table plugin
// $(document).ready(function() {
// 		$('#bestList,*#orderList').DataTable({
// 			"order": [
// 				[0, "desc"]
// 			]
// 		});
// 	});
</script>


<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>