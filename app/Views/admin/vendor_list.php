<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper">

  <section class="content-header">
    <h4>Vendor Management</h4>
  </section>

  <section class="content">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">All Vendors</h3>
      </div>

      <div class="box-body">
        <div class="col-md-12">
          <?php $msg2 = $session->get('success');
          if (!empty($msg2)) { ?>
            <div class="alert alert-success alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Success!</strong> <?= $msg2 ?>
            </div>
          <?php } ?>
          <?php $msg1 = $session->get('error');
          if (!empty($msg1)) { ?>
            <div class="alert alert-warning alert-dismissible aap">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Warning!</strong> <?= $msg1 ?>
            </div>
          <?php } ?>
        </div>
        <br>
        <table class="table table-bordered" width="100%" id="example1">
          <thead>
            <tr>
              <td colspan="8" align="right">
                <a href="<?= base_url('users/vendor-add') ?>">
                  <button class="badge bg-aqua-gradient" type="button" style="border: 0px; font-weight: normal; border-radius: 3px; font-size:14px;">
                    &nbsp;&nbsp;Add New Vendor&nbsp;&nbsp;
                  </button>
                </a>
              </td>
            </tr>
            <tr class="danger">
              <th>Sl.No</th>
              <th>Name</th>
              <th>Username</th>
              <th>Shop Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($vendors)) :
              $sl = 1;
              foreach ($vendors as $vendor) : ?>
                <tr>
                  <td><?= $sl ?></td>
                  <td><?= $vendor->name ?></td>
                  <td><?= $vendor->username ?></td>
                  <td><?= $vendor->shop_name ?? '-' ?></td>
                  <td><?= $vendor->phone ?? '-' ?></td>
                  <td><?= $vendor->email ?? '-' ?></td>
                  <td>
                    <?php if ($vendor->is_active === 'Y'): ?>
                      <span class="label label-success">Active</span>
                    <?php else: ?>
                      <span class="label label-danger">Inactive</span>
                    <?php endif; ?>
                  </td>
                  <td align="center">
                    <a href="<?= base_url('users/vendor-edit/' . $vendor->id) ?>">
                      <span class="badge bg-yellow-gradient" style="border-radius: 3px;">&nbsp;&nbsp;<i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;</span>
                    </a>
                    &nbsp;
                    <a href="<?= base_url('users/vendor-toggle/' . $vendor->id) ?>" onclick="return confirm('Are you sure you want to change this vendor\'s status?');">
                      <?php if ($vendor->is_active === 'Y'): ?>
                        <span class="badge bg-red-gradient" style="border-radius: 3px;" title="Deactivate">&nbsp;&nbsp;<i class="fa fa-ban"></i>&nbsp;&nbsp;</span>
                      <?php else: ?>
                        <span class="badge bg-green-gradient" style="border-radius: 3px;" title="Activate">&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;&nbsp;</span>
                      <?php endif; ?>
                    </a>
                  </td>
                </tr>
            <?php
                $sl++;
              endforeach;
            endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section>
</div>
<?= $this->endSection() ?>
