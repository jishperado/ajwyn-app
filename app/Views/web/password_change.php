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
      <li>Password Change</li>
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


 


  <!-- cancel MODAL end -->



    <div class="col-md-9 boxcol">
      <div class="">
      <h2 class="subhd hdng">Password Change</h2>   

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
                <form action="" method="POST">
                  
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" >
                        <span id="new_password_error" class="text-danger"><?= $errors['new_password'] ?? '' ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" >
                        <span id="confirm_password_error" class="text-danger"><?= $errors['confirm_password'] ?? '' ?></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
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
function setCancelData(order_id, product_id) {
    document.getElementById('cancel_order_id').value = order_id;
    document.getElementById('cancel_product_id').value = product_id;
}
function openTrackModal(track_id, track_url) {
    document.getElementById('track_code').textContent = track_id;
    document.getElementById('track_btn').href = track_url;
    $('#myModal').modal('show');
}

function copyTrackCode() {
    let code = document.getElementById('track_code').textContent;
    navigator.clipboard.writeText(code);
    alert("Tracking code copied!");
}

if (track_url === "") {
    document.getElementById('track_btn').style.pointerEvents = "none";
    document.getElementById('track_btn').style.opacity = "0.5";
} else {
    document.getElementById('track_btn').style.pointerEvents = "auto";
    document.getElementById('track_btn').style.opacity = "1";
}


</script>




  <?= $this->endSection() ?>
  