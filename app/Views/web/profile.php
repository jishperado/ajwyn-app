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
      <li>Address</li>
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




    <!-- track MODAL start -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

   
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Track your order</h4>
      </div>

      <div class="modal-body">
        <p><img src="images/caution.png" width="50px"><br>
          To track your shipment, copy the tracking code below and click the track button. On the next screen, you can track using the tracking code you copied.<br>
          Tracking Code: xrtyuo463#dsfr &nbsp;<span class="again text-center">Copy <i class="fa fa-copy"></i></span>
        </p>
        <p> <a href="" target="_blank"><span class="track text-center"  onclick="" >Track your order &nbsp;<i class="fa fa-map-marker"></i></span></a></p>
      </div>



    </div>

  </div>
</div>


  <!-- track MODAL end -->


  <!-- cancel MODAL start -->
<div id="myModal_cancel" class="modal fade" role="dialog">
  <div class="modal-dialog">

   
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Customer Order Cancellation</h4>
      </div>

      <div class="modal-body">
       <form>


            <div class="form-group">
                   <h5>Cancellation Reason </h5>
                   <select class="form-control">
                    <option>Select Reason</option>
                    <option value="">I ordered by mistake</option>
                    <option value="">I no longer need the product</option>
                    <option value="">I found a better price elsewhere</option>
                    <option value="">I want to change the delivery address</option>
                    <option value="">I want to buy a different product</option>
                    <option value="">I selected the wrong size/color</option>
                    <option value="">Delivery is taking too long</option>
                    <option value="">I changed my mind</option>
                    <option value="">Ordered multiple items by mistake</option>
                    <option value="">Other</option>

                   </select>
                </div>
            
            <div class="form-group">
                    
                   <textarea class="form-control" placeholder="Cancellation Reason"></textarea>
                </div>

                


                <button class="btn btn-primary btn-block">Cancel Order</button>
            
          </form>
      </div>



    </div>

  </div>
</div>


  <!-- cancel MODAL end -->


   <div class="col-md-9 boxcol">
    
      <div class="">
      <h2 class="subhd hdng">Profile</h2>   
                 <div class="col-sm-12">
          <?php
          $msg2 = $session->get('success');
          if (!empty($msg2)) { ?>

            <div class="alert alert-success alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong><i style="font-size: 25px;" class="fa fa-check-square-o"></i></strong> <?= $msg2 ?>
            </div> <?php } ?>
  
        </div>

<form method="post" action="">
    <input type="hidden" name="id" value="<?= (string) $a['id'] ?>">

    <div class="container-fluid cartitem cartbox<?= (string) $a['id'] ?>" >

        <div class="col-md-5 col-xs-8 details nopadcartr" style="margin-bottom: 20px;">  

<span class="name">
    <strong>Name</strong>
    <input type="text" name="name" class="form-control"
           value="<?= (string) $a['name'] ?>" >
    <font color="red">
        <?= isset($validation) ? $validation->getError('name') : '' ?>
    </font>
</span>

<div class="name" style="margin-top:10px;">
    Mobile:
    <input type="text" name="mobile" class="form-control"
           value="<?= (string) $a['mobile'] ?>" >
    <font color="red">
        <?= isset($validation) ? $validation->getError('mobile') : '' ?>
        <?= is_string($error) && strpos($error, 'Mobile') !== false ? $error : '' ?>
    </font>
</div>

<div class="name" style="margin-top:10px;">
    E-mail:
    <input type="email" name="email" class="form-control"
           value="<?= (string) ($a['email'] ?? '') ?>">
    <font color="red">
        <?= isset($validation) ? $validation->getError('email') : '' ?>
        <?= is_string($error) && strpos($error, 'Email') !== false ? $error : '' ?>
    </font>
</div>


        </div><!-- col-md-5 -->

        <div class="clearfix"></div>
        <br>
<div class="text-right" style="margin-bottom:10px">
    <button type="submit" class="btn btn-primary">Update</button>

    <!-- New button to open modal -->
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#passwordModal">
        Change Password
    </button>
</div>


    </div><!-- cartitem -->
</form>

<!-- Change Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="<?= base_url('cha_pass') ?>" method="POST">
        <div class="modal-body">

<div class="form-group">
    <label>Current Password</label>
    <input type="password" name="old_password" class="form-control" >
    <font color="red">
        <?= session()->getFlashdata('old_password_error') ?>
    </font>
</div>

<div class="form-group">
    <label>New Password</label>
    <input type="password" name="new_password" class="form-control" >
</div>

<div class="form-group">
    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" class="form-control" >
    <font color="red">
        <?= session()->getFlashdata('confirm_password_error') ?>
    </font>
</div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary " data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
<?php if (
    session()->getFlashdata('old_password_error') ||
    session()->getFlashdata('confirm_password_error') ||
    session()->getFlashdata('password_success')
): ?>
    $(document).ready(function() {
        $('#passwordModal').modal('show');
    });
<?php endif; ?>
</script>



      






          









          
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
function get_state(id) {
    $.ajax({
        type: "POST",
        url: "<?= base_url('get_state') ?>",
        data: { id: id },
        dataType: "json", // <-- This automatically parses JSON
        success: function(state) {
            $("#state").empty().append("<option value=''>Select</option>");

            $.each(state, function(key, value) {
                $("#state").append(
                    "<option value='" + value.id + "'>" + value.name + "</option>"
                );
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading states:", error);
        }
    });
}
function get_district(state_id) {
  
    $.ajax({
        type: "POST",
        url: "<?= base_url('get_district') ?>",
        data: { state_id: state_id },
        dataType: "json", // <-- This automatically parses JSON
        success: function(district) {
            $("#district").empty().append("<option value=''>Select</option>");

            $.each(district, function(key, value) {
                $("#district").append(
                    "<option value='" + value.id + "'>" + value.name + "</option>"
                );
            });
        },
        error: function(xhr, status, error) {
            console.error("Error loading districts:", error);
        }
    });
}
</script>






  <?= $this->endSection() ?>
  
