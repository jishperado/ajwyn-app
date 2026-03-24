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
                                Photos
                            </h3><br>

    <a href="<?=base_url()?>products" style="float:right" class="btn btn-danger">Back</a>



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
                            <form action="<?php echo base_url(); ?>users/savephotos" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value='<?=$id?>'>
                            <div class="row">
                              
                            
                                <div class="col-md-3">
                                    <label for="">Image (1024px x 1024px)</label><span class="text-danger">*</span>
                                 <input type="file" name="photo[]" class="form-control"  accept="image/*" multiple>
                                   <font size="2" color="red"><?= $err['photo'] ?? '' ?> </font>
                                    
                                </div>
                                <div class="col-md-12" align="center">
                                   
                                    <input style="margin-top: 25px" type="submit" value="Add" class="btn btn-success">
                                </div>
                            </div>
                             </form>
                            <br>
                            <div class="row">

                                <div class="col-md-12">
                                    <div id="example1_wrapper"
                                        class="dataTables_wrapper form-inline dt-bootstrap no-footer table-responsive">

                                        <table class="table table-bordered" width="100%" id="example1">
          <thead>
            <tr>
              <td colspan="2" align="right">
             
              </td>

              <td align="right"><button data-toggle="tooltip" title="Delete selected items!" id="deleteconf" class="badge bg-red-gradient" style="margin-right:0px; border-radius: 3px; border: 0px;" type="button">&nbsp;&nbsp;<i class="fa fa-trash-o"></i>&nbsp;&nbsp;</button></td>
                              
            </tr>


            <tr class="danger">
              <th>Image</th>
              <th>Image small</th>
              <th>Action<input type="checkbox" id="selectall" style="float:right;" /></th>
            </tr>


          </thead>
          <tbody>
            <?php
            if(!empty($result))
            {
                foreach ($result as $key => $val) {
                   
                    ?>
                    <tr>
                
                  <td><img width="400" src="<?= base_url() ?>uploads/products/<?=$val->product_image?>" alt="" srcset=""></td>
                  <td><img src="<?= base_url() ?>uploads/products/<?=$val->product_image_s?>" alt="" srcset=""></td>
                   <td align="center"><input class="check" name="deleteclii[]" form="form1" value="<?= $val->id ?>" type="checkbox" /></td>
                </tr> 
                    <?php
                    # code...
                }
            }
            ?>

          </tbody>
        </table>
                                        
                                    </div>
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
                    <form action="<?= base_url() ?>users/proimgdelete" id="form1" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value='<?=$id?>'>
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