<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>

<!-----workspace start----->


 <div class="cmtop"></div>
  

 <div class="container cmpad cartmanager" style="margin-top: 20px;">

<style>
@media(max-width:768px)
{
    div.list-group{ white-space: inherit !important; }
}
</style>

<div class="row">

  <div class="col-md-12">
    <ul class="breadcrumb">
      <li><a href=""><span class="fa fa-home"></span> Home</a></li>
      <li>My Orders</li>
    </ul>
  </div><!-- col-md-12 -->
<style>
        .side-menu {
            background: #ffffff;
            border: 1px solid #f1efef;
            border-radius: 6px;
            padding: 15px;
        }

        .side-menu a {
            display: block;
            padding: 8px 10px;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            border-bottom: solid 1px;
            border-color: #ececed;
        }

        .side-menu a:hover {
            background: #e7e7e7;
        }

        .content-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
        }
    </style>

  <div class="col-md-12 cartmainbox">
                        <div class="col-sm-12">
          <?php
          $msg2 = $session->get('success');
          if (!empty($msg2)) { ?>

            <div class="alert alert-success alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong><i style="font-size: 25px;" class="fa fa-check-square-o"></i></strong> <?= $msg2 ?>
            </div>
            <?php if ($session->get('success') === 'Order Placed Successfully' && !empty($orderdata)) {
                $latestOrder = $orderdata[0];
            ?>
            <script>
            if (typeof fbq === 'function') {
                var purchaseContentIds = [];
                <?php foreach ($orderdata as $ord): ?>
                purchaseContentIds.push('<?= $ord->product_id ?? "" ?>');
                <?php endforeach; ?>

                fbq('track', 'Purchase', {
                    value: parseFloat('<?= $latestOrder->amount ?? 0 ?>'),
                    currency: 'INR',
                    content_type: 'product',
                    content_ids: purchaseContentIds,
                    order_id: '<?= $latestOrder->order_id ?? "" ?>'
                });
            }
            </script>
            <?php } ?>
            <?php } ?>
             <?php
          $msg2 = $session->get('error');
          if (!empty($msg2)) { ?>

            <div class="alert alert-danger alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong><i style="font-size: 25px;" class="fa fa-check-square-o"></i></strong> <?= $msg2 ?>
            </div> <?php } ?>
  
        </div>


<?= $this->include('web/leftmenu') ?>




    <!-- track MODAL start -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

   
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Track your order</h4>
      </div>

      <div class="modal-body">
        <p><img src="<?= base_url('asset/images/caution.png') ?>" width="50px"><br>
          To track your shipment, copy the tracking code below and click the track button. On the next screen, you can track using the tracking code you copied.<br>
    <span id="track_code" style="font-weight:bold; color:#000;"></span>
    &nbsp;<span class="again text-center" onclick="copyTrackCode()">
      Copy <i class="fa fa-copy"></i>
    </span>
        </p>
        <p>   <a id="track_btn" href="#" target="_blank">
      <span class="track text-center">Track your order &nbsp;<i class="fa fa-map-marker"></i></span>
    </a></p>
      </div>



    </div>

  </div>
</div>


  <!-- track MODAL end -->


  <!-- cancel MODAL start -->
<!-- cancel MODAL -->
<!-- CANCEL ORDER MODAL -->
<div id="myModal_cancel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Customer Order Cancellation</h4>
      </div>

      <div class="modal-body">
        <form action="<?= base_url('cancelOrder') ?>" method="POST">

            <!-- Hidden Inputs -->
            <input type="hidden" name="order_id" id="cancel_order_id">
            <input type="hidden" name="product_id" id="cancel_product_id">

            <!-- Reason Dropdown -->
            <div class="form-group">
                <h5>Cancellation Reason</h5>
                <select class="form-control" name="reason" required>
                    <option value="">Select Reason</option>
                    <option value="1">I ordered by mistake</option>
                    <option value="2">I no longer need the product</option>
                    <option value="3">I found a better price elsewhere</option>
                    <option value="4">I want to change the delivery address</option>
                    <option value="5">I want to buy a different product</option>
                    <option value="6">I selected the wrong size/color</option>
                    <option value="7">Delivery is taking too long</option>
                    <option value="8">I changed my mind</option>
                    <option value="9">Ordered multiple items by mistake</option>
                    <option value="10">Other</option>
                </select>
            </div>

            <!-- Remarks -->
            <div class="form-group">
                <textarea class="form-control" name="remarks" placeholder="Additional note (optional)"></textarea>
            </div>

            <!-- Submit -->
            <button class="btn btn-primary btn-block" type="submit">
                Cancel Order
            </button>

        </form>
      </div>

    </div>

  </div>
</div>


  <!-- cancel MODAL end -->



    <div class="col-md-9 boxcol">

      <div class="">
      <h2 class="subhd hdng">My Orders</h2>   


      



	<?php foreach ($orderdata as $order): ?>
    <?php
$price  = (float)$order->price;
$offer  = (float)$order->if_offer_per_price;
$tax    = isset($order->tax) ? (float)$order->tax : 0;

// price after discount
$offer_price = $price - ($price * $offer / 100);

// price after discount + tax
$final_price = $offer_price + ($offer_price * $tax / 100);
?>


        <?php 
    // Add 4 days to created_date
    $estimated = date("d-m-Y", strtotime($order->created_date . " +4 days"));
?>

<div class="container-fluid cartitem cartbox<?= $order->id ?>" style="margin-bottom: 12px;">

    <!-- LEFT PRODUCT IMAGE -->
    <div class="col-md-2 col-xs-4 item nopadcart">  
        <a href="#">
            <img src="<?= base_url('uploads/products/' . $order->img) ?>" class="loadimg">
        </a>
    </div>

    <!-- PRODUCT DETAILS -->
    <div class="col-md-7 col-xs-8 details nopadcartr" style="margin-bottom: 10px;">

        <span class="brand"><?= $order->product_name ?></span><br>

        <a href="#" class="name">
            <?= $order->veriant ?>
        </a>

        <div class="price-con">

            <!-- PRICE -->
            <p class="price">
                <span class="fa fa-inr"></span> <?= number_format($final_price, 2) ?>

            </p>
            <p class="price" style="font-size:12px;color:#646565;">
                <span class="fa fa-dot-circle-o"></span>
                Order ID: <?= $order->order_id ?>
            </p>
            <!-- ORDER STATUS -->
            <p class="price" style="font-size:12px;color:#646565;">
                <span class="fa fa-dot-circle-o"></span>
                Order Status: 
                <?php 
                    switch ($order->ord_status) {
                        case "P":
                            echo "Not Dispatched";
                            break;
                        case "S":
                            echo "Shipped";
                            break;
                        case "C":
                            echo "Cancelled";
                            break;
                        case "D":
                            echo '<span style="color:#4caf50;">Delivered</span>';
                            break;
                        default:
                            echo $order->ord_status;
                    }
                ?>
            </p>

            <!-- ETA -->
      <p class="price" style="font-size:12px;color:#0591f4;">
        <span class="fa fa-clock-o"></span>
        Estimated arrival: <?= $estimated ?>
    </p>

            <!-- ACTION BUTTONS -->
           <?php
$status = $order->ord_status;
?>

<!-- TRACK BUTTON -->
<?php if ( $status == 'S'): ?>
    <span class="track text-center"
          onclick="openTrackModal('<?= $order->track_id ?>', '<?= $order->track_url ?>')">
        Track <i class="fa fa-map-marker"></i>
    </span>
<?php endif; ?>


<!-- INVOICE BUTTON -->
<?php if ( $status == 'S' || $status == 'R'): ?>
    <span class="download text-center"
        onclick="window.open('<?= base_url("invoice/$order->ord_id") ?>', '_blank')">
        Download Invoice <i class="fa fa-file-text-o"></i>
    </span>
<?php endif; ?>

<!-- CANCEL BUTTON -->
<?php if ($status == 'P'): ?>
    <span class="cancel text-center"
        data-toggle="modal"
        data-target="#myModal_cancel"
        onclick="setCancelData('<?= $order->ord_id ?>', '<?= $order->variant ?>')">
        Cancel Order <i class="fa fa-times-circle-o"></i>
    </span>
<?php endif; ?>



        </div>
    </div>

    <!-- RIGHT SIDE ACTIONS -->
    <div class="col-md-3 col-xs-12 count mobcartaction nopadcartr">

        <span class="again text-center" onclick="window.location.href='<?= base_url('product-details/'.$order->product_id) ?>'" >
            Buy Again <i class="fa fa-hand-o-right"></i>
        </span>

        <p class="again" style="font-size:12px;color:#49ad18;">
            Order: <?= date("d-m-Y", strtotime($order->created_date)) ?>
        </p>

        <div class="clearfix"></div>
        <br>
    </div>

</div>

<?php endforeach; ?>







			   



          
</div>

    </div><!-- col-md-8 -->


    



  </div><!-- col-md-12 -->


  </div><!-- row -->
  
  
<!-- =============TABBED CONTENT=============== -->
<style>
    .bhoechie-tab-menu{text-align:center;}
    .list-group-item{width:170px;margin-right:5px;float:left;}
    .list-group{display:inline-block;width:auto;margin:0 auto;}
    
    @media(max-width:1200px){.list-group-item{width:150px;} }
    @media(max-width:1070px){.list-group-item{width:200px;} }
    @media(max-width:992px){.list-group-item{width:auto;} }
</style>




 </div><!-- container-fluid cmpad -->
  </div>







  <!-----workspace end----->
 
<script>
function setCancelData(order_id, product_id) {
    document.getElementById('cancel_order_id').value = order_id;
    document.getElementById('cancel_product_id').value = product_id;
}
function openTrackModal(track_id, track_url) {
    document.getElementById('track_code').textContent = track_id;
    document.getElementById('track_btn').href = track_url;
    $('#myModal').modal('show');
}

function copyTrackCode() {
    let code = document.getElementById('track_code').textContent;
    navigator.clipboard.writeText(code);
  
}

if (track_url === "") {
    document.getElementById('track_btn').style.pointerEvents = "none";
    document.getElementById('track_btn').style.opacity = "0.5";
} else {
    document.getElementById('track_btn').style.pointerEvents = "auto";
    document.getElementById('track_btn').style.opacity = "1";
}


</script>




  <?= $this->endSection() ?>
  