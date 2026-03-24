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
                            <h3 class="box-title">Flash List
                            </h3><br>





                        </div><!-- /.box-header -->
                        <div class="box-body">
                        <?php $errors = session('validation');
                        $errors = $errors['errors'] ?? [];
                        
                        ?>
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
                            <form action="<?=getenv('app_baseURL')?>flash/update/<?=$result->id?>" method="post" enctype="multipart/form-data">
                              
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Title</label><span class="text-danger">*</span>
                                    <input type="text" name="title" class="form-control" value="<?=!empty(set_value("title")) ? set_value("title") : $result->title?>">
                                    <font size="2" color="red"><?= $errors['title'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Message</label><span class="text-danger">*</span>
                                    <textarea name="message" class="form-control"><?=!empty(set_value("message")) ? set_value("message") : $result->message?></textarea>
                                    <font size="2" color="red"><?= $errors['message'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Url</label><span class="text-danger">*</span>
                                    <input type="text" name="url" class="form-control" value="<?=!empty(set_value("url")) ? set_value("url") : $result->url?>">
                                    <font size="2" color="red"><?= $errors['url'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Is Active</label><span class="text-danger">*</span>
                                   <select class="form-control" name="isactive" id="">
                                       <option <?=set_select("isactive","Y",$result->isactive == 'Y' ? true : false)?> value="Y">Active</option>
                                       <option <?=set_select("isactive","N",$result->isactive == 'N' ? true : false)?> value="N">Deactive</option>
                                   </select>
                                   <font size="2" color="red"><?= $errors['isactive'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-12" align="center">
                                    <br>
                                    <a href="<?=getenv('app_baseURL')?>flash" class="btn btn-danger">Back</a>
                                    <input  type="submit" value="Update" class="btn btn-success">
                                </div>
                            </div>
                             </form>
                          
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
                    <form action="<?=getenv('app_baseURL')?>users/menudlt" id="form1" method="POST" enctype="multipart/form-data">
                   
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