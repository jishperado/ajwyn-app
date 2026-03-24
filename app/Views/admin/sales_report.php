<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1472px;">
    <section class="content-header">
        <h1>Sales Report <small>Filter by date range</small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('users/user-dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Sales Report</li>
        </ol>
    </section>

    <section class="content">

        <!-- Date Filter -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>
            </div>
            <div class="box-body">
                <form method="GET" action="<?= base_url('users/sales-report') ?>" class="form-inline">
                    <div class="form-group" style="margin-right: 10px;">
                        <label>From: </label>
                        <input type="date" name="from" class="form-control" value="<?= $date_from ?? date('Y-m-01') ?>">
                    </div>
                    <div class="form-group" style="margin-right: 10px;">
                        <label>To: </label>
                        <input type="date" name="to" class="form-control" value="<?= $date_to ?? date('Y-m-d') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Filter</button>
                    <a href="<?= base_url('users/sales-report') ?>" class="btn btn-default" style="margin-left: 5px;"><i class="fa fa-refresh"></i> Reset</a>
                </form>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= $total_orders ?? 0 ?></h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>₹<?= number_format($total_revenue ?? 0, 2) ?></h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="icon"><i class="fa fa-inr"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?= $report_delivered ?? 0 ?></h3>
                        <p>Delivered Orders</p>
                    </div>
                    <div class="icon"><i class="fa fa-check-circle"></i></div>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending</span>
                        <span class="info-box-number"><?= $report_pending ?? 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-truck"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Shipped</span>
                        <span class="info-box-number"><?= $report_shipped ?? 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Delivered</span>
                        <span class="info-box-number"><?= $report_delivered ?? 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cancelled</span>
                        <span class="info-box-number"><?= $report_cancelled ?? 0 ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Order Details (<?= $date_from ?? '' ?> to <?= $date_to ?? '' ?>)</h3>
            </div>
            <div class="box-body">
                <table id="salesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $i => $ord): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><strong>#<?= $ord->order_id ?></strong></td>
                                    <td><?= htmlspecialchars($ord->customer_name ?? '-') ?></td>
                                    <td><?= htmlspecialchars($ord->customer_mobile ?? '-') ?></td>
                                    <td>₹<?= number_format($ord->amount ?? 0, 2) ?></td>
                                    <td><?= date('d M Y', strtotime($ord->created_date)) ?></td>
                                    <td>
                                        <?php
                                            $statusMap = [
                                                'P' => ['Purchased', 'label-warning'],
                                                'S' => ['Shipped', 'label-info'],
                                                'D' => ['Delivered', 'label-success'],
                                                'C' => ['Cancelled', 'label-danger'],
                                                'N' => ['Pending Payment', 'label-default'],
                                            ];
                                            $s = $statusMap[$ord->ord_status] ?? ['Unknown', 'label-default'];
                                        ?>
                                        <span class="label <?= $s[1] ?>"><?= $s[0] ?></span>
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

<script>
$(function() {
    $('#salesTable').DataTable({
        "order": [[5, "desc"]],
        "pageLength": 25
    });
});
</script>
<?= $this->endSection() ?>
