<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>

<!-- Swiper -->
  <div class="swiper-container swiper-banner" data-sliderloaded="0">
    <div class="swiper-wrapper" style="margin-top: 21px;">

      
      <a href="" class="swiper-slide">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main2.jpg" class="sliderloader dsk" alt="">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main2.jpg" class="sliderloadermob res" alt="">
      </a>

      
      <a href="" class="swiper-slide">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main3.jpg" class="sliderloader dsk" alt="">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main3.jpg" class="sliderloadermob res" alt="">
      </a>

      
      <a href="" class="swiper-slide">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main4.png" class="sliderloader dsk" alt="">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main4.png" class="sliderloadermob res" alt="">
      </a>

      
      <a href="" class="swiper-slide">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main5.jpg" class="sliderloader dsk" alt="">
          <img src="<?php echo base_url(); ?>/asset/images/blue-loading.gif" data-src="<?php echo base_url(); ?>/asset/images/main5.jpg" class="sliderloadermob res" alt="">
      </a>

      
     

           
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination banner-pagination"></div>

   

  </div>



<?= $this->endSection() ?>
