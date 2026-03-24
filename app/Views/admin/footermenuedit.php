<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper">


  <section class="content-header">
    <h1>

      <small> Update</small>
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
        <form action="" method="post" enctype="multipart/form-data">
        <div class="row">


           

        <div class="col-sm-3">

<!--Akshaya mam copy and past for replace ok start -->

<div class="form-group">
  <label style="font-weight:400;">Select Menu Head<span style="color:#F00;">*</span></label>
<select name="main" class="form-control">
  <?php
foreach($main as $value)
{ 
      if($result[0]->title_footlink_id == $value->id)
  {


?>
<option selected value="<?php echo $value->id ?>"><?php echo $value->title ?></option>

<?php
 } 
 else{
   ?>
  <option  value="<?php echo $value->id ?>"><?php echo $value->title ?></option>
 
   <?php
 }
}
?>

</select>
  <font size="2" color="red"> <?= $errors['main'] ?? '' ?> </font>
</div>

<!--Ahkhaya mam copy and past for replace ok End -->
</div>



        <div class="col-sm-3">

<!--Akshaya mam copy and past for replace ok start -->

<div class="form-group">
  <label style="font-weight:400;">Sub Head <span style="color:#F00;">*</span></label>
  <input autocomplete="off" type="text" class="form-control" id="exampleInputEmail1" name="title" value="<?= !empty(set_value('title')) ? set_value('title') : $result[0]->head ?>">
  <font size="2" color="red"> <?= $errors['title'] ?? '' ?> </font>

</div>

<!--Ahkhaya mam copy and past for replace ok End -->
</div>

<div class="col-sm-3">

<!--Akshaya mam copy and past for replace ok start -->

<div class="form-group">
  <label style="font-weight:400;">URL <span style="color:#F00;">*</span></label>
  <input autocomplete="off" type="text" class="form-control" name="url" value="<?= !empty(set_value('url')) ? set_value('url') : $result[0]->url ?>">
  <font size="2" color="red"> <?= $errors['url'] ?? '' ?> </font>

</div>

<!--Ahkhaya mam copy and past for replace ok End -->
</div>


        
           
            <div class="col-sm-3">

<!--Akshaya mam copy and past for replace ok start -->

<div class="form-group">
  <label style="font-weight:400;">Status<span style="color:#F00;">*</span></label>
<select name="status" class="form-control">
    <option <?= set_select('status',"View",  $result[0]->status == "View" ? true :false) ?>  value="View">View</option>
    <option <?= set_select('status',"Hide",  $result[0]->status == "Hide" ? true :false) ?> value="Hide">Hide</option>
</select>
  <font size="2" color="red"> <?= $errors['status'] ?? '' ?> </font>
</div>

<!--Ahkhaya mam copy and past for replace ok End -->
</div>


</div>
<div class="row">
    <div class="col-md-12">
        <label for="">Content</label><span class="text-danger"></span>
        <textarea class="form-control" name="content" id="editor1" rows="3"><?= !empty(set_value("content")) ? set_value("content") : $result[0]->content ?></textarea>
        <font size="2" color="red"><?= $error['content'] ?? '' ?> </font>
    </div>  
</div>

       
    
      <div class="row">

          <div class="col-sm-12" style="padding-top: 20px" align="left">
            <a href="<?= base_url() ?>users/footermenu-list"><button type="button" class="btn btn-danger"> <i class="fa fa-arrow-left"></i> &nbsp;&nbsp;Cancel</button></a>
            <button type="reset" class="btn btn-warning"> Reset</button>
            <button type="submit" value="add" name="add" class="btn btn-primary"> Update Data&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
          </div>
      </div>
      </div> 
    </form>
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

<script src="https://www.mesfrcschool.com/asset/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function(){

  CKEDITOR.replace('editor1', {
filebrowserUploadUrl: "<?= getenv('app_baseURL') ?>users/ck-editor",
filebrowserBrowseUrl: '<?= getenv('app_baseURL') ?>users/ck-browser',
filebrowserUploadMethod: "form"
});
});
</script>
<?= $this->endSection() ?>