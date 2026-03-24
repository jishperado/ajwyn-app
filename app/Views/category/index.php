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
                            <h3 class="box-title"><?=$content->title?>
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
                            <form action="<?= base_url('category') ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value='<?=$id?>'>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Title</label><span class="text-danger"></span>
                                    <input type="text" name="title" class="form-control" value="<?=set_value("title")?>">
                                    <font size="2" color="red"><?= $err['title'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Notes</label><span class="text-danger"></span>
                                    <Textarea name="notes" class="form-control"><?=set_value("notes")?></Textarea>
                                    <font size="2" color="red"><?= $err['notes'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Url</label><span class="text-danger">*</span>
                                    <input type="text" name="url" class="form-control" value="<?=set_value("url")?>">
                                    <font size="2" color="red"><?= $err['url'] ?? '' ?> </font>
                    
                                </div>
                                <div class="col-md-3">
                                    <label for="">Image</label><span class="text-danger">*</span>
                                 <input type="file" name="photo" class="form-control">
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
              <td colspan="6" align="right">
             
              </td>

              <td align="right"><button data-toggle="tooltip" title="Delete selected items!" id="deleteconf" class="badge bg-red-gradient" style="margin-right:0px; border-radius: 3px; border: 0px;" type="button">&nbsp;&nbsp;<i class="fa fa-trash-o"></i>&nbsp;&nbsp;</button></td>
                              
            </tr>


            <tr class="danger">
              <th>Sl.No</th>
              <th>Title</th>
              <th>Message</th>
              <th>Url</th>
              <th>Image</th>
              <th>View & Edit</th>
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
                  <td><?= $key+1 ?></td>
                  <td><?= $val->title ?></td>
                  <td><?= $val->description ?></td>
                  <td><?= $val->url ?></td>
                
                  <td><img src="<?= base_url()?>uploads/cate/<?= $val->image ?>" width='100'></td>
                    <td align="center"><a href="<?= base_url('category/edit/'.$val->id) ?>"><span style="border-radius: 3px;" class="badge bg-yellow-gradient">&nbsp;&nbsp;<i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;</span></a></td>
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
                    <form action="<?=getenv('app_baseURL')?>cat-dlt" id="form1" method="POST" enctype="multipart/form-data">
                   
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