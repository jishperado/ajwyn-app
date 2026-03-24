<?= $this->extend('admin/admin_surround') ?>


<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1121px;">


    <section class="content-header">
        <h4>
            Profile Update
        </h4>

    </section>


    <section class="content">
    <div class="row">
                    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header">








                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>





            <div class="box-body">
                <div class="row">
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

                </div>

            </div>



            <!-- /.box-header -->
           



            <div class="box-body ">

                <form action="" method="post">
                    <form action="" method="post" name="wakin" id="wakin">
                        <div class="form-group has-feedback">
                            <label for="">Name</label>
                            <input type="text" autocomplete="off" name="name" class="form-control" placeholder="Full Name" value="<?= $userdetails[0]->name ?>">

                            <font size="2" color="red"><?= $errors['name'] ?? '' ?> </font>
                        </div>
                       
                        <div class="form-group has-feedback">
                            <label for="">User Name</label>
                            <input type="text" autocomplete="off" name="username" class="form-control" placeholder="Username" value="<?= $userdetails[0]->username ?>">

                            <font size="2" color="red"><?= $errors['username'] ?? '' ?> </font>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="">Password</label>
                            <input type="text" autocomplete="off" name="pwd" class="form-control" placeholder="Password" value="<?= $om->decrypt($userdetails[0]->password) ?>">

                            <font size="2" color="red"><?= $errors['pwd'] ?? '' ?> </font>
                        </div>

                        

                        <div class="form-group has-feedback">
                        <button type="submit" class="btn btn-primary">UPDATE DATA</button>
                        </div>

            </div>
            </form>

            </form>


        </div>
                        </div></div>



        <!-- /.box-body -->

</div>

</section>



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
        get_div();
        $("#class").change(function() {
            get_div();

        });
        $("#division").change(function() {
            redircet();

        });

        function redircet() {
            var cls = $("#division").val();
            if (cls)
                window.location = '<?= getenv('appurl') ?>student/search?div=' + cls;
        }

        function get_div() {
            var cls = $("#class").val();
            var postdiv = "<?= $post['division'] ?? '' ?>";
            if (cls) {
                $.ajax({
                    type: 'POST',
                    url: '<?= getenv('appurl') ?>ajaxcon/getdivisionbyclass',
                    data: {
                        cls: cls
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#division").html("<option   value=''> Select</option>");

                        for (var i = 0; i < data.length; i++) {
                            if (postdiv == data[i]['d_id'])
                                $("#division").append("<option selected  value='" + data[i]['d_id'] + "'>" + data[i]['division'] + "</option>");
                            else
                                $("#division").append("<option   value='" + data[i]['d_id'] + "'>" + data[i]['division'] + "</option>");

                        }

                    }
                });
            }
        }

    });
</script>
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
                <form action="<?= getenv('appurl') ?>student/dlt" id="form1" method="POST" enctype="multipart/form-data"><button type="submit" class="btn btn-danger" style="margin-right:0px;">&nbsp;&nbsp;Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </form>

            </div>
        </div>

    </div>
</div>
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