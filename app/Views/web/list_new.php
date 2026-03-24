<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>



<!-----workspace start----->


 <div class="cmtop"></div>
  <div class="container cmpad" style="margin-top: 20px;">
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb">
          <li><a href=""><span class="fa fa-home"></span> Home </a></li>
          <li>Search Result for Electrical</li>
        </ul>
      </div><!-- col-md-12 -->

      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3 md-hide">
            
            <h2 class="filterhd md-show">Filter Search</h2>

    
   



            <div class="filter" id="categoryFilter">
          <p class="head">Category</p>
          <ul>
                                      <li>
                <a href="">
                  Electricals
                </a>
              </li>
                      </ul>
        </div><!-- filter -->
        
    
          <div class="filter" id="subcategoryFilter">
      <p class="head">Sub Category test</p>
      <ul>
       
        
                          <li>
            <a href="">
              Lamps & Lighting
            </a>
          </li>
                          <li>
            <a href="">
              Switchgear
            </a>
          </li>
                          <li>
            <a href="">
              Distribuition Boards
            </a>
          </li>
                         
                
      
      </ul>
    </div><!-- filter -->
          
     
          
         
    

    
    

    <div class="filter" style="display: none;">
      <button class="filtrbtn">Discount</button>
      <div class="filtrcontent" id="filterByDiscountDiv">
        
                                <label class="check-label">
          <input type="checkbox" class="brandsort" data-discount-name="less_than_10">
          <span class="c-box"></span>
          <span class="check-div">Less than 10%</span>
        </label>
                      
        
      </div><!-- filtrcontent -->
    </div><!-- filter -->
                                             
                
           
            
          </div><!-- col-md-3 -->

          <div class="filter-block md-show" id="filter_block" style=" width: 60%;">
            <button id="hide" class="filterclose">
              <span class="fa fa-times"></span>
            </button>

            <h2 class="filterhd md-show">Filter Search</h2>

    
   



            <div class="filter" id="categoryFilter">
          <p class="head">Category</p>
          <ul>
                                      <li>
                <a href="">
                  Electricals
                </a>
              </li>
                      </ul>
        </div><!-- filter -->
        
    
          <div class="filter" id="subcategoryFilter">
      <p class="head">Sub Category</p>
      <ul>
       
        
                         
                          <li>
            <a href="">
              Door Bell
            </a>
          </li>
                          <li>
            <a href="">
              Conduits & Fittings
            </a>
          </li>
                          <li>
            <a href="">
              Accessories
            </a>
          </li>
                          <li>
            <a href="">
              Meter Box
            </a>
          </li>
                
      
      </ul>
    </div><!-- filter -->
          
     
          
         
    

    
    

    <div class="filter" style="display: none;">
      <button class="filtrbtn">Discount</button>
      <div class="filtrcontent" id="filterByDiscountDiv">
        
                                <label class="check-label">
          <input type="checkbox" class="brandsort" data-discount-name="less_than_10">
          <span class="c-box"></span>
          <span class="check-div">Less than 10%</span>
        </label>
                      
        
      </div><!-- filtrcontent -->
    </div><!-- filter -->
                                             
                
          </div><!-- filter-block -->

          <div class="col-md-9 searchres" id="searchBody">
                        
            <div class="row" id="product-search">
               
                                                             
                  <div class="result-status col-sm-7"> Showing 7438 results for  Electrical  </div>
                            
              
              
              <!--store data to database-->
                            <!--store data to database-->
              

              <div class="sort col-sm-5 col-xs-12">
                <div class="filter-icon md-show">
                  <button type="button" class="btn btn-filter" id="show">
                    Filter <span class="fa fa-filter"></span>
                  </button>
                </div><!-- filter-icon -->

                <div class="dropdown">
                  <!-- <span>Sort by</span> -->
                  <button id="sortFilterCurrentSortButton" class="btn dropdown-toggle" type="button" data-toggle="dropdown">Sort by: <span class="fa fa-sort-alpha-asc"></span><span id="sortByName"></span>
                    
                  </button>
                  <ul class="dropdown-menu" id="sortByUl">
                    
                    
                    <li><a href="#" data-sort-type="price_high_to_low"> Price High to Low</a></li>
                    <li><a href="#" data-sort-type="price_low_to_high"> Price Low to High</a></li>
                    <li><a href="#" data-sort-type="discount"> Discount</a></li>
                  </ul>
                </div>
              </div><!-- sort -->
              <div class="container-fluid">
                <ul class="filter-result">
                 
                </ul>
              </div><!-- container-fluid -->

            </div><!-- row -->

            <div class="row flex protab" style="margin-bottom: 91px;">
              
                              
              
                                         
              
              <div class="col-md-3 col-sm-4 col-xs-6">
                <div class="pro-box">
                  <a href="" target="_blank">
                    <p class="fav"><span class="fa fa-heart" style="color: #0a71ef;"></span></p>
            
                    <img src="images/indigo.jpg" data-src="images/indigo.jpg" class="loadimg pro-img" alt="">
                    
                    <p class="off">15% Off</p>  <p class="brand">Philips</p>
					  
					  
									  
                    <p class="name">LED Ace Saver 2.7W CDL</p>
                        
                    <div class="price-con">
                      <p class="price"><span class="fa fa-inr"></span>111.00</p>
                                             <p class="price-x"><span class="fa fa-inr"></span>130.00</p>
                                           </div>
                  </a> 
                   <div class="cont">
	                     <div autocomplete="off" class="crtdiv">
	                        <span class="qnty">
		                        <span class="dec" onclick="reducecart('1161')">
		                        <i class="fa fa-minus" aria-hidden="true"></i>
		                        </span>
		                        
		                        <input type="number" name="quantity" id="num1161" data-proprice="110.00" data-itemqntity="0" class="num" value="0" onkeyup="updatecart(1161)">

		                        <span class="inc" onclick="addtocart('1161')">
		                        <i class="fa fa-plus" aria-hidden="true"></i>
		                        </span>
	                        </span>
	                         <button id="btn35330" onclick="addtocart('35330')" type="button" class="btn cart  "><img style="width: 17px;" src="images/cart-b.svg" data-src="images/cart-b.svg" class="loadimg order" alt=""> <span class="name" style="font-size: 13px;">Add to cart</span> </button>
	                     </div>
	                  </div>
                </div><!-- pro-box -->
              </div><!-- col-md-3 col-sm-4 col-xs-6 procon -->



              

                                         
             
               <?php
$shown = []; // to avoid showing the same product twice
?>

<?php foreach ($productList  as $row): ?>

    <?php
    // Skip if this product already shown
    if (in_array($row->pro_id, $shown)) {
        continue;
    }
    $shown[] = $row->pro_id;

    // Calculate offer price if needed
    $price = (float)$row->price;
    $offerPer = (float)$row->if_offer_per_price;
    $offerPrice = $offerPer > 0 ? $price - ($price * $offerPer / 100) : $price;
    ?>

    <div class="col-md-3 col-sm-4 col-xs-6 product-box" data-price="<?= $offerPrice ?>"
     data-discount="<?= $offerPer ?>">
        
        <div class="pro-box ">

            <a href="#" target="_blank">
                <p class="fav">
                    <span class="fa fa-heart" style="color:#0a71ef;"></span>
                </p>

                <img src="<?= base_url('uploads/products/').$row->img ?>" class="loadimg pro-img" alt="">

                <?php if ($offerPer > 0): ?>
                    <p class="off"><?= $offerPer ?>% Off</p>
                <?php endif; ?>

                <p class="brand"><?= ucfirst($row->product_type) ?></p>

                <p class="name"><?= $row->product_name ?> (<?= $row->veriant ?>)</p>

                <div class="price-con">
                    <p class="price">
                        <span class="fa fa-inr"></span><?= number_format($offerPrice, 2) ?>
                    </p>

                    <?php if ($offerPer > 0): ?>
                        <p class="price-x">
                            <span class="fa fa-inr"></span><?= number_format($price, 2) ?>
                        </p>
                    <?php endif; ?>
                </div>

            </a>

            <div class="cont">
                <div autocomplete="off" class="crtdiv">

                    <span class="qnty">
                        <span class="dec" onclick="reducecart('<?= $row->pro_id ?>')">
                            <i class="fa fa-minus"></i>
                        </span>

                        <input type="number"
                               id="num<?= $row->pro_id ?>"
                               data-proprice="<?= $offerPrice ?>"
                               data-itemqntity="<?= $row->qty ?>"
                               class="num"
                               value="0"
                               onkeyup="updatecart(<?= $row->pro_id ?>)">

                        <span class="inc" onclick="addtocart('<?= $row->pro_id ?>')">
                            <i class="fa fa-plus"></i>
                        </span>
                    </span>

                    <button id="btn<?= $row->pro_id ?>"
                            onclick="addtocart('<?= $row->pro_id ?>')"
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

                                         
              
            

                            
              
              
              
               

              <div class="scrollproducts"></div>
              

              <input type="hidden" value="1" id="paginatedids" data-getdata="0" data-finalpage="372">
            </div><!-- row -->
            
            
               
            
            
            
          </div><!-- col-md-9 -->

        </div><!-- row -->
      </div><!-- col-md-12 -->

    </div><!-- row -->
  </div>



  <!-----workspace end----->
 









<script src="<?php echo base_url('asset/'); ?>js/mymain.js"></script>
<script src="<?php echo base_url('asset/'); ?>js/swiper.min.js"></script>

  <script>
document.querySelectorAll('#sortByUl a').forEach(function (item) {
    item.addEventListener('click', function (e) {
        e.preventDefault();

        let sortType = this.getAttribute('data-sort-type');

        // Correct container:
        let container = document.querySelector('.protab');

        // Select product cards:
        let items = Array.from(container.querySelectorAll('.product-box'));

        // Sorting logic:
        if (sortType === 'price_high_to_low') {
            items.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
        }

        if (sortType === 'price_low_to_high') {
            items.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
        }

        if (sortType === 'discount') {
            items.sort((a, b) => parseFloat(b.dataset.discount) - parseFloat(a.dataset.discount));
        }

        // Re-append sorted products:
        items.forEach(item => container.appendChild(item));
    });
});


</script>



<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $("#filter_block").hide(500);
  });
  $("#show").click(function(){
    $("#filter_block").show(500);
  });
});
 
</script>
  <?= $this->endSection() ?>