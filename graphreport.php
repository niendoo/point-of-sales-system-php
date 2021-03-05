<?php 

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] =="Sales"  )
{
   header('location:index.php');
}
error_reporting(0);
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
        <li><a href="#"><i class="fa fa-dashboard"></i>Sales Report</a></li>
            <li class="active">Graph Report</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content container-fluid">

		<!--------------------------
        | Your Page Content Here |
        -------------------------->
		<div class="box box-primary">
        <form role="form" action="" method="post">
        <div class="box-header with-border">
                <h3 class="box-title">From: <?php echo $_POST['date_1'];?> >>> <span>To: <?php echo $_POST['date_2'];?></span></h3>
            </div>
			<!-- /.box-header -->
			<!-- form start -->

			<div class="box-body">    
                
            
            <div class="row">
                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker1" name="date_1"
                                    data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker2" name="date_2"
                                    data-date-format="yyyy-mm-dd">
                            </div>


                        </div>

                        <div class="col-md-2">
                            <div align="left">

                                <input type="submit" name="btn_date_filter" value="Filter By Date"
                                    class="btn btn-success " title="Filter By Date">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <br>
          
<?php

$select = $connection -> prepare("select order_date, sum(total) as price from invoice_table where order_date between :from_date AND :to_date group by order_date");
$select->bindParam(':from_date',$_POST['date_1']);
$select->bindParam(':to_date',$_POST['date_2']);

$select -> execute();
$total=[];
$date=[];
while ($row=$select->fetch(PDO::FETCH_ASSOC)){
extract($row);
$total[]=$price;
$date[]=$order_date;


}

?>





<div class="chart"> <canvas id="myChart" style="height: 300px;"></canvas></div>

<?php

$select = $connection -> prepare("select product_name, sum(qty) as quantity from invoice_details_table where order_date between :from_date AND :to_date group by product_id");
$select->bindParam(':from_date',$_POST['date_1']);
$select->bindParam(':to_date',$_POST['date_2']);

$select -> execute();
$productName=[];
$quantity=[];
while ($row=$select->fetch(PDO::FETCH_ASSOC)){
extract($row);
$productName[]=$product_name;
$qty[]=$quantity;


}

?>
<hr>
<br>
<div class="chart"> <canvas id="bestSellers" style="height: 300px;"></canvas></div>

                    
               

            </div>
            </form>
		</div>

	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($date); ?>,
        datasets: [{
            label: 'Total Sales',
            backgroundColor: 'rgb(241,196,15)',
            borderColor: 'rgb(241,196,15)',
            data: <?php echo json_encode($total); ?>
        }]
    },

    // Configuration options go here
    options: {}
});

//Date picker
$('#datepicker1').datepicker({
    autoclose: true
});
//Date picker
$('#datepicker2').datepicker({
    autoclose: true
});


</script>
<script>

var ctx = document.getElementById('bestSellers').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($productName); ?>,
        datasets: [{
            label: 'Total Quantity Sold',
            backgroundColor: 'rgb(46,204,113)',
            borderColor: 'rgb(46,204,113)',
            data: <?php echo json_encode($qty); ?>
        }]
    },

    // Configuration options go here
    options: {}
});

//Date picker
$('#datepicker1').datepicker({
    autoclose: true
});
//Date picker
$('#datepicker2').datepicker({
    autoclose: true
});


</script>
<?php include_once'templates/footer.php';    ?>
