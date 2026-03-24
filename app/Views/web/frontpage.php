<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>

<!-- Swiper -->
  <div class="swiper-container swiper-banner" data-sliderloaded="0" >
    <div class="swiper-wrapper" style="margin-top: 21px;" >

      
      <?php foreach ($this->data['banner'] as $banner): ?>
      <a href="<?php echo $banner->title; ?>" class="swiper-slide">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url('uploads/banner/'.$banner->desk_banner); ?>" class="sliderloader dsk" alt="<?php echo $banner->banner_title; ?>">
          <img src="images/blue-loading.gif" data-src="<?php echo base_url('uploads/banner/'.$banner->mobile_banner); ?>" class="sliderloadermob res" alt="" style="padding-right:15px; padding-left:15px; border-radius: 15%; margin-top:10px;">
         <!-- <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url('uploads/banner/'.$banner->desk_banner); ?>" class="sliderloadermob res" alt="<?php echo $banner->banner_title; ?>"> -->
      </a>
      <?php endforeach; ?>

      
     

           
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination banner-pagination"></div>

   

  </div>



  <style>
    .mobileadd{display:none;}
    @media(max-width:768px)
    {
        .desktopadd{display:none;}
        .mobileadd{display:block;}
    }
</style>

                                   
                           
<!----again start---->

<div class="container cmpad offers">
  	<div class="row">
  		<div class="col-md-12">
  			<div class="row">
    	  	     <div class="col-sm-12">
    	 	       <h2 class="subhd">Top Selling <span>Products</span>  </h2>
    		     </div>
				 <div class="col-md-12 col-xs-12">
                      <div class="arrow">
                      <!--  <a href="brands" class="btn-view">View All</a> -->
                        <!-- <a href="" class="offer-button-prev"><span class="fa fa-angle-left"></span></a>
                        <a href="" class="offer-button-next"><span class="fa fa-angle-right"></span></a> -->
                      </div>
                    </div>
	        </div>
	        <div class="fullline"><span></span></div>
    	    <div>
        	    <div class="col-md-12" style="padding:0">
        	        <div id="recpro">
<div class="swiper-container swiper-recpro">
    <div class="swiper-wrapper">


        <?php foreach ($products as $p): 
       
           
           $price =  $p->tax > 0 ? $p->product_price + round($p->product_price * ($p->tax / 100), 2) : $p->product_price;
           $offer_price =$p->product_price -  ($p->offers > 0 ? round($p->product_price * ($p->offers / 100), 2) : 0);
         
           $offer_price = $offer_price > 0 ? $offer_price + ($p->tax > 0 ? round($offer_price * ($p->tax / 100), 2) : 0) : $offer_price;
            $slug = slugify($p->product_name);
          
            ?>
        <div class="swiper-slide procon">
            <div class="pro-box">
                <a href="<?= base_url('product-details/' . $p->pro_id.'/'.$slug) ?>">
                    <img src="uploads/products/<?= $p->img ?>"
                        data-src="uploads/products/<?= $p->img ?>"
                        class="catloader pro-img"
                        alt="<?= $p->product_name ?>">

                    <?php if ($p->offers > 0): ?>
                        <p class="off"><?= floatval($p->offers) ?>% Off</p>
                    <?php endif; ?>

                    <p class="brand"><?= $p->product_type ?></p>

                    <p class="name"><?= $p->product_name ?></p>

                    <div class="price-con">
                        <p class="price"><span class="fa fa-inr"></span> <?= $offer_price > 0 ? $offer_price : $price ?></p>
                        <?php if ($offer_price > 0): ?>
                            <p class="price-x"><span class="fa fa-inr"></span> <?= $price ?></p>
                        <?php endif; ?>
                    </div>
                </a>

                <div class="cont">
                    <div class="crtdiv">
                       
                <?php if ($p->qty > 0): ?>
                        <button id="btn<?= $p->pro_id ?>"
                                onclick="addtocartmain('<?= $p->pro_id ?>', '<?= $p->pv_id ?>')"
                                type="button" class="btn cart">
                            <img src="<?= base_url('asset/images/cart-b.svg') ?>" class="order" style="width:17px;">
                            <span class="name" style="font-size:13px;">Add to cart</span>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn cart soldout">
                            <img src="<?= base_url('asset/images/cart-b.svg') ?>" class="order" style="width:17px;">
                            <span class="name" style="font-size:13px;">Sold Out</span>
                        </button>
                    <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
        <?php endforeach; ?>




											

									
                            			                  	            </div>
            			</div>
            		</div>
            	</div><!-- row -->
            </div>
	    </div>
	</div>

</div>

<!----again end----->



  
  <!-- container-fluid  --><div class="container cmpad top-offer">
    <div class="row">
          <div class="col-md-12">
            <div class="swiper-container swiper-topoffer">
              <div class="swiper-wrapper">
                                    <?php foreach ($midbanner as $banner): ?>
                                    <a href="<?php echo $banner->title ?>" class="swiper-slide offer">
                                        <img src="<?php echo base_url('asset/images/blue-loading.gif') ?>" data-src="<?php echo base_url('uploads/banner/'.$banner->desk_banner); ?>" class="catloader" alt="<?php echo $banner->banner_title; ?>">
                                    </a>
                                    <?php endforeach; ?>
                                    </div><!-- swiper-wrapper -->
            </div><!-- swiper-container swiper- -->
          </div><!-- col-md-12 -->
    </div>
</div>


<!-- ===========RELATED TABBED CONTENT============ -->



<div class="container cmpad offers">
  	<div class="row">
  		<div class="col-md-12">
  			<div class="row">
    	  	     <div class="col-sm-12">
    	 	       <h2 class="subhd">View All <span>Categories</span>  </h2>
    		     </div>
				  <div class="col-md-12 col-xs-12">
                      <div class="arrow">
                      <!--  <a href="brands" class="btn-view">View All</a> -->
                        <!-- <a href="" class="offer-button-prev"><span class="fa fa-angle-left"></span></a>
                        <a href="" class="offer-button-next"><span class="fa fa-angle-right"></span></a> -->
                      </div>
                    </div>
	        </div>
	        <div class="fullline"><span></span></div>
    	    <div>
        	
        	    <div class="col-md-12" style="padding:0">
        	        <div id="recpro">
            		    <div class="swiper-container swiper-recpro">
            			    <div class="swiper-wrapper">
                       
                            			                                  		<?php if (!empty($category)) { ?>
    <?php foreach ($category as $cat) { ?>
        <div class="swiper-slide">
            <a href="<?= base_url('product-filter/'.$cat->id.'/1?flag=1') ?>" class="pro-box">
                <p class="fav"><span class="fa fa-heart-o"></span></p>

                <img style="border-radius: 4px;"
                     src="<?= base_url('web/images/'.$cat->img) ?>"
                     data-src="<?= base_url('web/images/'.$cat->img) ?>"
                     class="catloader loadimg pro-img"
                     alt="<?= $cat->name ?>">

                <p class="name"><?= $cat->name ?></p>
            </a>
        </div>
    <?php } ?>
<?php } ?>



											

											  
											
                            			                  	            </div>
            			</div>
            		</div>
            	</div><!-- row -->
            </div>
	    </div>
	</div>

</div>


<!----must Keep----->

  <div class="container cmpad protabcon" data-catimageloaded="0">

  <div class="col-lg-12 col-md-12 bhoechie-tab-container">
         
<div class="bhoechie-tab">

 
            </div>
        </div>

    </div>
	
	<!-- must keep -->



<div class="container cmpad top-offer">
    <div class="row">
          <div class="col-md-12">
            <div class="swiper-container swiper-topoffer">
              <div class="swiper-wrapper">
                    <?php $i = 0; foreach ($midbanner as $banner): ?>
                    <?php if($i >= 4): ?>
                                    <a href="<?php echo $banner->title; ?>" class="swiper-slide offer">
                                        <img src="<?php echo base_url('asset/images/blue-loading.gif') ?>" data-src="<?php echo base_url('uploads/banner/'.$banner->desk_banner); ?>" class="catloader" alt="<?php echo $banner->banner_title; ?>">
                                    </a>
                    <?php endif; $i++; ?>
                    <?php endforeach; ?>
                                    </div><!-- swiper-wrapper -->
            </div><!-- swiper-container swiper-topoffer -->
          </div><!-- col-md-12 -->
    </div>
</div><!-- container-fluid cmpad brands --><div class="container-fluid cmpad brands">
 
										

  <?= $this->endSection() ?>

  