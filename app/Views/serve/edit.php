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
                            <h3 class="box-title">
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
                            <form action="<?=base_url()?>serve/update/<?=$raw->id?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Title</label><span class="text-danger"></span>
                                    <input type="text" name="head" class="form-control" value="<?=!empty(set_value("head")) ? set_value("head") : $raw->head?>">
                                    <font size="2" color="red"><?= $err['head'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Description</label><span class="text-danger"></span>
                                    <Textarea name="description" class="form-control"><?=!empty(set_value("description")) ? set_value("notes") : $raw->description?></Textarea>
                                    <font size="2" color="red"><?= $err['description'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Icon</label><span class="text-danger">*</span>
                                    <?php if (!empty($raw->icon)) { ?>
                                        <img src="<?=base_url()?>web/images/<?=$raw->icon?>" width="50" height="50">
                                    <?php } ?>
                                    <input type="file" name="icon" class="form-control">
                                    <font size="2" color="red"><?= $err['icon'] ?? '' ?> </font>
                    
                                </div>
                              
                                <div class="col-md-12" align="center">
                                    <br>
                                <a href="<?= base_url() ?>serve/show"><button type="button" class="btn btn-danger">Cancel</button></a>
                                    <input  type="submit" value="Update" class="btn btn-success">
                                </div>
                            </div>
                             </form>
                            <br>
                            <div class="row">

                                <div class="col-md-12">
                                   
                                </div>
                            </div>

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
                    <form action="<?=getenv('app_baseURL')?>flash/delete/1" id="form1" method="POST" enctype="multipart/form-data">
                   
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