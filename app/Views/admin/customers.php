<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1472px;">
    <section class="content-header">
        <h1>Customers <small>Manage registered customers</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('users/user-dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Customers</li>
        </ol>
    </section>

    <section class="content">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-times"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= count($customers ?? []) ?></h3>
                        <p>Total Customers</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?= count(array_filter($customers ?? [], fn($c) => !empty($c->email))) ?></h3>
                        <p>With Email</p>
                    </div>
                    <div class="icon"><i class="fa fa-envelope"></i></div>
                </div>
            </div>
        </div>

        <!-- Customer List -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-address-book"></i> Registered Customers</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-sm btn-info" onclick="openEmailModal('selected')">
                        <i class="fa fa-envelope"></i> Email Selected
                    </button>
                    <button type="button" class="btn btn-sm btn-success" onclick="openEmailModal('all')">
                        <i class="fa fa-send"></i> Email All
                    </button>
                </div>
            </div>
            <div class="box-body">
                <table id="customerTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:30px"><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Registered</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $i => $cus): ?>
                                <tr>
                                    <td><input type="checkbox" class="customer-check" value="<?= $cus->id ?>"></td>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($cus->name ?? '') ?></td>
                                    <td><?= htmlspecialchars($cus->email ?? '') ?></td>
                                    <td><?= htmlspecialchars($cus->mobile ?? '') ?></td>
                                    <td><?= !empty($cus->created_at) ? date('d M Y', strtotime($cus->created_at)) : '-' ?></td>
                                    <td>
                                        <?php if (($cus->is_active ?? 'Y') === 'Y'): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= base_url('users/send-promo-email') ?>" method="POST" id="promoForm">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-envelope"></i> Send Promotional Email</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="send_to" id="sendToField" value="all">
                    <div id="selectedIdsContainer"></div>
                    <div class="alert alert-info" id="recipientInfo"></div>
                    <div class="form-group">
                        <label>Subject <span class="text-red">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="Email subject..." required>
                    </div>
                    <div class="form-group">
                        <label>Message <span class="text-red">*</span></label>
                        <textarea name="message" class="form-control" rows="8" placeholder="Type your promotional message here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function() {
    $('#customerTable').DataTable({
        "order": [[1, "asc"]],
        "pageLength": 25
    });

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.customer-check').prop('checked', this.checked);
    });
});

function openEmailModal(type) {
    var $modal = $('#emailModal');
    var $sendTo = $('#sendToField');
    var $container = $('#selectedIdsContainer');
    var $info = $('#recipientInfo');

    $container.html('');

    if (type === 'all') {
        $sendTo.val('all');
        var total = <?= count(array_filter($customers ?? [], fn($c) => !empty($c->email))) ?>;
        $info.html('<i class="fa fa-info-circle"></i> Email will be sent to <strong>all ' + total + ' customers</strong> with email addresses.');
    } else {
        $sendTo.val('selected');
        var checked = $('.customer-check:checked');
        if (checked.length === 0) {
            alert('Please select at least one customer!');
            return;
        }
        checked.each(function() {
            $container.append('<input type="hidden" name="customer_ids[]" value="' + $(this).val() + '">');
        });
        $info.html('<i class="fa fa-info-circle"></i> Email will be sent to <strong>' + checked.length + ' selected customer(s)</strong>.');
    }

    $modal.modal('show');
}
</script>
<?= $this->endSection() ?>
