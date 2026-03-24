<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper">


  <section class="content-header">
    <h1>

      <small>Banner Management</small>
    </h1>

  </section>


  <section class="content">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Add New </h3>

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
        <form action="" method="post" enctype="multipart/form-data">


          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label style="font-weight:400;">Title <span style="color:#F00;"></span></label>
                <input autocomplete="off" type="text" class="form-control" name="banner_title" value="<?= !empty(set_value('banner_title')) ? set_value('banner_title') : '' ?>">
                <font size="2" color="red"> <?= $errors['banner_title'] ?? '' ?> </font>
              </div>
            </div>

            <div class="col-sm-3">

              <!--Akshaya mam copy and past for replace ok start -->

              <div class="form-group">
                <label style="font-weight:400;">Url <span style="color:#F00;"></span></label>
                <input autocomplete="off" type="text" class="form-control" name="title" value="<?= !empty(set_value('title')) ? set_value('title') : '' ?>">
                <font size="2" color="red"> <?= $errors['title'] ?? '' ?> </font>

              </div>

              <!--Ahkhaya mam copy and past for replace ok End -->
            </div>




            <div class="col-sm-3">

<!--Akshaya mam copy and past for replace ok start -->

<div class="form-group">
  <label style="font-weight:400;">Status<span style="color:#F00;">*</span></label>
<select name="status" class="form-control">
    <option <?= set_select('status',"View", false) ?>  value="View">View</option>
    <option <?= set_select('status',"Hide", false) ?> value="Hide">Hide</option>
</select>
  <font size="2" color="red"> <?= $errors['status'] ?? '' ?> </font>
</div>

<!--Ahkhaya mam copy and past for replace ok End -->
</div>




        
           
        


<div class="col-sm-3" >
                            <!--Akshaya mam copy and past for replace ok start -->

                            <div class="form-group">
                              <label style="font-weight:400;"> Banner<br>Width : 2000px, Height : 550px, Format : jpg,jpeg,png</label> <span style="color:#F00;">*</span>
                            <font size="2" color="red"> <?= $errors['photo'] ?? '' ?> </font> </div>





                            <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-top:5px;" align="center">
                              <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                              <?php
                                
                                    $link = base_url() . "/assets/img/icon.jpg";
                                
                             ?>
                              <img src="<?= $link ?>">
                              </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                              <div>
                                <span class="btn btn-white btn-file">
                                  <span class="fileinput-new">Select Image</span>

                                  <span class="btn btn-orange fileinput-exists"><i class="fa fa-edit"></i></span>
                                  <input type="file" name="photo" accept="image/*">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash-o"></i></a>
                              </div>
                            </div>



                            <!--Ahkhaya mam copy and past for replace ok End -->
                          </div>





              
</div>

                            <div class="form-group">
                              <label style="font-weight:400;">Mobile Banner<br>Width : 640px, Height : 360px, Format : jpg,jpeg,png</label> <span style="color:#F00;">*</span>
                              <font size="2" color="red"> <?= $errors['mobile_photo'] ?? '' ?> </font>
                            </div>

                            <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-top:5px;" align="center">
                              <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                              <?php
                                $link = base_url() . "/assets/img/icon.jpg";
                              ?>
                              <img src="<?= $link ?>">
                              </div>
                              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                              <div>
                                <span class="btn btn-white btn-file">
                                  <span class="fileinput-new">Select Mobile Banner</span>

                                  <span class="btn btn-orange fileinput-exists"><i class="fa fa-edit"></i></span>
                                  <input type="file" name="mobile_photo" accept="image/*">
                                </span>
                                <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash-o"></i></a>
                              </div>

       
          <div class="col-sm-12" align="left">
            <a href="<?= base_url() ?>users/banner-list"><button type="button" class="btn btn-danger"> <i class="fa fa-arrow-left"></i> &nbsp;&nbsp;Cancel</button></a>
            <button type="reset" class="btn btn-warning"> Reset</button>
            <button type="submit" value="add" name="add" class="btn btn-primary"> Save&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
          </div>
        </form>
      </div>
    </div>





  </section>



</div>
<script>
    $(document).ready(function() {
        setunit();
        $("#type").change(function() {
         setunit();
        });
      function  setunit(){
        let type = $("#type").val();
        let data = <?//=json_encode($type)?>;

        if(type)
        {
            const found = data.find(element => element["id"] == type);
            $("#unit").html(`Unit in ${found['units']}`);
           
        }
        }
       
    });
</script>
<?= $this->endSection() ?>