<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>
<div class="content-wrapper">

  <section class="content-header">
    <h1>
      <small><?= $edit_mode ? 'Edit Vendor' : 'Add New Vendor' ?></small>
    </h1>
  </section>

  <section class="content">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><?= $edit_mode ? 'Edit Vendor' : 'Add New Vendor' ?></h3>
        <div class="box-tools pull-right">
          <a href="<?= base_url('users/vendor-list') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
        </div>
      </div>

      <div class="box-body">
        <div class="col-sm-12">
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

        <form action="" method="post">

          <div class="row">
            <?php if (!$edit_mode): ?>
            <div class="col-sm-4">
              <div class="form-group">
                <label style="font-weight:400;">Username <span style="color:#F00;">*</span></label>
                <input autocomplete="off" type="text" class="form-control" name="username" value="<?= set_value('username') ?>" placeholder="Login username">
                <font size="2" color="red"><?= $errors['username'] ?? '' ?></font>
              </div>
            </div>
            <?php endif; ?>

            <div class="col-sm-4">
              <div class="form-group">
                <label style="font-weight:400;">Password <?= $edit_mode ? '(leave blank to keep current)' : '' ?> <span style="color:#F00;"><?= $edit_mode ? '' : '*' ?></span></label>
                <input autocomplete="off" type="password" class="form-control" name="password" placeholder="<?= $edit_mode ? 'Enter new password to change' : 'Login password' ?>">
                <font size="2" color="red"><?= $errors['password'] ?? '' ?></font>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label style="font-weight:400;">Full Name <span style="color:#F00;">*</span></label>
                <input autocomplete="off" type="text" class="form-control" name="name" value="<?= set_value('name', $vendor->name ?? '') ?>" placeholder="Vendor's full name">
                <font size="2" color="red"><?= $errors['name'] ?? '' ?></font>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label style="font-weight:400;">Shop Name <span style="color:#F00;">*</span></label>
                <input autocomplete="off" type="text" class="form-control" name="shop_name" value="<?= set_value('shop_name', $vendor->shop_name ?? '') ?>" placeholder="Vendor's shop/business name">
                <font size="2" color="red"><?= $errors['shop_name'] ?? '' ?></font>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label style="font-weight:400;">Phone <span style="color:#F00;">*</span></label>
                <input autocomplete="off" type="text" class="form-control" name="phone" value="<?= set_value('phone', $vendor->phone ?? '') ?>" placeholder="10-digit mobile number">
                <font size="2" color="red"><?= $errors['phone'] ?? '' ?></font>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label style="font-weight:400;">Email</label>
                <input autocomplete="off" type="email" class="form-control" name="email" value="<?= set_value('email', $vendor->email ?? '') ?>" placeholder="Vendor's email (optional)">
                <font size="2" color="red"><?= $errors['email'] ?? '' ?></font>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12" style="margin-top: 15px;">
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?= $edit_mode ? 'Update Vendor' : 'Create Vendor' ?></button>
              <a href="<?= base_url('users/vendor-list') ?>" class="btn btn-default">Cancel</a>
            </div>
          </div>

        </form>
      </div>
    </div>

  </section>
</div>
<?= $this->endSection() ?>
