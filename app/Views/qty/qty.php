<?= $this->extend('admin/admin_surround') ?>

<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1472px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Control panel
            <small>Product</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product</h3>
                        <br>
                        <a href="<?= base_url() ?>products/new" style="float:right">
                            <button type="button" class="btn btn-info">Create new product</button>
                        </a>
                    </div>

                    <div class="box-body">
                        <?php
                        $msg2 = $session->get('success');
                        if (!empty($msg2)) { ?>
                            <div class="alert alert-success alert-dismissible aap">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Success!</strong> <?= $msg2 ?>
                            </div>
                        <?php } ?>

                        <?php
                        $msg1 = $session->get('error');
                        if (!empty($msg1)) { ?>
                            <div class="alert alert-warning alert-dismissible aap">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Warning!</strong> <?= $msg1 ?>
                            </div>
                        <?php } ?>

                        <!-- form start -->
                        <form method="post" action="<?= base_url('QtyController/update_quantity') ?>" id="update-qty-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="example1_wrapper"
                                        class="dataTables_wrapper form-inline dt-bootstrap no-footer table-responsive">

                                        <table class="table table-bordered" width="100%" id="example1">
                                            <thead>
                                                <tr>
                                                    <td colspan="6" align="right">
                                                        <button type="submit" class="btn btn-primary" style="float:right; margin-bottom:6px;">Update quantities</button>
                                                    </td>
                                                </tr>

                                                <tr class="danger">
                                                    <th>Sl.No</th>
                                                    <th>Product</th>
                                                    <th>Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($result)) {
                                                    foreach ($result as $key => $val) { ?>
                                                        <tr>
                                                            <td><?= $key + 1 ?></td>
                                                            <td><?= esc($val->product_name) ?> (<?= esc($val->veriant) ?>)</td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <!-- Variant ID -->
                                                                    <input type="hidden" name="variant_id[]" value="<?= (int)$val->variant_id ?>">

                                                                    <!-- Quantity -->
                                                                    <input
                                                                        type="number"
                                                                        name="quantity[]"
                                                                        value="<?= (int)$val->qty ?>"
                                                                        min="0"
                                                                        class="form-control qty-input"
                                                                        style="max-width:120px;"
                                                                        required
                                                                    />

                                                                    <span class="input-group-btn" style="padding-left:6px">
                                                                        <button type="button" class="btn btn-default quick-update">Save</button>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section><!-- /.content -->

    <script>
        // Optional: quick-update button will submit the form immediately
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.quick-update').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('update-qty-form').submit();
                });
            });
        });
    </script>
</div>

<!-- Delete Modal -->
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
                <form action="<?= getenv('app_baseURL') ?>users/productdelete" id="form1" method="POST" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-danger" style="margin-right:0px;">&nbsp;&nbsp;Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#selectall").click(function () {
            $('.check').not(this).prop('checked', this.checked);
            $('.check').attr('checked', this.checked);
        });
        $(".check").click(function () {
            if ($(this).prop("checked") == false) {
                $('#selectall').prop('checked', false);
            }
        });
        $("#selectalltomsg").click(function () {
            $('.checkmsg').not(this).prop('checked', this.checked);
            $('.checkmsg').attr('checked', this.checked);
        });
        $(".checkmsg").click(function () {
            if ($(this).prop("checked") == false) {
                $('#selectalltomsg').prop('checked', false);
            }
        });

        $("#deleteconf").click(function () {
            $('#head1').html('Delete?');
            $('#message1').html('Are you sure you want to delete the selected items?');
            $('#deleteModal').modal('show');
        });
    });
</script>

<?= $this->endSection() ?>
