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

        <div id="guestCartContainer"></div>

        <div class="container-fluid btncont">
            <a href="<?= base_url('/') ?>" class="btn btn-continue" style="font-size: 13px;">
                <img style="width: 18px;" src="<?= base_url('asset/images/cart-b.svg') ?>">
                Continue Shopping
            </a>

		      <a href="javascript:void(0);" id="logtocntinue" class="btn btn-order moqcheck">
    <img style="width: 18px;" src="<?= base_url('asset/images/buynow.svg') ?>"> 
    Place Order
</a>
        </div>
    </div>


<form id="placeOrderForm" action="" method="POST" style="display:none;">
    <!-- Add hidden fields if needed -->
<input type="hidden" name="one"  value="one">
</form>
    <!-- RIGHT SUMMARY -->
    <div class="col-md-4 boxcol">
        <h2 class="subhd">Order Summary</h2>

        <div class="table-responsive cartpage">
            <table class="table table-cartsummary">
                <tbody>
                <tr>
                    <td><strong>Subtotal (<span id="itcnt">0</span> Item)</strong></td>
                    <td><strong>₹ <span id="crtamnt">0.00</span></strong></td>
                </tr>

                <tr>
                    <td style="color: green;">Discount</td>
                    <td style="color: green;">₹ <span id="discount">0.00</span></td>
                </tr>

                <tr class="total">
                    <td>Amount Payable</td>
                    <td>₹ <span id="ttsummnt">0.00</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div><!-- MAIN WRAPPER ENDS -->


<script>
function loadGuestCart() {
    let cart = JSON.parse(localStorage.getItem("guest_cart")) || [];

    if (cart.length === 0) {
        window.location.href = "<?= base_url('/') ?>";
        return;
    }

    $("#guestCartContainer").html("");

    let subtotal = 0, item_count = 0, grand_total = 0;

    let pending = cart.length;

    cart.forEach(item => {
        $.post("<?= base_url('product/get_variant_details') ?>", {variant: item.variant}, function(prod) {

            let base = parseFloat(prod.price);
            let offer = parseFloat(prod.if_offer_per_price);
            let tax = parseFloat(prod.tax);

            let offer_price = offer > 0 ? base - (base * offer / 100) : base;
            let final_price = tax > 0 ? (offer_price + (offer_price * tax / 100)) : offer_price;

            let total_price = final_price * item.quantity;
            let total_mrp = (tax > 0 ? base + (base * tax / 100) : base) * item.quantity;

            subtotal += total_mrp;
            grand_total += total_price;
            item_count += parseInt(item.quantity);

            let html = `
            <div class="container-fluid cartitem " style="margin-bottom: 10px;">

                <div class="col-md-2 col-xs-4 item nopadcart">
                    <img src="${prod.img}" class="loadimg">
                </div>

                <div class="col-md-7 col-xs-8 details nopadcartr">
                    <span class="brand">${prod.type_name}</span>
                    <a href="#" class="name">${prod.product_name} (${prod.veriant})</a>

                    <div class="price-con">
                        <p class="price"><span class="fa fa-inr"></span> ${final_price.toFixed(2)}</p>
                        ${
                            offer > 0 ?
                            `<p class="price-x"><span class="fa fa-inr"></span> ${ (tax > 0 ? (base + (base * tax / 100)) : base).toFixed(2) }</p>` :
                            ''
                        }
                    </div>
                </div>

                <div class="col-md-3 col-xs-12 count mobcartaction nopadcartr">
                      <div class="cart-count">
                    <span class="btn"><span class="fa fa-minus-square-o" onclick="updateQty('${item.variant}', -1)"></span></span>
                    <input class="num cartnum" type="number" value="${item.quantity}" readonly>
                    <span class="btn"><span class="fa fa-plus-square-o" onclick="updateQty('${item.variant}', 1)"></span></span>
   </div>
                    <span class="remove text-center" onclick="removeItem('${item.variant}')">
                        Remove <i class="fa fa-trash-o"></i>
                    </span>
                     <div class="clearfix"></div>
        <br>
                 
                </div>

            </div>
            `;

            $("#guestCartContainer").append(html);

            pending--;
            if (pending === 0) updateSummary(subtotal, grand_total, item_count);
        }, "json");
    });
}

function updateSummary(subtotal, grand_total, item_count) {
    $("#itcnt").text(item_count);
    $("#crtamnt").text(subtotal.toFixed(2));
    $("#discount").text((subtotal - grand_total).toFixed(2));
    $("#ttsummnt").text(grand_total.toFixed(2));
}

function updateQty(variant, change) {
    let cart = JSON.parse(localStorage.getItem("guest_cart")) || [];
    cart = cart.map(item => {
        if (item.variant == variant) {
            item.quantity = Math.max(1, item.quantity + change);
        }
        return item;
    });
    localStorage.setItem("guest_cart", JSON.stringify(cart));
    loadGuestCart();
}

function removeItem(variant) {
    let cart = JSON.parse(localStorage.getItem("guest_cart")) || [];
    cart = cart.filter(item => item.variant != variant);
    localStorage.setItem("guest_cart", JSON.stringify(cart));
    loadGuestCart();
}

$("#guestPlaceOrder").click(function(){
    window.location.href = "<?= base_url('login') ?>";
});

loadGuestCart();
</script>


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
    $("#placeOrderForm").submit();
});


</script>





  <!-----workspace end----->

  <?= $this->endSection() ?>
  