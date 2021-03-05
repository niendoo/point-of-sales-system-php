 <?php

//database connection
include_once 'config/db_connection.php';
session_start();
if($_SESSION['email'] == "" OR $_SESSION['role'] ==""  )
{
   header('location:index.php');
}


function fill_product($connection,$pid){

$output='';

	$select=$connection->prepare("select * from product_table order by product_name asc");
	$select->execute();
    $results=$select->fetchAll();
    
	foreach($results as $result){


        $output.='<option value="'.$result["product_id"].'"';
        if($pid==$result['product_id']){
$output.='selected';

        }
        $output.='>'.$result["product_name"].' </option>';
	}
return $output;

}
$id=$_GET['id'];
$select=$connection->prepare("select * from invoice_table where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_ASSOC);

	//Variables for Invoice table date Update
	$customer_name=$row['customer_name'];
	$order_date=date('Y-m-d',strtotime($row['order_date']));
	$subtotal=$row['subtotal'];
	$tax=$row['tax'];
	$discount=$row['discount'];
	$total=$row['total'];
	$paid=$row['paid'];
	$due=$row['due'];
	$payment_type=$row['payment_type'];
	//end

//Fetching Invoice Details Table Data
$select=$connection->prepare("select * from invoice_details_table where invoice_id=$id");
$select->execute();
$row_invoice_details = $select->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST['btn_update_order'])){

// Steps for updating an order

// 1. Get values from text fields and array variables
	//Variables for Invoice table date insertion
	$txt_customer_name=$_POST['customer_name'];
	$txt_order_date=date('Y-m-d',strtotime($_POST['order_date']));
	$txt_subtotal=$_POST['subtotal'];
	$txt_tax=$_POST['tax'];
	$txt_discount=$_POST['discount'];
	$txt_total=$_POST['total'];
	$txt_paid=$_POST['paid'];
	$txt_due=$_POST['due'];
	$txt_payment_type=$_POST['rb'];
	//end

//Variables for Invoice Details table date insertion
$arr_product_id = $_POST['product_id'];
$arr_product_name = $_POST['product_name'];
$arr_product_stock = $_POST['product_stock'];
$arr_purchase_price = $_POST['purchase_price'];
$arr_product_quantity = $_POST['product_quantity']; 
$arr_total = $_POST['total']; 
// 2. Write update query for stock in the product table
foreach($row_invoice_details as $item_invoice_details){
    
   $updateProduct = $connection->prepare("update product_table set product_stock=product_stock+".$item_invoice_details['qty']." 
   where product_id='".$item_invoice_details['product_id']."'");
    $updateProduct->execute();

}

// 3. Write query to delete invoice details table data for each id selected for editing

$delete_invoice_details=$connection->prepare("delete from invoice_details_table where invoice_id=$id");
$delete_invoice_details->execute();
 

// 4. Write update query for invoice table  
$update_invoice = $connection->prepare("update invoice_table set customer_name=:customer_name,order_date=:order_date,subtotal=:subtotal
,tax=:tax,discount=:discount,total=:total,paid=:paid,due=:due,payment_type=:payment_type where invoice_id =$id");
$update_invoice->bindParam(':customer_name',$txt_customer_name,);
$update_invoice->bindParam(':order_date',$txt_order_date,);
$update_invoice->bindParam(':subtotal',$txt_subtotal,);
$update_invoice->bindParam(':tax',$txt_tax,);
$update_invoice->bindParam(':discount',$txt_discount,);
$update_invoice->bindParam(':total',$txt_total,);
$update_invoice->bindParam(':paid',$txt_paid,);
$update_invoice->bindParam(':due',$txt_due,);
$update_invoice->bindParam(':payment_type',$txt_payment_type,);
$update_invoice->execute();

//Query for Invoice Details
$invoice_id=$connection->lastInsertId();
if($invoice_id!=null){ 

for($i=0; $i<count($arr_product_id); $i++){

// 5. Write select query for product table to get out stock value
$select_product = $connection->prepare("select * from product_table where product_id='".$arr_product_id[$i]."'");
$select_product->execute();

while($row_product=$select_product->fetch(PDO::FETCH_OBJ)){

$db_stock[$i]=$row_product->product_stock;

    $remaining_stock = $db_stock[$i]-$arr_product_quantity[$i];	
    if($remaining_stock<0){
    return "Order Fails";
    }
    // 6. Write update query for product table to update the stock values
    else{
    $update=$connection->prepare("update product_table SET product_stock ='$remaining_stock' where product_id='".$arr_product_id[$i]."'");
    $update->execute();
    }


}



// 7. Write insert query for invoice details table for inserting new records
$insert=$connection -> prepare("insert into invoice_details_table(invoice_id,product_id,product_name,qty,price,order_date)
values(:invoice_id,:product_id,:product_name,:qty,:price,:order_date)");
$insert->bindParam(':invoice_id',$id);
$insert->bindParam(':product_id',$arr_product_id[$i]);
$insert->bindParam(':product_name',$arr_product_name[$i]);
$insert->bindParam(':qty',$arr_product_quantity[$i]);
$insert->bindParam(':price',$arr_purchase_price[$i]);
$insert->bindParam(':order_date',$txt_order_date);	

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
}   ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <h3 class="box-title"> <a href="orderlist.php" class="btn btn-info" role="button">
					Back To Order List </a>
            <small><?php echo 'Welcome'.' '.$_SESSION['username'];?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Order list</a></li>
            <li class="active">edit order</li>
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

                                <input type="text" class="form-control" value="<?php echo $customer_name;?>"
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
                                    value="<?php echo $order_date;?>" data-date-format="yyyy-mm-dd">
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
                                            <center><button type="button" name="addorder" class="btn btn-info  btnadd">
                                                    <span> <i class="fa fa-plus"></i>
                                                    </span></button></center>
                                        </th>


                                    </tr>
                                </thead>
 <?php
 foreach($row_invoice_details as $item_invoice_details){
    
$select=$connection->prepare("select * from product_table where product_id='{$item_invoice_details['product_id']}'");
$select->execute();
$row_product=$select->fetch(PDO::FETCH_ASSOC);

?>

<tr>
<?php 
echo'<td><input type="hidden" class="form-control product_name" name="product_name[]" value="'.$row_product['product_name'].'" style="width:50px;" readonly></td>';

echo'<td><select  class="form-control product_idd" name="product_id[]" style="width:250px;"> <option value = ""> Select Product </option>'.fill_product($connection, $item_invoice_details['product_id']).'</select> </td > ';

echo'<td><input type="text" class="form-control product_stock" name="product_stock[]" value="'.$row_product['product_stock'].'" style="width:150px;" readonly></td>';

echo'<td><input type="text" class="form-control purchase_price" name="purchase_price[]" value="'.$row_product['sale_price'].'" style="width:150px;" readonly></td>';

echo'<td><input type="number" min="1" class="form-control product_quantity" name="product_quantity[]" value="'.$item_invoice_details['qty'].'" style="width:150px;"></td>';

echo'<td><input type="text" class="form-control total" name="total[]" style="width:150px;" value="'.$row_product['sale_price']*$item_invoice_details['qty'].'" readonly ></td>';

echo'<td><center><button type="button" name="remove" class="btn btn-danger btnremove"> <span> <i class="fa fa-remove"></i></span></button ></center></td>';






?>
</tr>

<?php } ?>





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
                                <input type="text" class="form-control" name="subtotal" id="subtotal" value="<?php echo $subtotal;?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tax(5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-percent"></i>
                                </div>
                                <input type="text" class="form-control" name="tax" id="tax" readonly value="<?php echo $tax;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-percent"></i>
                                </div>
                                <input type="number" class="form-control" name="discount" id="discount" value="<?php echo $discount;?>">
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
                                <input type="text" class="form-control" name="total" id="total" value="<?php echo $total;?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Amount Paid</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span>GH&#8373;</span>
                                </div>
                                <input type="text" class="form-control" name="paid" id="paid" value="<?php echo $paid;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span>GH&#8373;</span>
                                </div>
                                <input type="text" class="form-control" name="due" id="due" value="<?php echo $due;?>" readonly>
                            </div>
                        </div>
                        <br>
                        <label>Choose Payment Method</label>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="rb" value="cash" class="flat-red" <?php echo ($payment_type=='cash')?'checked':''?> > CASH
                            </label>
                            <label>
                                <input type="radio" name="rb" value="card" class="flat-red" <?php echo ($payment_type=='card')?'checked':''?>> CARD
                            </label>
                            <label>
                                <input type="radio" name="rb" value="check" class="flat-red" <?php echo ($payment_type=='check')?'checked':''?>> CHECK
                            </label>
                            <label>
                                <input type="radio" name="rb" value="momo" class="flat-red" <?php echo ($payment_type=='momo')?'checked':''?>> MOMO

                            </label>
                        </div>
                    </div>

                </div><!-- Tax and discount box -->
                <hr>
                <div align="center ">

                    <input type="submit" name="btn_update_order" value="Update Order" class="btn btn-info "
                        title="Update Order">
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
            //Initialize Select2 Elements
            $('.product_idd').select2()

//fetching product other fields matching details
$(".product_idd").on('change', function(e) {


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
            $("#paid").val("");
        }

    })
})
    //add button code
    $(document).on('click', '.btnadd', function() {

        var html = '';
        html += '<tr>';
        html +=
            '<td><input type="hidden " class="form-control product_name" name="product_name[]" style="width:50px;" readonly></td>';

        html +=
            '<td><select  class="form-control product_id" name="product_id[]" style="width:250px;"> <option value = ""> Select Product </option><?php echo fill_product($connection,''); ?></select> </td > ';

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
                    $("#paid").val("");
                }

            })
        })

    })
    //remove button code
    $(document).on('click', '.btnremove', function() {
        $(this).closest('tr').remove();
        calculate(0, 0);
    
        $("#paid").val("");
    }); //remove button code end

    //calculate total cost of products
    $("#product_table").delegate(".product_quantity", "keyup change", function() {
        var quantity = $(this);
        var tr = $(this).parent().parent();
        $("#paid").val("");

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