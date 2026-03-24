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
      <li>Edit Shipping Address</li>
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
      <h2 class="subhd hdng">Edit shipping addresses</h2>   

<style>
.rating {
  direction: rtl;
  unicode-bidi: bidi-override;
}
.rating > label {
  color: #ddd;
  font-size: 20px;
  padding: 0 5px;
  cursor: pointer;
}
.rating > input {
  display: none;
}
.rating > input:checked ~ label,
.rating > label:hover,
.rating > label:hover ~ label {
  color: #ffc107;
}
</style>

			    <div class="container-fluid cartitem cartbox14972" style="margin-bottom: 10px;">
			      

			      <div class="col-md-12 col-xs-8 details nopadcartr" style="margin-bottom: 10px;">  
			        <div class="row">
              <div class="deliverybox">
          
                    
         
         
          <form action="" method="post">

    <div class="form-group">
        <h5>Name</h5>
        <input type="text" name="name" class="form-control"
               value="<?= set_value('name', $address->name) ?>"
               placeholder="Enter your name">
    </div>

    <div class="form-group">
        <h5>Address</h5>
        <textarea class="form-control" name="address" placeholder="Address"><?= set_value('address', $address->address) ?></textarea>
    </div>

    <div class="form-group">
        <h5>Country/region</h5>
        <select class="form-control" id="country" onchange="get_state(this.value)" name="country">
            <option>Select</option>
            <?php foreach($country as $c) { ?>
                <option value="<?= $c->id ?>"
                    <?= ($c->id == set_value('country', $address->country)) ? 'selected' : '' ?>>
                    <?= $c->name ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <h5>State/Province</h5>
        <select class="form-control" name="state" id="state" onchange="get_district(this.value)">
            <option>Select</option>
            <?php if (!empty($states)) { foreach($states as $s) { ?>
                <option value="<?= $s->id ?>"
                    <?= ($s->id == set_value('state', $address->state)) ? 'selected' : '' ?>>
                    <?= $s->name ?>
                </option>
            <?php }} ?>
        </select>
    </div>

    <div class="form-group">
        <h5>Cities</h5>
        <select class="form-control" name="city" id="district">
            <option>Select</option>
            <?php if (!empty($districts)) { foreach($districts as $d) { ?>
                <option value="<?= $d->id ?>"
                    <?= ($d->id == set_value('city', $address->city)) ? 'selected' : '' ?>>
                    <?= $d->name ?>
                </option>
            <?php }} ?>
        </select>
    </div>

    <div class="form-group">
        <h5>Postal/ZIP code</h5>
        <input type="text"
               name="pincode"
               class="form-control"
               value="<?= set_value('pincode', $address->pincode) ?>"
               placeholder="Zip Code">
    </div>

    <div class="form-group">
        <h5>Mobile Number</h5>
        <input type="text"
               name="mobile"
               class="form-control"
               value="<?= set_value('mobile', $address->mobile) ?>"
               placeholder="Mobile Number">
    </div>

    <div class="form-group">
        <h5>Landmark</h5>
        <input type="text"
               name="landmark"
               class="form-control"
               value="<?= set_value('landmark', $address->landmark) ?>"
               placeholder="Landmark">
    </div>

    <button type="submit" class="btn btn-primary btn-block">Update Address</button>

</form>
</div></div>
			      </div><!-- col-md-7 -->

			      
			    </div><!-- cartitem -->






          









          
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
  