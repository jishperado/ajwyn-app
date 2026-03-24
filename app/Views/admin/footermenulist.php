<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper">

  <section class="content-header">
    <h4>
  Main Menu Management
    </h4>
  </section>


  <section class="content">

  

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Search Result</h3>

      </div>
      <!-- /.box-header -->




      <div class="box-body">
        <div class="col-md-12">
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
        <br>
        <table class="table table-bordered" width="100%" id="example1">
          <thead>
            <tr>
              <td colspan="5" align="right">
              <a href="<?= base_url() ?>users/create-footermenu"><button class="badge bg-aqua-gradient" type="button" style=" border: 0px; font-weight: normal; border-radius: 3px; font-size:14px;" >&nbsp;&nbsp;Add New&nbsp;&nbsp;</button></a>
              </td>

              <td align="right"><button data-toggle="tooltip" title="Delete selected items!" id="deleteconf" class="badge bg-red-gradient" style="margin-right:0px; border-radius: 3px; border: 0px;" type="button">&nbsp;&nbsp;<i class="fa fa-trash-o"></i>&nbsp;&nbsp;</button></td>
                              
            </tr>


            <tr class="danger">
              <th>Sl.No</th>
              <th>Menu Head</th>
              <th>sub Head</th>
              <th>Status</th>
              
             

              <th>View & Edit</th>
              <th>Action<input type="checkbox" id="selectall" style="float:right;" /></th>
            </tr>


          </thead>
          <tbody>
            <?php 
           
            if (!empty($result)) :
              $sl = 1;

              foreach ($result as $val) :
            ?>
                <tr>
                  <td><?= $sl ?></td>
                  <td><?= $val->title?></td>
                  <td><?= $val->head?></td>
                  <td><?= $val->status ?></td>
                 
                  <td align="center"><a href="<?= base_url() ?>users/footermenu-edit/<?=$val->id?>"><span style="border-radius: 3px;" class="badge bg-yellow-gradient">&nbsp;&nbsp;<i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;</span></a></td>
                  <td align="center"><input class="check" name="deleteclii[]" form="form1" value="<?= $val->id ?>" type="checkbox" /></td>
                  </tr> 
                  <?php
                  $sl += 1;
                  endforeach;
                  endif;
                  ?>

          </tbody>
        </table>
        </div>

       
      

      </div>




      <!-- /.box-body -->

    </div>

  </section>
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
                <form action="<?= base_url() ?>users/footermenu-dlt" id="form1" method="POST" enctype="multipart/form-data"><button type="submit" class="btn btn-danger" style="margin-right:0px;">&nbsp;&nbsp;Yes</button>
            
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