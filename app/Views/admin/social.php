<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper">


  <section class="content-header">
    <h1>

      <small>Social Media</small>
    </h1>

  </section>


  <section class="content">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Update </h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>

      <div class="box-body">
        <div class="col-sm-12">
          <?php
          $msg2 = $session->get('success');
          if (!empty($msg2)) { ?>

            <div class="alert alert-success alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Success!</strong> <?= $msg2 ?>
            </div> <?php } ?>
          <?php
          $msg1 = $session->get('error');
          if (!empty($msg1)) { ?>

            <div class="alert alert-warning alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Warning!</strong> <?= $msg1 ?>
            </div> <?php } ?>
        </div>
        <form action="<?= base_url('media/update/1') ?>" method="post" enctype="multipart/form-data">
        <div class="row">



            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">GooglePlay Link</label>
  <input autocomplete="off" type="text" class="form-control" name="gp_link" value="<?= !empty(set_value('gp_link')) ? set_value('gp_link') : $result->gp_link ?>">
  <font size="2" color="red"> <?= $error['gp_link'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">AppStore Link</label>
  <input autocomplete="off" type="text" class="form-control" name="as_link" value="<?= !empty(set_value('as_link')) ? set_value('as_link') : $result->as_link ?>">
  <font size="2" color="red"> <?= $error['as_link'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Address</label>
  <input autocomplete="off" type="text" class="form-control" name="address" value="<?= !empty(set_value('address')) ? set_value('address') : $result->address ?>">
  <font size="2" color="red"> <?= $error['address'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Pincode</label>
  <input autocomplete="off" type="text" class="form-control" name="pincode" value="<?= !empty(set_value('pincode')) ? set_value('pincode') : $result->pincode ?>">
  <font size="2" color="red"> <?= $error['pincode'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Email</label>
  <input autocomplete="off" type="text" class="form-control" name="email" value="<?= !empty(set_value('email')) ? set_value('email') : $result->email ?>">
  <font size="2" color="red"> <?= $error['email'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Mobile</label>
  <input autocomplete="off" type="text" class="form-control" name="mobile" value="<?= !empty(set_value('mobile')) ? set_value('mobile') : $result->mobile ?>">
  <font size="2" color="red"> <?= $error['mobile'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Instagram Link</label>
  <input autocomplete="off" type="text" class="form-control" name="insta_link" value="<?= !empty(set_value('insta_link')) ? set_value('insta_link') : $result->insta_link ?>">
  <font size="2" color="red"> <?= $error['insta_link'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Facebook Link</label>
  <input autocomplete="off" type="text" class="form-control" name="face_link" value="<?= !empty(set_value('face_link')) ? set_value('face_link') : $result->face_link ?>">
  <font size="2" color="red"> <?= $error['face_link'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Twitter Link</label>
  <input autocomplete="off" type="text" class="form-control" name="twi_link" value="<?= !empty(set_value('twi_link')) ? set_value('twi_link') : $result->twi_link ?>">
  <font size="2" color="red"> <?= $error['twi_link'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">YouTube Link</label>
  <input autocomplete="off" type="text" class="form-control" name="you_link" value="<?= !empty(set_value('you_link')) ? set_value('you_link') : $result->you_link ?>">
  <font size="2" color="red"> <?= $error['you_link'] ?? '' ?> </font>

</div>

</div>

            <div class="col-sm-3">

<div class="form-group">
  <label style="font-weight:400;">Linkedin Link</label>
  <input autocomplete="off" type="text" class="form-control" name="lin_link" value="<?= !empty(set_value('lin_link')) ? set_value('lin_link') : $result->lin_link ?>">
  <font size="2" color="red"> <?= $error['lin_link'] ?? '' ?> </font>

</div>
</div>
<div class="col-sm-8">
<div class="form-group">
  <label style="font-weight:400;">About</label>
  <textarea class="form-control" name="about" rows="3"><?= !empty(set_value('about')) ? set_value('about') : $result->about ?></textarea>
  <font size="2" color="red"> <?= $error['about'] ?? '' ?> </font>
</div>
</div>
</div>


        
         

        


       
    
      <div class="row">

          <div class="col-sm-12" align="left">
            <a href="<?= getenv('app_baseURL') ?>media"><button type="button" class="btn btn-danger"> <i class="fa fa-arrow-left"></i> &nbsp;&nbsp;Cancel</button></a>
            <button type="reset" class="btn btn-warning"> Reset</button>
            <button type="submit" class="btn btn-primary"> Update Data&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
          </div>
      </div>
      </div> 
    </form>
    </div>





  </section>



</div>



<?= $this->endSection() ?>