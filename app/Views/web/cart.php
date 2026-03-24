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
      <li>Cart</li>
    </ul>
  </div><!-- col-md-12 -->

  <div class="col-md-12 cartmainbox">



    <div class="col-md-8 boxcol cart">
      <h2 class="subhd hdng">My Cart</h2>   


     <!--- <div class="emptycart">
      	<img src="images/emptycart.svg" style="width: 100px;"  alt="empty" class="img-responsive center-block">
      	
      	<h3>Your cart is empty</h3>
      	<p>Add items to it now</p>
      </div>

<div class="container-fluid btncont">
      <a href="" class="btn btn-continue"><img style="width: 20px;" src="images/cartd.svg" alt="empty" class=""> Continue Shopping</a>
		    </div>--->

<?php foreach ($cartdata as $item): ?>
 

<?php  
    // Main price  
    $price = (float)$item->price;

    // Offer percentage
    $offer = (float)$item->if_offer_per_price;

    // price after discount
    $offer_price = $price - ($price * $offer / 100);

    // tax percentage
    $tax = isset($item->tax) ? (float)$item->tax : 0;

    // final price = discount → then tax
    $final_price = $tax > 0 ?  round($offer_price + ($offer_price * $tax / 100),2) : $offer_price;
    $price = $tax > 0 ? round($price + ($price * $tax / 100), 2) : $price;
?>


<div class="container-fluid cartitem cartbox<?= $item->cart_id ?>">

    <!-- Image -->
    <div class="col-md-2 col-xs-4 item nopadcart">  
        <a href="">
            <img src="<?= base_url('uploads/products/'.$item->img) ?>"
                 data-src="<?= base_url('uploads/products/'.$item->img) ?>"
                 class="loadimg">
        </a>
    </div>

    <!-- Product Details -->
    <div class="col-md-7 col-xs-8 details nopadcartr">  

        <span class="brand"><?= $item->type_name ?></span>

        <a href="" class="name">
            <?= $item->product_name ?> (<?= $item->veriant ?>)
        </a>

        <div class="price-con">

            <p class="price">
                <span class="fa fa-inr"></span>
                <?= number_format($final_price, 2) ?>
            </p>

            <?php if ($offer > 0): ?>
            <p class="price-x">
                <span class="fa fa-inr"></span>
                <?= number_format($price, 2) ?>
      
            </p>
       
                  <span style="font-weight: bold;color: red" class="soldout <?= $item->qty > 0 ? 'd-block' : 'd-none' ?>">
              <?= $item->qty > 0 ? '' : 'Sold Out' ?>
            </span>
            <?php endif; ?>

        </div>
    </div>

    <!-- Quantity + Remove -->
    <div class="col-md-3 col-xs-12 count mobcartaction nopadcartr">  

        <form class="cart-count">

            <!-- Minus -->
  <span class="btn">
    <span class="fa fa-minus-square-o" onclick="reducecart('<?= $item->cart_id ?>')"></span>
</span>

<input class="num cartnum cart-qty"
       type="number"
       id="num<?= $item->cart_id ?>"
       name="quantity"
       value="<?= $item->quantity ?>"
       data-cart-id="<?= $item->cart_id ?>"
       data-proprice="<?= $final_price ?>">

<span class="btn">
    <span class="fa fa-plus-square-o" onclick="addtocart('<?= $item->cart_id ?>')"></span>
</span>


        </form>

        <!-- Remove -->
   <span class="remove text-center delete-item"
      data-url="<?= base_url('deletepro/' . $item->cus_id . '/' . $item->cart_id) ?>">
    Remove <i class="fa fa-trash-o"></i>
</span>


        <div class="clearfix"></div>
        <br>
    </div>

</div>

<?php endforeach; ?>

    				    
    	
   

    <div class="container-fluid btncont">
      <a href="<?= base_url('/') ?>" class="btn btn-continue" style="font-size: 13px;"><img style="width: 18px;" src="<?= base_url('asset/images/cart-b.svg') ?>" class=""> Continue Shopping</a>
		      <a href="javascript:void(0);" id="logtocntinue" class="btn btn-order moqcheck">
    <img style="width: 18px;" src="<?= base_url('asset/images/buynow.svg') ?>"> 
    Place Order
</a>

<form id="placeOrderForm" action="" method="POST" style="display:none;">
    <!-- Add hidden fields if needed -->
<input type="hidden" name="one"  value="one">
</form>

            </div><!-- container-fluid -->

    </div><!-- col-md-8 -->


<?php  
$item_count = 0;
$subtotal = 0;
$total_discount = 0;
$tax_amount = 0;

// shipping (optional: dynamic or fixed)
$shipping_cost = 0;
$grand_total = 0;

foreach ($cartdata as $item) {

    $item_count += $item->quantity;

    $price  = (float)$item->price;
    $offer  = (float)$item->if_offer_per_price;
    $tax    = isset($item->tax) ? (float)$item->tax : 0;
    $totalPriceWithTax = $tax > 0 ? round( $price + ($price * $tax / 100),2) : $price;
    $totalPrice = $totalPriceWithTax * $item->quantity;
    $subtotal += $totalPrice;

    $offer_price = $offer > 0 ? round($price - ($price * $offer / 100),2) : $price;
    $offer_price = $tax > 0 ? round($offer_price + ($offer_price * $tax / 100),2) : $offer_price;
    $offer_price = $offer_price * $item->quantity;
    $grand_total += $offer_price;
    

    
}

// final payable (subtotal already contains tax)

?>




    <div class="col-md-4 boxcol ">
 <div class="row">
              <div class="deliverybox">
          
                    
          <!--<span class="title"><i class="fa fa-map-marker"></i>Delivery by 18th November</span>-->
          <label>Enter Promo Code</label>
          <form>
            
            <input type="number" name="pincode" value="" placeholder="Code">
            <a href="#" class="change">Apply Now</a>
          </form></div></div>
      
      <h2 class="subhd">Order Summary</h2> 

      <div class="table-responsive cartpage">
<table class="table table-cartsummary">
    <tbody>

        <!-- Subtotal -->
        <tr>
            <td><strong>Subtotal (<span id="itcnt"><?= $item_count ?></span> Item)</strong></td>
            <td><strong>₹ <span id="crtamnt"><?= number_format($subtotal, 2) ?></span></strong></td>
        </tr>

        <!-- Shipping 
        <tr>
            <td>Shipping Cost</td>
            <td>₹ <span id="shipCost"><?= number_format($shipping_cost, 2) ?></span></td>
        </tr>

        -->
        <tr>
            <td style="color: green;">Discount</td>
            <td style="color: green;">₹ 
                <span id="discount"><?= number_format($subtotal - $grand_total, 2) ?></span>
            </td>
        </tr>

        <!-- Tax
        <tr >
            <td>Estimated Tax (%)</td>
            <td>₹ <span id="taxAmt"><?= number_format($tax_amount, 2) ?></span></td>
        </tr>

        -->
        <tr class="total">
            <td>Amount Payable</td>
            <td>₹ <span id="ttsummnt"><?= number_format($grand_total, 2) ?></span></td>
        </tr>

    </tbody>
</table>

      </div><!-- table-responsive -->
    </div><!-- col-md-4 -->



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
<script src="<?= base_url('asset/js/mymain.js')?>"></script>
<script src="<?= base_url('asset/js/swiper.min.js')?>"></script>

<script>
$(document).on('click', '.delete-item', function () {
    let url = $(this).data("url");
    window.location.href = url;
});

$("#logtocntinue").on("click", function () {
    var contentIds = [];
    <?php foreach ($cartdata as $item): ?>
    contentIds.push('<?= $item->product_id ?>');
    <?php endforeach; ?>

    fbq('track', 'InitiateCheckout', {
        value: parseFloat($("#ttsummnt").text().replace(/,/g, '')) || 0,
        currency: 'INR',
        num_items: parseInt($("#itcnt").text()) || 0,
        content_ids: contentIds,
        content_type: 'product'
    });
    $("#placeOrderForm").submit();
});


</script>





  <!-----workspace end----->

  <?= $this->endSection() ?>
  