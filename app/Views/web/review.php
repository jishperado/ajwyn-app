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
        <p><img src="images/caution.png" width="50px"><br>
          To track your shipment, copy the tracking code below and click the track button. On the next screen, you can track using the tracking code you copied.<br>
          Tracking Code: xrtyuo463#dsfr &nbsp;<span class="again text-center">Copy <i class="fa fa-copy"></i></span>
        </p>
        <p> <a href="" target="_blank"><span class="track text-center"  onclick="" >Track your order &nbsp;<i class="fa fa-map-marker"></i></span></a></p>
      </div>



    </div>

  </div>
</div>


  <!-- track MODAL end -->


  <!-- cancel MODAL start -->
<div id="myModal_cancel" class="modal fade" role="dialog">
  <div class="modal-dialog">

   
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Customer Order Cancellation</h4>
      </div>

      <div class="modal-body">
       <form>


            <div class="form-group">
                   <h5>Cancellation Reason </h5>
                   <select class="form-control">
                    <option>Select Reason</option>
                    <option value="">I ordered by mistake</option>
                    <option value="">I no longer need the product</option>
                    <option value="">I found a better price elsewhere</option>
                    <option value="">I want to change the delivery address</option>
                    <option value="">I want to buy a different product</option>
                    <option value="">I selected the wrong size/color</option>
                    <option value="">Delivery is taking too long</option>
                    <option value="">I changed my mind</option>
                    <option value="">Ordered multiple items by mistake</option>
                    <option value="">Other</option>

                   </select>
                </div>
            
            <div class="form-group">
                    
                   <textarea class="form-control" placeholder="Cancellation Reason"></textarea>
                </div>

                


                <button class="btn btn-primary btn-block">Cancel Order</button>
            
          </form>
      </div>



    </div>

  </div>
</div>


  <!-- cancel MODAL end -->


<div class="col-md-9 boxcol">
      <div class="">
      <h2 class="subhd hdng">Review & Rating</h2>   

<style>
.rating {
  direction: rtl;
  unicode-bidi: bidi-override;
}
.rating > label {
  color: #ddd;
  font-size: 20px;
  padding: 0 5px;
  cursor: pointer;
}
.rating > input {
  display: none;
}
.rating > input:checked ~ label,
.rating > label:hover,
.rating > label:hover ~ label {
  color: #ffc107;
}
</style>

			 <?php foreach ($orderdata as $order): ?>

    <?php 
        // Calculate dates
        $orderDate      = date("d/m/Y", strtotime($order->created_date));
        $dispatchDate   = !empty($order->dispatched_date) ? date("d/m/Y", strtotime($order->dispatched_date)) : "";
        $deliveryDate   = date("d/m/Y", strtotime($order->created_date . " +4 days"));
    ?>

<div class="container-fluid cartitem cartbox<?= $order->id ?>" style="margin-bottom: 10px;">
    
    <!-- PRODUCT IMAGE -->
    <div class="col-md-2 col-xs-4 item nopadcart">  
        <a href="">
            <img src="<?= base_url('uploads/products/' . $order->img) ?>" 
                 data-src="<?= base_url('uploads/products/' . $order->img) ?>" 
                 class="loadimg">
        </a>
    </div>

    <!-- PRODUCT DETAILS -->
    <div class="col-md-7 col-xs-8 details nopadcartr" style="margin-bottom: 10px;">  
        
        <span class="brand"><?= esc($order->product_name) ?></span><br>

        <a href="" class="name"><?= esc($order->veriant) ?></a>

        <div class="price-con">

            <!-- PRICE -->
            <p class="price">
                <span class="fa fa-inr"></span> <?= esc($order->price) ?>
            </p>

            <!-- ORDER STATUS -->
            <p class="price" style="font-size: 12px; color: #646565;">
                <span class="fa fa-dot-circle-o"></span>
                Order Status:
                <?php if (!empty($dispatchDate)): ?>
                    Dispatched on <?= $dispatchDate ?>
                <?php else: ?>
                    <?= $order->ord_status ?? "Not Dispatched" ?>
                <?php endif; ?>
            </p>

            <!-- PRODUCT DELIVERY DATE -->
            <p class="price" style="font-size: 12px; color: #0591f4;">
                <span class="fa fa-clock-o"></span>
                Product delivery date: <?= $deliveryDate ?>
            </p>

            <!-- ⭐ DO NOT TOUCH THIS AREA (YOUR RATING FORM) ⭐ -->
            <div style="height: 30px; background-color: #fefefe; width: fit-content; line-height: 33px; border-radius: 3px; box-shadow: 0 3px 12px rgba(0,0,0,0.12);">

          <form action="<?= base_url('review') ?>" method="post">

    <input type="hidden" name="product_id" value="<?= $order->product_id ?>">

    <div class="rating" align="left">

        <?php 
            $oldRating = !empty($order->rating_info) ? $order->rating_info->rating : 0;
        ?>

        <input type="radio" name="rating" id="star5-<?= $order->id ?>" value="5" 
            <?= $oldRating == 5 ? 'checked' : '' ?>>
        <label for="star5-<?= $order->id ?>" class="fa fa-star"></label>

        <input type="radio" name="rating" id="star4-<?= $order->id ?>" value="4"
            <?= $oldRating == 4 ? 'checked' : '' ?>>
        <label for="star4-<?= $order->id ?>" class="fa fa-star"></label>

        <input type="radio" name="rating" id="star3-<?= $order->id ?>" value="3"
            <?= $oldRating == 3 ? 'checked' : '' ?>>
        <label for="star3-<?= $order->id ?>" class="fa fa-star"></label>

        <input type="radio" name="rating" id="star2-<?= $order->id ?>" value="2"
            <?= $oldRating == 2 ? 'checked' : '' ?>>
        <label for="star2-<?= $order->id ?>" class="fa fa-star"></label>

        <input type="radio" name="rating" id="star1-<?= $order->id ?>" value="1"
            <?= $oldRating == 1 ? 'checked' : '' ?>>
        <label for="star1-<?= $order->id ?>" class="fa fa-star"></label>
    </div>
                </div>

    <div class="form-group" style="margin-top: 15px;">

        <textarea name="review" class="form-control" placeholder="Write a review" rows="3">
<?= !empty($order->rating_info) ? esc($order->rating_info->review) : '' ?>
        </textarea>

    </div>

    <button class="btn btn-primary btn-block">
        <?= empty($order->rating_info) ? 'Submit' : 'Update Review' ?>
    </button>

</form>


            <!-- ⭐ END OF UNTOUCHED FORM ⭐ -->

        </div>
    </div>

    <!-- BUY AGAIN / ORDER DATE -->
    <div class="col-md-3 col-xs-12 count mobcartaction nopadcartr">  
        
        <span class="again text-center" onclick="removecart('<?= $order->id ?>')">
            Buy Again <i class="fa fa-hand-o-right"></i>
        </span>

        <p class="again" style="font-size: 12px; color: #49ad18;">
            Order: <?= $orderDate ?>
        </p>

        <div class="clearfix"></div><br>

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
 







  <?= $this->endSection() ?>
  