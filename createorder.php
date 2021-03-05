<?php

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] ==""  )
{
   header('location:index.php');
}


function fill_product($connection){

$output='';

	$select=$connection->prepare("select * from product_table order by product_name asc");
	$select->execute();
	$results=$select->fetchAll();
	foreach($results as $result){
		$output.='<option value="'.$result["product_id"].'"> '.$result["product_name"].' </option>';
	}
return $output;

}



if(isset($_POST['btn_save_order'])){

	//Variables for Invoice table date insertion
	$customer_name=$_POST['customer_name'];
	$order_date=date('Y-m-d',strtotime($_POST['order_date']));
	$subtotal=$_POST['subtotal'];
	$tax=$_POST['tax'];
	$discount=$_POST['discount'];
	$total=$_POST['total'];
	$paid=$_POST['paid'];
	$due=$_POST['due'];
	$payment_type=$_POST['rb'];
	//end

//Variables for Invoice Details table date insertion
$arr_product_id = $_POST['product_id'];
$arr_product_name = $_POST['product_name'];
$arr_product_stock = $_POST['product_stock'];
$arr_purchase_price = $_POST['purchase_price'];
$arr_product_quantity = $_POST['product_quantity']; 
$arr_total = $_POST['total'];



	$insert=$connection->prepare("insert into invoice_table(customer_name,order_date,subtotal,tax,discount,total,paid,due,payment_type)values
	(:customer_name,:order_date,:subtotal,:tax,:discount,:total,:paid,:due,:payment_type)");
	$insert->bindParam(':customer_name',$customer_name,);
	$insert->bindParam(':order_date',$order_date,);
	$insert->bindParam(':subtotal',$subtotal,);
	$insert->bindParam(':tax',$tax,);
	$insert->bindParam(':discount',$discount,);
	$insert->bindParam(':total',$total,);
	$insert->bindParam(':paid',$paid,);
	$insert->bindParam(':due',$due,);
	$insert->bindParam(':payment_type',$payment_type,);
	$insert->execute();

//Query for Invoice Details
$invoice_id=$connection->lastInsertId();
if($invoice_id!=null){

for($i=0; $i<count($arr_product_id); $i++){

$remaining_stock = $arr_product_stock[$i]-$arr_product_quantity[$i];	
if($remaining_stock<0){
return "Order Fails";
}
else{
$update=$connection->prepare("update product_table SET product_stock ='$remaining_stock' where product_id='".$arr_product_id[$i]."'");
$update->execute();
}

$insert=$connection -> prepare("insert into invoice_details_table(invoice_id,product_id,product_name,qty,price,order_date)
values(:invoice_id,:product_id,:product_name,:qty,:price,:order_date)");
$insert->bindParam(':invoice_id',$invoice_id);
$insert->bindParam(':product_id',$arr_product_id[$i]);
$insert->bindParam(':product_name',$arr_product_name[$i]);
$insert->bindParam(':qty',$arr_product_quantity[$i]);
$insert->bindParam(':price',$arr_purchase_price[$i]);
$insert->bindParam(':order_date',$order_date);	

$insert->execute();


}
// echo "Order has been successfully created";
header('location:orderlist.php');

}



}



if($_SESSION['role'] =="Admin"  )
{
include_once'templates/header.php'; 
}
elseif($_SESSION['role'] =="Sales"  )
{
    include_once'templates/salesheader.php';
}
   ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create Order
            <small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Orders</a></li>
            <li class="active">Create Order</li>
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
                    <h3 class="box-title">Create New Order</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>

                                <input type="text" class="form-control" placeholder="Enter Customer Name"
                                    name="customer_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Choose Date:</label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker" name="order_date"
                                    value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                </div><!-- Customer and date box -->
                <div class="box-body">
                    <div class="col-md-12">
                        <div style="overflow-x: auto">
                            <table id="product_table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Search Product</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>
                                            <center><button type="button" name="addorder"
                                                    class="btn btn-success btnadd"> <span> <i class="fa fa-plus"></i>
                                                    </span></button></center>
                                        </th>


                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!-- Table box -->
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sub-total</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span>GH&#8373;</span>
                                </div>
                                <input type="text" class="form-control" name="subtotal" id="subtotal" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tax(5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-percent"></i>
                                </div>
                                <input type="text" class="form-control" name="tax" id="tax" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-percent"></i>
                                </div>
                                <input type="number" class="form-control" name="discount" id="discount">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span>GH&#8373;</span>
                                </div>
                                <input type="text" class="form-control" name="total" id="total" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amount Paid</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span>GH&#8373;</span>
                                </div>
                                <input type="text" class="form-control" name="paid" id="paid">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span>GH&#8373;</span>
                                </div>
                                <input type="text" class="form-control" name="due" id="due" readonly>
                            </div>
                        </div>
                        <br>
                        <label>Choose Payment Method</label>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="rb" value="cash" class="flat-red" checked> CASH
                            </label>
                            <label>
                                <input type="radio" name="rb" value="card" class="flat-red"> CARD
                            </label>
                            <label>
                                <input type="radio" name="rb" value="check" class="flat-red"> CHECK
                            </label>
                            <label>
                                <input type="radio" name="rb" value="momo" class="flat-red"> MOMO

                            </label>
                        </div>
                    </div>

                </div><!-- Tax and discount box -->
                <hr>
                <div align="center ">

                    <input type="submit" name="btn_save_order" value="Save Order" class="btn btn-success "
                        title="Save Order">
                </div>
                <hr>
            </form>
        </div>

    </section>
    <!-- /.content -->
</div>

<script>
//Date picker
$('#datepicker').datepicker({
    autoclose: true
});
//Flat red color scheme for iCheck
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
})

$(document).ready(function() {
    //add button code
    $(document).on('click', '.btnadd', function() {

        var html = '';
        html += '<tr>';
        html +=
            '<td><input type="hidden " class="form-control product_name" name="product_name[]" style="width:50px;" readonly></td>';

        html +=
            '<td><select  class="form-control product_id" name="product_id[]" style="width:250px;"> <option value = ""> Select Product </option><?php echo fill_product($connection); ?></select> </td > ';

        html +=
            '<td><input type="text" class="form-control product_stock" name="product_stock[]" style="width:150px;" readonly></td>';

        html +=
            '<td><input type="text" class="form-control purchase_price" name="purchase_price[]" style="width:150px;" readonly></td>';

        html +=
            '<td><input type="number" min="1" class="form-control product_quantity" name="product_quantity[]" style="width:150px;"></td>';

        html +=
            '<td><input type="text" class="form-control total" name="total[]" style="width:150px;" readonly ></td>';

        html +=
            '<td><center><button type="button" name="remove" class="btn btn-danger btnremove"> <span> <i class="fa fa-remove"></i></span></button ></center></td>';

        $('#product_table').append(html);
        //Initialize Select2 Elements
        $('.product_id').select2()

        //fetching product other fields matching details
        $(".product_id").on('change', function(e) {


            var product_id = this.value;
            var tr = $(this).parent().parent();
            $.ajax({
                url: "getproduct.php",
                method: "get",
                data: {
                    id: product_id
                },
                success: function(data) {
                    //console.log(data);

                    tr.find(".product_name").val(data["product_name"]);
                    tr.find(".product_stock").val(data["product_stock"]);
                    tr.find(".purchase_price").val(data["sale_price"]);
                    tr.find(".product_quantity").val(1);
                    tr.find(".total").val(tr.find(".product_quantity").val() * tr
                        .find(".purchase_price").val());
                    calculate(0, 0);
                }

            })
        })

    })
    //remove button code
    $(document).on('click', '.btnremove', function() {
        $(this).closest('tr').remove();
        calculate(0, 0);
        $("#paid").val(0);
    }); //remove button code end

    //calculate total cost of products
    $("#product_table").delegate(".product_quantity", "keyup change", function() {
        var quantity = $(this);
        var tr = $(this).parent().parent();

        if ((quantity.val() - 0) > (tr.find(".product_stock").val() - 0)) {
            swal("Warning!", "Sorry, You Have Exceeded Product Stock Limit", "warning");
            quantity.val(1);
            tr.find(".total").val(quantity.val() * tr.find(".purchase_price").val());
            calculate(0, 0);
        } else {
            tr.find(".total").val(quantity.val() * tr.find(".purchase_price").val());
            calculate(0, 0);
        }
    })

    function calculate(dis, paid) {
        var subtotal = 0;
        var tax = 0;
        var discount = dis;
        var net_total = 0;
        var paid_amount = paid;
        var due = 0;




        $(".total").each(function() {
            subtotal = subtotal + ($(this).val() * 1);

        })
        tax = 0.05 * subtotal;
        net_total = tax + subtotal;
        net_total = net_total - discount;
        due = net_total - paid_amount;

        $("#subtotal").val(subtotal.toFixed(2));
        $("#total").val(net_total.toFixed(2));
        $("#tax").val(tax.toFixed(2));
        $("#discount").val(discount);
        $("#due").val(due.toFixed(2));

    } //calculate function end
    $("#discount").keyup(function() {
        var discount = $(this).val();
        calculate(discount, 0);

    })

    $("#paid").keyup(function() {
        var paid = $(this).val();
        var discount = $("#discount").val();
        calculate(discount, paid);
    })




});
</script>
<!-- /.content-wrapper -->
<?php include_once'templates/footer.php';    ?>