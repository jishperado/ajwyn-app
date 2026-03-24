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
      <li>Payment</li>
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






    <!-- track MODAL start -->


 


  <!-- cancel MODAL end -->



    <div class="col-md-9 boxcol">
      <div class="">
      <h2 class="subhd hdng">Payment</h2>   

              <div class="col-sm-12">
          <?php
          $msg2 = $session->get('success');
          if (!empty($msg2)) { ?>

            <div class="alert alert-success alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong><i style="font-size: 25px;" class="fa fa-check-square-o"></i></strong> <?= $msg2 ?>
            </div> <?php } ?>
  
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="https://pgbiz.omniware.in/v2/paymentrequest" id="payment_form" method="POST">
<input type="hidden" value="<?php echo $post['hash']; ?>"                   name="hash"/>
<input type="hidden" value="<?php echo $post['api_key'];?>"        name="api_key"/>
<input type="hidden" value="<?php echo $post['return_url']; ?>"    name="return_url"/>
<input type="hidden" value="<?php echo $post['mode'];?>"           name="mode"/>
<input type="hidden" value="<?php echo $post['order_id'];?>"       name="order_id"/>
<input type="hidden" value="<?php echo $post['amount'];?>"         name="amount"/>
<input type="hidden" value="<?php echo $post['currency'];?>"       name="currency"/>
<input type="hidden" value="<?php echo $post['description'];?>"    name="description"/>
<input type="hidden" value="<?php echo $post['name'];?>"           name="name"/>
<input type="hidden" value="<?php echo $post['email'];?>"          name="email"/>
<input type="hidden" value="<?php echo $post['phone'];?>"          name="phone"/>
<input type="hidden" value="<?php echo $post['address_line_1'];?>" name="address_line_1"/>
<input type="hidden" value="<?php echo $post['address_line_2'];?>" name="address_line_2"/>
<input type="hidden" value="<?php echo $post['city'];?>"           name="city"/>
<input type="hidden" value="<?php echo $post['state'];?>"          name="state"/>
<input type="hidden" value="<?php echo $post['zip_code'];?>"       name="zip_code"/>
<input type="hidden" value="<?php echo $post['country'];?>"        name="country"/>
<input type="hidden" value="<?php echo $post['udf1'] ?? '';?>"           name="udf1"/>
<input type="hidden" value="<?php echo $post['udf2'] ?? '';?>"           name="udf2"/>
<input type="hidden" value="<?php echo $post['udf3'] ?? '';?>"           name="udf3"/>
<input type="hidden" value="<?php echo $post['udf4'] ?? '';?>"           name="udf4"/>
<input type="hidden" value="<?php echo $post['udf5'] ?? '';?>"           name="udf5"/>
<div id="loadingSpinner" style="display: none; text-align: center; margin-top: 20px;">
    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
    <p>Redirecting to payment gateway...</p>
</div>
<script>
    document.getElementById('payment_form').addEventListener('submit', function() {
        document.getElementById('loadingSpinner').style.display = 'block';
        this.querySelector('input[type="submit"]').style.display = 'none';
    });
</script>


<center>
<noscript><input type="submit" class="btn btn-primary" value="Continue"/></center></noscript>
</form>


            </div>
        </div>

      



	







			   



          
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
 
<script>
$(document).ready(function() {
    var $paymentForm = $('#payment_form');
    if ($paymentForm.length) {
        // Track AddPaymentInfo before redirecting to payment gateway
        if (typeof fbq === 'function') {
            fbq('track', 'AddPaymentInfo', {
                value: parseFloat('<?= $post['amount'] ?? 0 ?>'),
                currency: '<?= $post['currency'] ?? "INR" ?>',
                content_type: 'product',
                order_id: '<?= $post['order_id'] ?? "" ?>'
            });
        }
        $paymentForm.submit();
    }
});


</script>




  <?= $this->endSection() ?>
  