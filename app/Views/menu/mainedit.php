<?= $this->extend('admin/admin_surround') ?>

<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1472px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Control panel
                <small>Main List
                </small>
            </h1>
            <!--          <ol class="breadcrumb">
                            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="active">Dashboard</li>
                          </ol>-->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Main List
                            </h3><br>





                        </div><!-- /.box-header -->
                        <div class="box-body">

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
                            <!-- form start -->
                            <form action="" method="post" enctype="multipart/form-data">
                              
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Main</label><span class="text-danger">*</span>
                                    <input type="text" name="main" class="form-control" value="<?=!empty(set_value("main")) ? set_value("main") : $result[0]->name?>">
                                    <font size="2" color="red"><?= $errors['main'] ?? '' ?> </font>
                    
                                </div>
                               
                            <div class="col-md-3">
    <label for="">Order</label><span class="text-danger">*</span>
    <input type="number" name="order" class="form-control" value="<?= set_value("order") ? set_value("order") : $result[0]->orderlist ?>">
    <font size="2" color="red"><?= $errors['order'] ?? '' ?> </font>
</div>


                                <div class="col-md-3" >
                                    <label for="">Is Active</label><span class="text-danger">*</span>
                                   <select class="form-control" name="status" id="">
                                   <option <?= set_select('active',"Y",$result[0]->is_active == 'Y' ? true : false) ?>  value="Y">Yes</option>
    <option <?= set_select('active',"N",$result[0]->is_active == 'N' ? true : false) ?> value="N">No</option>
</select>

                                   </select>
                                   <font size="2" color="red"><?= $errors['status'] ?? '' ?> </font>
                    
                                </div>
</div>
 <div class="row">

<div class="col-md-3" style="margin-top: 30px;">
    <label for="">Category Image (The Image is Shown in main Page Category List)</label>
    <input type="file" name="pic" class="form-control">
    <?php if (!empty($result[0]->img)) { ?>
        <img src="<?= base_url() ?>web/images/<?= $result[0]->img ?>" width="50" height="50">
    <?php } ?>
    <font size="2" color="red"><?= $errors['pic'] ?? '' ?> </font>
</div>
</div>
 <div class="row">
<div class="col-md-3" style="margin-top: 30px;">
    <label for="">Icon (This is for The Mobile Menu )</label>
    <input type="file" name="icon" class="form-control">
    <?php if (!empty($result[0]->icon)) { ?>
        <img src="<?= base_url() ?>web/images/<?= $result[0]->icon ?>" width="50" height="50">
    <?php } ?>
    <font size="2" color="red"><?= $errors['icon'] ?? '' ?> </font>
</div>
</div>
 <div class="row">
<div class="col-md-3" style="margin-top: 30px;">
    <label for="">Banner (This Is Not Mandatory)</label>
    <input type="file" name="banner" class="form-control">
    <?php if (!empty($result[0]->banner)) { ?>
        <img src="<?= base_url() ?>web/images/<?= $result[0]->banner ?>" width="50" height="50">
    <?php } ?>
    <font size="2" color="red"><?= $errors['banner'] ?? '' ?> </font>
</div>
</div>
 <div class="row">
<div class="col-md-3" style="margin-top: 30px;">
    <label for="">Menu Image (Shown Inside The Navbar Menu)</label>
    <input type="file" name="menu_image" class="form-control">
    <?php if (!empty($result[0]->menu_image)) { ?>
        <img src="<?= base_url() ?>web/images/<?= $result[0]->menu_image ?>" width="50" height="50">
    <?php } ?>
    <font size="2" color="red"><?= $errors['menu_image'] ?? '' ?> </font>
</div>
</div>

                             
                         
                                <div class="col-md-12">
                                    <a href="<?= base_url() ?>users/menu">       <input style="margin-top: 25px" type="button" value="Back" class="btn btn-danger"></a>
                                    <input style="margin-top: 25px" type="submit" value="Save" class="btn btn-success">
                                </div>
                            </div>
                             </form>
                            <br>
                          

                        </div>
                        <!-- /.box -->
                    </div><!-- /.col -->
                    <!-- /.col -->
                </div><!-- /.row -->

        </section><!-- /.content -->
    </div>
    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="head1"></h4>
                </div>
                <div class="modal-body">
                    <p id="message1"></p>
                </div>
                <div class="modal-footer">
                    <form action="<?=base_url()?>bannerdlt" id="form1" method="POST" enctype="multipart/form-data">
                   
                    <button type="submit"
                            class="btn btn-danger" style="margin-right:0px;">&nbsp;&nbsp;Yes</button>
                     
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#selectall").click(function() {
                $('.check').not(this).prop('checked', this.checked);
                $('.check').attr('checked', this.checked);
            });
            $(".check").click(function() {
                if ($(this).prop("checked") == false) {
                    $('#selectall').prop('checked', false);
                }
            });
            $("#selectalltomsg").click(function() {
                $('.checkmsg').not(this).prop('checked', this.checked);
                $('.checkmsg').attr('checked', this.checked);
            });
            $(".checkmsg").click(function() {
                if ($(this).prop("checked") == false) {
                    $('#selectalltomsg').prop('checked', false);
                }
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $("#deleteconf").click(function() {
            $('#head1').html('Delete?');
            $('#message1').html('Are you sure you want to delete the selected items?');
            $('#deleteModal').modal('show');


        });
       
    });
</script>

<?= $this->endSection() ?>