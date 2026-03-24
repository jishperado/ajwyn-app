<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>

  <style>
    .mobileadd{display:none;}
    @media(max-width:768px)
    {
        .desktopadd{display:none;}
        .mobileadd{display:block;}
    }
</style>

<!-----workspace start----->


 <div class="cmtop"></div>
  <div class="container cmpad" style="margin-top: 20px;">
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb">
          <li><a href="<?= base_url() ?>"><span class="fa fa-home"></span> Home </a></li>
          <li>Search Results</li>
        </ul>
      </div><!-- col-md-12 -->

      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3 md-hide">
            
            <h2 class="filterhd md-show">Filter Search</h2>

    
   



            <div class="filter" id="categoryFilter">
          <p class="head">Category</p>
          <ul>
        <?php foreach($category as $cat): ?>
            <li>
              <a href="<?= base_url('product-filter/' . $cat['id'] . '/1') ?>">
                <?= $cat['name'] ?>
              </a>
            </li>
          <?php endforeach; ?>
                      </ul>
        </div><!-- filter -->
        
    
          <div class="filter" id="subcategoryFilter">
      <p class="head">Sub Category test</p>
      <ul>
       
                          <?php foreach($subcategory as $subcat): ?>
                          <li>
                            <a href="<?= base_url('product-filter/' . $subcat['id'] . '/2') ?>">
                              <?= $subcat['name'] ?>
                            </a>
                          </li>
                          <?php endforeach; ?>
                        
                         
                
      
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
          <?php foreach($category as $cat): ?>
            <li>
              <a href="<?= base_url('product-filter/' . $cat['id'] . '/1') ?>">
                <?= $cat['name'] ?>
              </a>
            </li>
          <?php endforeach; ?>
                      </ul>
        </div><!-- filter -->
        
    
          <div class="filter" id="subcategoryFilter">
      <p class="head">Sub Category</p>
      <ul>
       
        
                               <?php foreach($subcategory as $subcat): ?>
                          <li>
                            <a href="<?= base_url('product-filter/' . $subcat['id'] . '/2') ?>">
                              <?= $subcat['name'] ?>
                            </a>
                          </li>
                          <?php endforeach; ?>
                
      
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
               
                                                             
                  <div class="result-status col-sm-7"> Showing Results  </div>
                            
              
              
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
              
                              
              
                                         
              
<?php
$shown = [];
?>

<?php foreach ($productList as $row): ?>
  

    <?php

    if (in_array($row->pro_id, $shown)) {
        continue;
    }

    // Find first row with qty > 0 OR fallback first row
    $selected = $row;
    foreach ($productList as $r) {
        if ($r->pro_id == $row->pro_id && $r->qty > 0) {
            $selected = $r;
            break;
        }
    }

    $shown[] = $row->pro_id;

    // Base price
    $price = (float)$selected->price;
    $offerPer = (float)$selected->if_offer_per_price;
    $taxPer = isset($selected->tax) ? (float)$selected->tax : 0;

    // Price after discount (no tax yet)
    $offerPrice = $offerPer > 0 ? $price - ($price * $offerPer / 100) : $price;

    // Add tax
    $priceWithTax = $price + ($price * $taxPer / 100);
    $offerPriceWithTax = $offerPrice + ($offerPrice * $taxPer / 100);
    ?>

    <div class="col-md-3 col-sm-4 col-xs-6 product-box"
         data-price="<?= $offerPriceWithTax ?>"
         data-discount="<?= $offerPer ?>">

        <div class="pro-box">

            <a href="<?= base_url('product-details/' . $selected->pro_id) ?>" target="_blank">
                <p class="fav">
                    <span class="fa fa-heart" style="color:#0a71ef;"></span>
                </p>

                <img src="<?= base_url('uploads/products/') . $selected->img ?>" class="loadimg pro-img" alt="">

                <?php if ($offerPer > 0): ?>
                    <p class="off"><?= $offerPer ?>% Off</p>
                <?php endif; ?>

                <p class="brand"><?= ucfirst($selected->type_name) ?></p>

                <p class="name"><?= $selected->product_name ?> (<?= $selected->veriant ?>)</p>

                <div class="price-con">
                    <p class="price">
                        <span class="fa fa-inr"></span><?= number_format($offerPriceWithTax, 2) ?>
                    </p>

                    <?php if ($offerPer > 0): ?>
                        <p class="price-x">
                            <span class="fa fa-inr"></span><?= number_format($priceWithTax, 2) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </a>

            <div class="cont">
                <div autocomplete="off" class="crtdiv">

                    <?php if ($selected->qty > 0): ?>
                        <button id="btn<?= $selected->pro_id ?>"
                                onclick="addtocartmain('<?= $selected->pro_id ?>', '<?= $selected->pv_id ?>')"
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



              





            
                                         
              
            

                            
              
              
              
               

              <div class="scrollproducts"></div>
              

              <input type="hidden" value="1" id="paginatedids" data-getdata="0" data-finalpage="372">
            </div><!-- row -->
            
            
               
            
            
            
          </div><!-- col-md-9 -->

        </div><!-- row -->
      </div><!-- col-md-12 -->

    </div><!-- row -->
  </div>
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



  <?= $this->endSection() ?>

  

  <!-----workspace end----->