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
    <h2 class="subhd hdng" >Select Shipping Address   

    </h2>


  <?php 
  
  $state=0;
  foreach ($address as $add): 
   if($add->primary_address == 1){
    $state=$add->state;
   }
  ?>
<?php $is_primary = ($add->primary_address == 1) ? 'checked' : ''; ?>

<div class="container-fluid cartitem cartbox<?= $add->id ?>" 
     onclick="selectAddress(<?= $add->id ?>)"
     id="box_<?= $add->id ?>">

    <div class="col-md-9 col-xs-8 details nopadcartr">
        <span class="brand"><?= $add->name ?></span>
        <p class="name"><?= $add->address ?></p>

        <div class="price-con">
            <p class="price">City: <?= $add->city_name ?></p>
            <p class="price">Pincode: <?= $add->pincode ?></p>
            <p class="price">Landmark: <?= $add->landmark ?></p>
        </div>
    </div>

    <div class="col-md-3 col-xs-12 count mobcartaction nopadcartr">
        <label style="font-weight:600;">
            <input type="radio" name="selected_address"
                   id="addr_<?= $add->id ?>"
                   value="<?= $add->id ?>"
                   <?= $is_primary ?>>
            Select
        </label>
        <div class="clearfix"></div><br>
    </div>
</div>
<?php endforeach; ?>


    <div class="container-fluid btncont">
        <?php if (empty($address)) { ?>
            <script>
                window.location.href = "<?= base_url('new_address?place=1') ?>";
            </script>
        <?php } else { ?>
            <a href="<?= base_url('/') ?>" class="btn btn-continue" style="font-size: 13px;">
                <img style="width: 18px;" src="<?= base_url('asset/images/cart-b.svg') ?>"> Continue Shopping
            </a>

            <a href="javascript:void(0);" id="logtocntinue" class="btn btn-order moqcheck">
                <img style="width: 18px;" src="<?= base_url('asset/images/buynow.svg') ?>"> 
                Place Order
            </a>
        <?php } ?>

        <form id="placeOrderForm" action="" method="POST" style="display:none;">
            <input type="hidden" name="selected_address_id" id="selected_address_id">
        </form>

    </div>

</div>



<?php  
$item_count = 0;
$subtotal = 0;
$total_discount = 0;
$tax_amount = 0;
$grand_total = 0;
$grand_total_with_shipping = 0;
$shipping_cost =0;

if( array_sum(array_column($cartdata, 'quantity'))  == 1 && $state >0 )
{
    $shipping_cost = $state == 19 ? $cartdata[0]->shipping : $cartdata[0]->shipping_outside;
}

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
$grand_total_with_shipping = $grand_total + $shipping_cost;
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

        <!-- Shipping -->
        <tr>
            <td>Shipping Cost</td>
            <td>₹ <span id="shipCost"><?= number_format($shipping_cost, 2) ?></span></td>
        </tr>

        <!-- Discount -->
        <tr>
            <td style="color: green;">Discount</td>
            <td style="color: green;">₹ 
                <span id="discount"><?= number_format($subtotal - $grand_total, 2) ?></span>
            </td>
        </tr>

        <!-- 
        <tr>
            <td>Estimated Tax</td>
            <td>₹ <span id="taxAmt"><?= number_format($tax_amount, 2) ?></span></td>
        </tr>-->

        <!-- Grand Total -->
        <tr class="total">
            <td>Amount Payable</td>
            <td>₹ <span id="ttsummnt"><?= number_format($grand_total_with_shipping, 2) ?></span></td>
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
    // auto select primary on page load
window.addEventListener("load", function () {
    let primary = document.querySelector("input[name='selected_address']:checked");
    if (primary) {
        $("#selected_address_id").val(primary.value);
        highlightSelected(primary.value);
    }
});

$(document).on('click', '.delete-item', function () {
    let url = $(this).data("url");
    window.location.href = url;
});

//$("#logtocntinue").on("click", function () {
  //  $("#placeOrderForm").submit();
//});

//function selectAddress(id) {
  // window.location.href = "<?= base_url('placeorder') ?>?address=" + id;
//}

function highlightSelected(id) {
    $(".cartitem").removeClass("active-address");
    $(".cartbox" + id).addClass("active-address");
}


// When clicking "Place Order"
/*document.getElementById("logtocntinue").addEventListener("click", function () {
    var selected = document.querySelector("input[name='selected_address']:checked");
    if (!selected) {
        
        return false;
    }
    document.getElementById("placeOrderForm").submit();
});*/


</script>
<script>
$("#logtocntinue").on("click", function () {
    let selected = $("input[name='selected_address']:checked").val();

    if (!selected) {
        alert("Please select a shipping address");
        return;
    }

    // InitiateCheckout tracked on cart page — not duplicated here
    $("#selected_address_id").val(selected);
    $("#placeOrderForm").submit();
});
</script>


<script>
function selectAddress(id) {
    // check radio button
    $("#addr_" + id).prop("checked", true);

    // set hidden input
    $("#selected_address_id").val(id);

    // highlight selected box
    highlightSelected(id);
}
</script>




  <!-----workspace end----->

  <?= $this->endSection() ?>
  