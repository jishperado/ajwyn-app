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


    <div class="col-md-9 boxcol">
      <div class="">
      <h2 class="subhd hdng">My Wishlist</h2>   



			    <div class="container-fluid cartitem cartbox14972">
			     
<div class="row">
<?php
$shown = []; // avoid showing same product_id twice
?>

<?php foreach ($wishlistProducts as $row): ?>

    <?php
    // Skip if this product already shown (use product_id, not id)
    if (in_array($row->product_id, $shown)) {
        continue;
    }
    $shown[] = $row->product_id;

    // Calculate offer price
    $price      = (float) $row->price;
    $offerPer   = (float) $row->if_offer_per_price;
    $offerPrice = $offerPer > 0 ? $price - ($price * $offerPer / 100) : $price;
    ?>

    <div class="col-md-3 col-sm-4 col-xs-6 product-box"
         data-price="<?= $offerPrice ?>"
         data-discount="<?= $offerPer ?>">

        <div class="pro-box">

            <a href="<?= base_url('product-details/'.$row->id) ?>" target="_blank">

                <p class="fav">
                    <span class="fa fa-heart" style="color:#0a71ef;"></span>
                </p>

                <img src="<?= base_url('uploads/products/'.$row->img) ?>"
                     class="loadimg pro-img" alt="">
                           <p class="off">15% Off</p>  <p class="brand">Philips</p>

                <?php if ($offerPer > 0): ?>
                    <p class="off"><?= $offerPer ?>% Off</p>
                <?php endif; ?>

                <p class="brand"><?= ucfirst($row->product_type) ?></p>

                <p class="name"><?= $row->product_name ?> (<?= $row->veriant ?>)</p>

                <div class="price-con">
                    <p class="price">
                        <span class="fa fa-inr"></span>
                        <?= number_format($offerPrice, 2) ?>
                    </p>

                    <?php if ($offerPer > 0): ?>
                        <p class="price-x">
                            <span class="fa fa-inr"></span>
                            <?= number_format($price, 2) ?>
                        </p>
                    <?php endif; ?>
                </div>

            </a>

            <div class="cont">
                <div autocomplete="off" class="crtdiv">

                    <span class="qnty">

                        <span class="dec" onclick="reducecart('<?= $row->id ?>')">
                            <i class="fa fa-minus"></i>
                        </span>

                        <input type="number"
                               id="num<?= $row->id ?>"
                               data-proprice="<?= $offerPrice ?>"
                               data-itemqntity="<?= $row->qty ?>"
                               class="num"
                               value="0"
                               min="0"
                               onkeyup="updatecart('<?= $row->id ?>')">

                        <span class="inc" onclick="addtocart('<?= $row->id ?>')">
                            <i class="fa fa-plus"></i>
                        </span>

                    </span>

                    <button id="btn<?= $row->id ?>"
                            onclick="addtocart('<?= $row->id ?>')"
                            type="button"
                            class="btn cart">
                        <img src="<?= base_url('asset/images/cart-b.svg') ?>" class="order" style="width:17px;">
                        <span class="name" style="font-size:13px;">Add to cart</span>
                    </button>

                </div>
            </div>

        </div>
    </div>

<?php endforeach; ?>




            
</div>
             
			    </div><!-- cartitem container fluid -->



          
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
  