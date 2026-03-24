<?= $this->extend('admin/admin_surround') ?>


<?= $this->section('property') ?>

<div class="content-wrapper" style="min-height: 1472px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Control panel
                <small>
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
                            <h3 class="box-title">Cancelled List
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
                          
                            <div class="row">

                                <div class="col-md-12">
                            <div class="table-responsive">
    <table class="table table-bordered" width="100%" id="example1">
        <thead>
            <tr class="danger">
                <th>Sl.No</th>
                <th>Order ID</th>
                <th>Customer Details</th>
                <th>Products</th>
                <th>Total</th>
                <th>Tracking ID</th>
                <th>Tracking URL</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $sl = 1; ?>
            <?php if (!empty($groupedOrders)): ?>
                <?php foreach ($groupedOrders as $order): ?>
                    <?php $total = 0; ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><strong>#<?= esc($order['order_id']) ?></strong></td>
                        <td>
                            <strong><?= esc($order['customer_name']) ?></strong><br>
                            <?= esc($order['address']) ?><br>
                            <?= esc($order['city']) ?> - <?= esc($order['pincode']) ?><br>
                            <?= esc($order['landmark']) ?>
                        </td>
                        <td>
                            <?php foreach ($order['items'] as $item): ?>
                                • <?= esc($item->product_name) ?> 
                                (Qty: <?= esc($item->quantity) ?>, ₹<?= esc(number_format($item->price, 2)) ?>)<br>
                                <?php $total += $item->quantity * $item->price; ?>
                            <?php endforeach; ?>
                        </td>
                        <td><strong>₹<?= number_format($total, 2) ?></strong></td>
                        <td><?= esc($order['track_id'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($order['track_url'])): ?>
                                <a href="<?= esc($order['track_url']) ?>" target="_blank">View</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-red">Canceled</span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
             
            <?php endif; ?>
        </tbody>
    </table>
</div>


                                        
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
                    <form action="<?=getenv('app_baseURL')?>flash/delete/1" id="form1" method="POST" enctype="multipart/form-data">
                   
                    <button type="submit"
                            class="btn btn-danger" style="margin-right:0px;">&nbsp;&nbsp;Yes</button>
                     
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
<!-- Ship Modal -->
<div class="modal fade" id="shipModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="shipForm" method="POST" action="">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title"><i class="fa fa-truck"></i> Ship Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Tracking ID (optional)</label>
                        <input type="text" name="tracking_id" class="form-control" placeholder="Enter tracking ID (optional)">
                    </div>
                    <div class="form-group">
                        <label>Tracking URL (optional)</label>
                        <input type="url" name="tracking_url" class="form-control" placeholder="https://example.com/tracking">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


   <script>
$(document).ready(function() {
    $('#example1').DataTable({
        "language": {
            "emptyTable": "No canceled orders available."
        },
        "autoWidth": false
    });

    $("#selectall").click(function() {
        $('.check').not(this).prop('checked', this.checked);
    });

    $(".check").click(function() {
        if (!$(this).prop("checked")) {
            $('#selectall').prop('checked', false);
        }
    });

    $("#deleteconf").click(function() {
        $('#head1').html('Delete?');
        $('#message1').html('Are you sure you want to delete the selected items?');
        $('#deleteModal').modal('show');
    });

    $('.openShipModal').click(function() {
        let orderId = $(this).data('order-id');
        let actionUrl = "<?= base_url('ship/') ?>" + orderId;
        $('#shipForm').attr('action', actionUrl);
        $('#shipModal').modal('show');
    });
});
</script>



<?= $this->endSection() ?>