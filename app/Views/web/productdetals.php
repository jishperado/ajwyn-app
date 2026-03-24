<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>
  
<!-----workspace start----->
<link href="<?php echo base_url(); ?>asset/css/xzoom.css" rel="stylesheet" type="text/css">

  <style>
      .detailcount{display:none;}
      .detailcount .qnty{position: unset;}
      .detailcount .dec, 
      .detailcount .inc{height: 50px;line-height: 50px;width: 30%;text-align:center;}
      .detailcount .num{height: 48px;width:32%;}
      
      .alreadypro .addcart{display:none !important;}
      .alreadypro .detailcount{display:block !important;}
      
      .yousave{background: #3f8654;color: #fff;padding: 1px 6px;border-radius: 4px;font-size: 11px;}
    
      .xzoom-lens{position:fixed !important;left:33% !important;right:10px !important; bottom:10px !important; top:10px !important;width:auto !important;height:auto !important;z-index:100000;background-color:#fff;border:none !important;box-shadow: 0px 0px 10px 0px #0000004f;}
      @media(max-width:992px)
      {
          .xzoom-lens{display:none !important;}
      }

  </style>

 <div class="cmtop"></div>
  <div class="container-fluid cmpad" style="margin-top: 20px;">

    <div class="row">

      <div class="col-md-12">
        <ul class="breadcrumb">
          <li><a href="<?= base_url() ?>"><span class="fa fa-home"></span> Home </a></li>
          <li><a href="">Product </a></li>
          <li><a href=""> Details</a></li>
          <li></li>
        </ul>
      </div><!-- col-md-12 -->

      <div class="col-md-9">
        <div class="row">

          <div class="col-md-5">

            <a href="#" onclick="wishlistadded(<?= $product->id; ?>, 0)" class="wishlist"><span class="fa fa-heart-o"></span></a>



<?php 
// Check if product images exist
$hasImages = !empty($productImages) && isset($productImages[0]);
$firstImage = $hasImages ? $productImages[0] : null;
$dummy = base_url('asset/ajwyn_logo.png');
?>

<!-- lens options start -->
<section id="lens">
    <div class="large-5 column">
        <div class="xzoom-container">

            <!-- MAIN IMAGE -->
            <img class="loadimg xzoom3"
                 src="<?= $hasImages ? base_url('uploads/products/'.$firstImage->product_image) : $dummy ?>"
                 xoriginal="<?= $hasImages ? base_url('uploads/products/'.$firstImage->product_image) : $dummy ?>"
                 alt="">

            <!-- THUMBNAILS -->
            <div class="xzoom-thumbs">

                <?php if($hasImages): ?>

                    <!-- First thumbnail (main image) -->
                    <a class="thumbdt" href="#">
                        <img class="loadimg xzoom-gallery3"
                             width="80"
                             src="<?= base_url('uploads/products/'.$firstImage->product_image_s) ?>"
                             xpreview="<?= base_url('uploads/products/'.$firstImage->product_image) ?>"
                             xoriginal="<?= base_url('uploads/products/'.$firstImage->product_image) ?>"
                             alt="">
                    </a>

                    <!-- Remaining thumbnails -->
                    <?php foreach($productImages as $img): ?>
                        <a class="thumbdt" href="#">
                            <img class="loadimg xzoom-gallery3"
                                 width="80"
                                 src="<?= base_url('uploads/products/'.$img->product_image_s) ?>"
                                 xpreview="<?= base_url('uploads/products/'.$img->product_image) ?>"
                                 xoriginal="<?= base_url('uploads/products/'.$img->product_image) ?>"
                                 alt="">
                        </a>
                    <?php endforeach; ?>

                <?php else: ?>

                    <!-- DUMMY THUMBNAIL (No images available) -->
                    <a class="thumbdt" href="#">
                        <img class="loadimg xzoom-gallery3"
                             width="80"
                             src="<?= $dummy ?>"
                             xpreview="<?= $dummy ?>"
                             xoriginal="<?= $dummy ?>"
                             alt="">
                    </a>

                <?php endif; ?>

            </div>

            <!-- VIDEO (remains same) -->
            <div class="detvideo">
                <div class="videoWrapper">
                    <iframe id="playvideo" width="560" height="315"
                        src=""
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>

        </div>        
    </div>
    <div class="large-7 column"></div>
</section>

<!-- lens options end -->
 



              <div class="md-hide">
                
              </div>

            </div><!-- col-md-5 -->




<!-- Details Start -->

<form id="cartForm" action="<?= base_url('product-details/' . $product->id) ?>" method="post">

<input type="hidden" name="target" id="target" value="cart">
<input type="hidden" name="variant" id="variant">
<input type="hidden" name="quantity" id="form_qty">

<?php

$first = $productVeriant[0] ?? [];


$base0 = (float)$first->price;
$per0 = (float)$first->if_offer_per_price;
$tax0 = isset($product->tax) ? (float)$product->tax : 0;

$offer0 = $per0 > 0 ? $base0 - ($base0 * $per0 / 100) : $base0;

// final price after tax
$finalPrice0 = $tax0 > 0 ? $offer0 + ($offer0 * $tax0 / 100) : $offer0;

// MRP including tax
$mrp0 = $tax0 > 0 ? $base0 + ($base0 * $tax0 / 100) : $base0;

// how much customer saves
$save0 = $mrp0 - $finalPrice0;
?>

<div class="col-md-7">

<div class="titlebox">
    <h1 class="proname"><?= $product->product_name ?></h1>
    <ul class="detli"><li><?= $product->description ?></li></ul>
</div>

<div class="pricebox">
    <span class="title">Made In India
    <span class="clearfix"></span>

    <span class="price salesprice" style="font-family: Arial, Helvetica, sans-serif;">
        <span style="font-family: Arial, Helvetica, sans-serif; font-weight: normal;">₹</span>
        <?= number_format($finalPrice0, 2) ?>
    </span>

    <span class="cutprice">₹<?= number_format($mrp0, 2) ?></span>

    <span class="offers" style="border:none">
        You Save <i class="fa fa-inr"></i> <?= number_format($save0, 2) ?> (<?= $per0 ?>%)
    </span>

    <span class="title">Inclusive of all taxes</span></span>
</div>
        
    <div class="md-show"></div>

 <div class="descbox">
                <p>
                 <?= $product->product_name ?>
                  <a href="#moreinfo" class="more">Product Reviews 
                    <span class="fa fa-angle-down"></span>
                  </a>
                </p>
 </div><!-- descbox -->

<div class="optionbox">
<h4 class="dthd">Veriants :</h4>

<ul class="optbrand" id="variantList">
<?php foreach ($productVeriant as $v): ?>

<?php
$base = (float)$v->price;
$per = (float)$v->if_offer_per_price;
$tax = isset($product->tax) ? (float)$product->tax : 0;
$offer = $per > 0 ? $base - ($base * $per / 100) : $base;

$final = $tax > 0 ? $offer + ($offer * $tax / 100) : $offer;
$mrp = $tax > 0 ? $base + ($base * $tax / 100) : $base;
$save = $mrp - $final;
$stock = (int)$v->qty; 
  // YOUR STOCK
?>

<li class="variant-item" 
    data-id="<?= $v->id ?>"   
    data-price="<?= $mrp ?>"
    data-discount="<?= $per ?>"
    data-final-price="<?= $final ?>"
    data-yousave="<?= $save ?>"
    data-stock="<?= $stock ?>">   <!--  stock added -->

    <a href="#" class="detbrand" >
     <!--   <img src="images/loading.svg" data-src="images/Bajaj.jpg" class="loadimg"> -->
        <span class="price"><?= $v->veriant ?> – ₹<?= number_format($final, 2) ?></span>
        <?php if ($stock <= 0): ?><span style="color:red;font-size:11px;">Out of Stock</span><?php else: ?><span style="color:green;font-size:11px;">In Stock</span><?php endif; ?>
    </a>
</li>

<?php endforeach; ?>
</ul>
</div>


<div class="btncol">

    <!-- ADD & BUY BUTTONS SHOWN WHEN STOCK AVAILABLE -->
   
        <a href="#" class="btn addcart cartBuyBtns">
            <img src="<?= base_url('asset/images/cartd.svg') ?>" class="loadimg" alt="">
            <span style="font-size: 12px;">Add to Cart</span>
        </a>

        <a href="#" class="btn buynow cartBuyBtns">
            <img src="<?= base_url('asset/images/buynow.svg') ?>" class="loadimg">
            Buy for ₹<?= number_format($finalPrice0, 2) ?>
            <span class="off">
                <span class="cut">₹ <?= number_format($mrp0, 2) ?></span> <?= $per0 ?> %Off
            </span>
        </a>
 

    <!-- SOLD OUT BUTTON when no stock -->
    <a href="#" class="btn addcart soldout" >
        SOLD OUT
    </a>
           <a href="<?= base_url('/') ?>" class="btn buynow soldout">
            <img src="<?= base_url('asset/images/buynow.svg') ?>" class="loadimg">
           Continue To Shopping
        </a>

    <!-- quantity box -->
         <!--puls btn-->
              <div class="cont detailcount">
                 <div autocomplete="off" class="crtdiv">
                    <span class="qnty">
                        <span class="dec" >
                        <i class="fa fa-minus" aria-hidden="true"></i>
                        </span>
                        
                        <input type="number" name="quantity" id="num7719" data-proprice="129" data-itemqntity="0" class="num" value="0" >

                        <span class="inc">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        </span>
                    </span>
                 </div>
	          </div>
              <!--puls btn-->

</div><!-- btncol -->

            <div class="highlights" >
              <h3 class="dthd">Highlights</h3>
              <ul>
                  <?php foreach ($highlight as $item): ?>
                  <li style="text-align: left;" class="col-sm-6"><span style="font-weight: bold;font-size: 14px;"><?= $item->question ?></span> : <?= $item->answer ?></li>
                  <?php endforeach; ?>
                  
                          </ul>
            </div><!-- highlights-->

</div><!-- col-md-7 -->
</form>
<!-- Details End -->


           


          <div class="col-md-12 md-show">
            <div class="deliverybox">
          
                    
          <!--<span class="title"><i class="fa fa-map-marker"></i>Delivery by 18th November</span>-->
          <form>
            <input type="number" name="pincode" value="670002" placeholder="Enter Pincode">
            <a href="#" class="change">Change</a>
          </form>

          <ul class="row delivery">
           
           <li class="col-xs-4">
              <img src="<?= base_url('asset/images/truck.svg') ?>" data-src="<?= base_url('asset/images/truck.svg') ?>" class="loadimg delimg" alt="">
              <span class="name">Priority</span>
              <span class="status">Delivery</span>
            </li>
            <li class="col-xs-4">
              <img src="<?= base_url('asset/images/cancel.svg') ?>" data-src="<?= base_url('asset/images/cancel.svg') ?>" class="loadimg delimg" alt="">
              <span class="name">Cancellation</span>
              <span class="status">Allowed</span>
            </li>

            <li class="col-xs-4">
              <img src="<?= base_url('asset/images/refunf.svg') ?>" data-src="<?= base_url('asset/images/refunf.svg') ?>" class="loadimg delimg" alt="">
              <span class="name">Refund</span>
              <span class="status">Policy & Terms</span>
            </li>
          </ul>

          <div class="details">
            <!--<p><span class="fa fa-check"></span> 1 Year Warranty</p>-->
                        <p style="display: none;"><span class="fa fa-money"></span> Cash on Delivery Available</p>
                        <p><span class="fa fa-money"></span> Online Payment Available</p>
          </div>

        </div><!-- deliverybox -->          </div>

           <div id="" class="col-md-12"></div>

          <div  class="col-md-6">

            
<div class="row">
            
    <div class="container-fluid" style="margin-top: 10px;">

    

    <div class="row" >
        

        <div class="col-md-9">

 <h3><strong>Question & answer</strong></h3>

        </div>
    </div>

    <hr>

    <!-- Review box -->
   
<?php if (!empty($qa)): ?>
    <?php foreach ($qa as $item): ?>
        
        <div class="review-box" style="margin-bottom:30px;">
           
            <h5><strong><?= $item->question ?></strong></h5>

            <div>
                <small style="color: #518e5c;">
                    <i class="fa fa-clock-o"></i> 
                    &nbsp;Created on <?= date("M d, Y", strtotime($item->created_at)) ?>
                </small>
            </div>

            <p style="margin-top:10px;"><?= $item->answer ?></p>
        </div>

    <?php endforeach; ?>
<?php else: ?>

    <p>No questions yet.</p>

<?php endif; ?>

    <div style="font-size: 13px; color: #0c7fea; font-weight: bold;" ><a href="" style="text-decoration: none;"> See more questions</a></div>

</div>
</div>



          </div>
          
          <!-- col-md-6 -->
           <?php 
           // assume $rating contains all rows fetched from DB

$total_reviews = count($rating);
$ratingCount = [1=>0,2=>0,3=>0,4=>0,5=>0];
$sum = 0;

foreach ($rating as $r) {
    $star = (int) $r->rating;
    $ratingCount[$star]++;
    $sum += $star;
}

$avg_rating = $total_reviews ? round($sum / $total_reviews, 1) : 0;
$barPercent = [];

foreach ($ratingCount as $star => $count) {
    $barPercent[$star] = $total_reviews ? round(($count / $total_reviews) * 100) : 0;
}

           
           ?>

          <div id="moreinfo" class="col-md-6">
<div class="row">
            
    <div class="container-fluid" style="margin-top: 10px;">

    <h3><strong>Product Reviews</strong></h3>

    <div class="row" style="margin-top: 20px;">
    <div class="col-md-3 text-center">
        <h1 style="margin:0; font-size:48px; font-weight:700;">
            <?= $avg_rating ?><span style="color:#f1c606; font-size: 15px;">★</span>
        </h1>
        <small>Rating & <?= $total_reviews ?> reviews</small>
        <span><img src="<?= base_url('asset/images/star.webp') ?>" style="margin-top: 20px;"></span>
    </div>

    <div class="col-md-9">
        <?php
            $colors = [
                5 => "#30b34e",
                4 => "#98cc3f",
                3 => "#ffdc46",
                2 => "#f7941d",
                1 => "#fd3e2f",
            ];

            for ($i = 5; $i >= 1; $i--):
        ?>
        <div class="row" style="margin-bottom:3px;">
            <div class="col-xs-2 text-right"><?= $i ?> <span style="color:#f1c606;">★</span></div>
            <div class="col-xs-8">
                <div class="progress" style="height:8px; margin-top:8px;">
                    <div class="progress-bar"
                        style="width: <?= $barPercent[$i] ?>%; background-color: <?= $colors[$i] ?>;">
                    </div>
                </div>
            </div>
            <div class="col-xs-2"><?= $ratingCount[$i] ?></div>
        </div>
        <?php endfor; ?>
    </div>
</div>


    <hr>

    <!-- Review box -->
   <?php foreach ($rating as $r): ?>

    <div class="review-box" style="margin-bottom:30px;">
        <span class="label label-success" style="font-size:14px;"><?= $r->rating ?> ★</span>
        <strong style="margin-left:6px;"><?= $r->name ?></strong>

        <div>
            <?php
                $date = date("M d, Y", strtotime($r->created_at));
            ?>
            <small>Reviewed on <?= $date ?></small>
        </div>

        <p style="margin-top:10px;"><?= $r->review ?></p>

        <button class="btn btn-default btn-xs">Helpful</button>
    </div>

<?php endforeach; ?>

   

    <div style="font-size: 13px; color: #0c7fea; font-weight: bold;" ><a href="" style="text-decoration: none;"> See more reviews</a></div>

</div>
</div>

          </div><!-- col-md-6 -->

        </div><!-- row -->
      </div><!-- col-md-9 -->

      <div class="col-md-3">
        <div class="row">

          <div class="col-md-12">

            <div class="md-hide">
              <div class="deliverybox">
          
                    
          <!--<span class="title"><i class="fa fa-map-marker"></i>Delivery by 18th November</span>-->
          <form>
            <input type="number" name="pincode" value="670002" placeholder="Enter Pincode">
            <a href="#" class="change">Change</a>
          </form>

          <ul class="row delivery">
           
            <li class="col-xs-4">
              <img src="<?php echo base_url('asset/images/truck.svg') ?>" data-src="<?php echo base_url('asset/images/truck.svg') ?>" class="loadimg delimg" alt="">
              <span class="name">Priority</span>
              <span class="status">Delivery</span>
            </li>
            <li class="col-xs-4">
              <img src="<?php echo base_url('asset/images/cancel.svg') ?>" data-src="<?php echo base_url('asset/images/cancel.svg') ?>" class="loadimg delimg" alt="">
              <span class="name">Cancellation</span>
              <span class="status">Allowed</span>
            </li>

            <li class="col-xs-4">
              <img src="<?php echo base_url('asset/images/refunf.svg') ?>" data-src="<?php echo base_url('asset/images/refunf.svg') ?>" class="loadimg delimg" alt="">
              <span class="name">Refund</span>
              <span class="status">Policy & Terms</span>
            </li>
          </ul>

          <div class="details">
            <!--<p><span class="fa fa-check"></span> 1 Year Warranty</p>-->
                        <p style="display: none;"><span class="fa fa-money"></span> Cash on Delivery Available</p>
                        <p><span class="fa fa-money"></span> Online Payment Available</p>
          </div>

        </div><!-- deliverybox -->            </div>

           <style>


.product-card {
    background: #fcfafa;
    border-radius: 5px;
    padding-top: 15px;
    padding-bottom: 15px;
    margin-bottom: 10px;
    border: 1px solid #eee;
    box-shadow: 0 3px 15px rgba(79, 79, 79, 0.1);
}

.product-card img {
    width: 90px;
    height: auto;
    object-fit: cover;
    border-radius: 10px;
}

.product-title {
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #444343;
   
}

.product-info {
    font-size: 13px;
    color: #555;
    line-height: 1.4;
}

.price-section {
    font-size: 18px;
    font-weight: 600;
    color: #000;
    text-align: right;
}

.qty-box {
    margin-top: 10px;
}




</style> 

            <div class="col-md-12 nopad">

              <h3 class="dthd">Similar Products</h3>
              
                                          
                            
              <div class="container-fluid">

    <!-- PRODUCT ITEM -->
<?php if (!empty($simproduct)) : ?>
    <?php $i = 0; foreach ($simproduct as $sp) : ?>
        <?php if ($i >= 6) break; ?>
        <a href="<?= base_url('product-details/' . $sp->product_id) ?>">
            <div class="product-card row">

                <div class="col-xs-3" style="border-right: solid 1px; border-color: #eeeded;">
                    <img src="<?= base_url('uploads/products/' . $sp->img) ?>" alt="<?= $sp->product_name ?>" style="width:100%; height:auto;">
                </div>

                <div class="col-xs-9">
                    <div class="product-title">
                        <?= substr($sp->product_name, 0, 40) ?><?= strlen($sp->product_name) > 40 ? '...' : '' ?>
                    </div>

                    <div class="product-info" style="font-weight: bold; color: #a4a5a4; font-size: 14px;">
                        ₹ <?= $sp->price ?? '0.00' ?> <br>
                    </div>
                </div>

            </div>
        </a>

        <?php $i++; ?>
    <?php endforeach; ?>
<?php endif; ?>


  
  </div></div>
                            
                            
              
                            
                            
              
                            
                            
             
                            
                            
              
                            
            </div><!-- similar-product -->

          

          </div><!-- col-md-12 -->

        </div><!-- row -->
      </div>


    </div><!-- row -->
  </div>

<?php $isLoggedIn = isset($_SESSION['user_id']); ?>

<!-- Meta Pixel: ViewContent -->
<script>
fbq('track', 'ViewContent', {
    content_name: '<?= addslashes($product->product_name) ?>',
    content_ids: ['<?= $product->id ?>'],
    content_type: 'product',
    value: <?= $finalPrice0 ?>,
    currency: 'INR'
});
</script>

<!--Details Script-->
<script>
$(document).ready(function () {

    // =========================
    // 1) VARIANT CLICK HANDLER
    // =========================
    $(document).on("click", ".variant-item", function (e) {
        e.preventDefault();

        let variantID   = $(this).data("id");
        let mrp         = parseFloat($(this).data("price"));
        let percent     = parseFloat($(this).data("discount"));
        let finalPrice  = mrp - (mrp * percent / 100);
        let stock       = parseInt($(this).data("stock"));

        // set hidden input
        $("#variant").val(variantID);

        // Update main price section
        $(".salesprice").html('₹' + finalPrice.toFixed(2));
        $(".cutprice").html('₹' + mrp.toFixed(2));
        $(".offers").html('You Save ₹ ' + (mrp - finalPrice).toFixed(2) + ' (' + percent + '%)');

        // Update ONLY the main Buy Now button (not the Continue Shopping one)
        $(".buynow.cartBuyBtns .off").html(
            '<span class="cut">₹' + mrp.toFixed(2) + '</span> ' + percent + '% Off'
        );
        $(".buynow.cartBuyBtns").contents().filter(function () {
            return this.nodeType === 3;
        }).remove();
        $(".buynow.cartBuyBtns").prepend("Buy for ₹ " + finalPrice.toFixed(2) + " ");

        // Stock-wise UI handling
        if (stock > 0) {
            $(".cartBuyBtns").show();
            $(".soldout").hide();
            $(".num").val(1);
        } else {
            $(".cartBuyBtns").hide();
            $(".soldout").show();
            $(".num").val(0);
        }

        $(".variant-item").removeClass("active");
        $(this).addClass("active");
    });

    // =========================
    // 2) AUTO SELECT FIRST VARIANT
    // =========================
    let firstVariant = $(".variant-item").first();
    if (firstVariant.length > 0) {
        // trigger the same handler as manual click
        firstVariant.trigger("click");
    }

    // =========================
    // 3) QUANTITY BUTTONS
    // =========================
    $(".inc").click(function () {
        let stock = parseInt($(".variant-item.active").data("stock"));
        let input = $(".num");
        let qty   = parseInt(input.val()) || 0;

        if (qty < stock) input.val(qty + 1);
    });

    $(".dec").click(function () {
        let input = $(".num");
        let qty   = parseInt(input.val()) || 0;
        if (qty > 1) input.val(qty - 1);
    });

    // =========================
    // 4) ADD TO CART
    // =========================
    // bind ONLY on real add-to-cart button (not the SOLD OUT one)
    $(".addcart.cartBuyBtns").click(function (e) {
        e.preventDefault();

        let variant_id = $("#variant").val();
        let qty        = $(".num").val();
        let product_id = "<?= $product->id ?>";

        // safety check: no variant selected
        if (!variant_id) {
            alert("Please select a brand / variant.");
            return;
        }

        <?php if (!$isLoggedIn): ?>  
            let guestCart = JSON.parse(localStorage.getItem("guest_cart")) || [];

            let exists = guestCart.find(
                item => item.product_id == product_id && item.variant == variant_id
            );

            if (!exists) {
                guestCart.push({
                    product_id: product_id,
                    variant: variant_id,
                    quantity: qty
                });
                localStorage.setItem("guest_cart", JSON.stringify(guestCart));
            }

            // Meta Pixel: AddToCart (guest)
            fbq('track', 'AddToCart', {
                content_name: '<?= addslashes($product->product_name) ?>',
                content_ids: [product_id],
                content_type: 'product',
                value: <?= $finalPrice0 ?>,
                currency: 'INR'
            });

            // Disable button to prevent double click
            $(".addcart.cartBuyBtns span").text("Added to Cart");
            $(".addcart.cartBuyBtns").css("pointer-events", "none").css("opacity", "0.7");

            // Redirect to cart
            window.location.href = "<?= base_url('cart') ?>";
            return; // stop form submit
        <?php endif; ?>

        // 👇 LOGGED-IN USER → normal submit
        fbq('track', 'AddToCart', {
            content_name: '<?= addslashes($product->product_name) ?>',
            content_ids: [product_id],
            content_type: 'product',
            value: <?= $finalPrice0 ?>,
            currency: 'INR'
        });
        $("#target").val("cart");
        $("#form_qty").val(qty);
        $("#cartForm").submit();
    });

    // =========================
    // 5) BUY NOW
    // =========================
    $(".buynow.cartBuyBtns").click(function (e) {
        e.preventDefault();
        $("#target").val("buy");
        $("#form_qty").val($(".num").val());
        $("#cartForm").submit();
    });

});
</script>
<!-- Details SCRIPT -->

<!-- WISHLIST SCRIPT -->

<script>
function wishlistadded(productid, flag) {
    const favBtn = $('#fav' + productid);
    const customer_id = "<?= session()->get('user_id') ?>";

    if (!customer_id) {
        // If user not logged in → show login modal
      
    $('#accMod').modal('show');
    $("#lwthcrd").trigger("click");
    return;
    }

    // Toggle the active class
    favBtn.toggleClass('active');
    const active = favBtn.hasClass('active') ? 1 : 0;

    // Send AJAX to update wishlist
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: "<?= base_url('wishlist/toggle') ?>",
        data: {
            productid: productid,
            active: active,
            customer_id: customer_id
        },
        success: function (data) {
            console.log('Wishlist updated:', data);
            if (active == 1) {
                fbq('track', 'AddToWishlist', {
                    content_name: '<?= addslashes($product->product_name) ?>',
                    content_ids: [productid.toString()],
                    content_type: 'product',
                    value: <?= $finalPrice0 ?>,
                    currency: 'INR'
                });
            }
            // No popup, just toggle icon
        },
        error: function (xhr, status, error) {
            console.error('Wishlist AJAX Error:', error);
        }
    });
}
</script>


  <?= $this->endSection() ?>



  <!-----workspace end----->