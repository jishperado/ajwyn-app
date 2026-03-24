<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Product Management <small>Create Product</small></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New Product</h3>
                    </div>
                    <div class="box-body">
                        <!-- Success Message -->
                        <?php if ($session->get('success')): ?>
                            <div class="alert alert-success alert-dismissible aap">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Success!</strong> <?= $session->get('success') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Error Message -->
                        <?php if ($session->get('error')): ?>
                            <div class="alert alert-warning alert-dismissible aap">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Warning!</strong> <?= $session->get('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form Start -->
                        <form action="<?= base_url('products') ?>" method="post" enctype="multipart/form-data">
                            <div class="row">

                                <!-- Product Type -->


                                <!-- Product Name -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Name:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" value="<?= set_value('product_name') ?>" placeholder="Enter product name">
                                        <font size="2" color="red"><?= $errors['product_name'] ?? '' ?></font>
                                    </div>
                                </div>
                                          <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Image:</label>
                                        <input type="file" class="form-control" name="photo">
                                        <font size="2" color="red"><?= $errors['photo'] ?? '' ?></font>
                                    </div>
                                </div>
                                <div class="col-md-4">
    <div class="form-group">
        <label>Product Type:</label>
        <select name="product_type" id="product_type" class="form-control" >
            <option value="">Select Type</option>
            <?php if (!empty($type)): ?>
                <?php foreach ($type as $val): ?>
                    <option value="<?= $val->id ?>" <?= set_select('product_type', $val->id) ?>>
                        <?= $val->product_type ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <font size="2" color="red"><?= $errors['product_type'] ?? '' ?></font>
    </div>
</div>
</div>

                            <div class="row">
                                <!-- Vendor Assignment (Admin only) -->
                                <?php if (($user_role ?? 'admin') === 'admin' && !empty($vendors)): ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Assign to Vendor:</label>
                                        <select class="form-control" name="vendor_id">
                                            <option value="">Admin (Self)</option>
                                            <?php foreach ($vendors as $v): ?>
                                                <option value="<?= $v->id ?>" <?= set_select('vendor_id', $v->id) ?>>
                                                    <?= $v->shop_name ?? $v->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Is Active -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Is Active:<span class="text-danger">*</span></label>
                                        <select class="form-control" name="is_active">
                                            <option <?= set_select("is_active", "Y") ?> value="Y">Yes</option>
                                            <option <?= set_select("is_active", "N") ?> value="N">No</option>
                                        </select>
                                        <font size="2" color="red"><?= $errors['is_active'] ?? '' ?></font>
                                    </div>
                                </div>
                                           <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Highlight:</label>
                                        <select class="form-control" name="category_status">
                                            <option value="">Select</option>
                                            <?php foreach ($status as $sts): ?>
                                                <option <?= set_select('category_status', $sts->id) ?> value="<?= $sts->id ?>"><?= $sts->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <font size="2" color="red"><?= $errors['category_status'] ?? '' ?></font>
                                    </div>
                                </div>
                            </div>

                            <!-- Category & Image -->
                            <div class="row">
                     

                      
                                <div class="col-md-3">
                                    <label>Tax (%):</label>
                                    <input type="number" name="tax" class="form-control" value="<?= set_value('tax', 0) ?>">
                                    <font size="2" color="red"><?= $errors['tax'] ?? '' ?></font>
                                </div>
                                
                                <div class="col-md-3">
                                    <label>Shipping (Inside Kerela)<span class="text-danger">*</span></label>
                                    <input type="number" name="shipping" class="form-control" value="<?= set_value('shipping', 0) ?>">
                                    <font size="2" color="red"><?= $errors['shipping'] ?? '' ?></font>
                                </div>

                                <div class="col-md-3">
                                    <label>Shipping (Outside Kerela)<span class="text-danger">*</span></label>
                                    <input type="number" name="shipping_outside" class="form-control" value="<?= set_value('shipping_outside', 0) ?>">
                                    <font size="2" color="red"><?= $errors['shipping_outside'] ?? '' ?></font>
                                </div>

                            </div>
                    

                            <!-- Dynamic Fields (based on Product Type) -->
                          <div class="row" style="margin-top:15px;">
    <div class="col-md-12">
        <label>Product Variants</label>
        <table class="table table-bordered" id="productVariants">
            <thead>
                <tr>
                    <th>Variant Name</th>
                    <th>Price</th>
                    <th>Offer (%)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button type="button" id="addVariant" class="btn btn-primary">Add Variant</button>
        <font size="2" color="red"><?= $errors['variant'] ?? '' ?></font>
    </div>
</div>


                            <!-- Category, Order, Offers, Description -->
                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-6">
                                    <label>Category</label>
                                    <select class="form-control select2" multiple name="cate[]" >
                                        <option value="">Select</option>
                                        <?php foreach ($mainmenu as $val): ?>
                                            <option <?= set_select("cate", $val->id) ?> value="<?= $val->id ?>"><?= $val->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <font size="2" color="red"><?= $errors['cate'] ?? '' ?></font>
                                </div>

                                <div class="col-md-3">
                                    <label>Order</label>
                                    <input type="number" name="order" class="form-control" value="<?= set_value('order', ($orderlist[0]->cnt ?? 0) + 1) ?>">
                                    <font size="2" color="red"><?= $errors['order'] ?? '' ?></font>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Offers:</label>
                                        <textarea class="form-control" id="editor1" name="offers" rows="3"><?= set_value('offers') ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Product Description:</label>
                                        <textarea class="form-control" id="editor2" name="description" rows="3"><?= set_value('description') ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <a href="<?= base_url('products') ?>">
                                        <input style="margin-top:25px" type="button" value="Back" class="btn btn-danger">
                                    </a>
                                    <input style="margin-top:25px;float:right" type="submit" value="Create" class="btn btn-success">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


<script>
$(document).ready(function () {

    // Add Variant Row
    $(document).on("click", "#addVariant", function () {
        let row = `<tr>
            <td><input type="text" name="variant[]" class="form-control" required></td>
            <td><input type="number" name="price[]" step="0.01" class="form-control" required></td>
            <td><input type="number" name="offerPerPrice[]" step="0.01" class="form-control"></td>
            <td><button type="button" class="btn btn-danger deleteRow">Remove</button></td>
        </tr>`;
        $("#productVariants tbody").append(row);
    });

    // Delete Row
    $(document).on("click", ".deleteRow", function () {
        $(this).closest("tr").remove();
    });

    // Restore Old Submitted Data (on validation failure)
    <?php if(old('variant')): ?>
        let oldVariant = <?= json_encode(old('variant')) ?>;
        let oldPrice = <?= json_encode(old('price')) ?>;
        let oldOffer = <?= json_encode(old('offerPerPrice')) ?>;

        for (let i = 0; i < oldVariant.length; i++) {
            let row = `<tr>
                <td><input type="text" name="variant[]" class="form-control" value="${oldVariant[i] ?? ''}" required></td>
                <td><input type="number" name="price[]" class="form-control" step="0.01" value="${oldPrice[i] ?? ''}" required></td>
                <td><input type="number" name="offerPerPrice[]" class="form-control" step="0.01" value="${oldOffer[i] ?? ''}"></td>
                <td><button type="button" class="btn btn-danger deleteRow">Remove</button></td>
            </tr>`;
            $("#productVariants tbody").append(row);
        }
    <?php endif; ?>

    // If no old inputs (initial load) -> start with one empty row
    <?php if(!old('variant')): ?>
        $("#addVariant").trigger("click");
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
