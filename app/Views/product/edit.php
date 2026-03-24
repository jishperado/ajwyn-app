<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>

<div class="content-wrapper" style="min-height: 1472px;">
    <section class="content-header">
        <h1>
            Control panel
            <small>Edit Product</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Product</h3>
                    </div>
                    <div class="box-body">
                        <!-- Success / Error messages -->
                        <?php if ($msg = $session->get('success')): ?>
                            <div class="alert alert-success alert-dismissible aap">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Success!</strong> <?= $msg ?>
                            </div>
                        <?php elseif ($msg = $session->get('error')): ?>
                            <div class="alert alert-warning alert-dismissible aap">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Warning!</strong> <?= $msg ?>
                            </div>
                        <?php endif; ?>
                        <!-- Edit Form -->
                        <form action="<?= base_url('products/update/' . $result->id) ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Product Name -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Name:<span class='text-danger'>*</span></label>
                                        <input type="text" class="form-control" name="product_name"
                                            value="<?= old('product_name', $result->product_name) ?>"
                                            placeholder="Enter product name">
                                        <font size="2" color="red"><?= $errors['product_name'] ?? '' ?></font>
                                    </div>
                                </div>
                                    <!-- Product Image -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Image:</label>
                                        <input type="file" class="form-control" name="photo">
                                        <?php if (!empty($result->img)): ?>
                                            <img src="<?= base_url() . 'uploads/products/' . $result->img ?>" width="100" style="margin-top:10px;">
                                        <?php endif; ?>
                                        <font size="2" color="red"><?= $errors['photo'] ?? '' ?></font>
                                    </div>
                                </div>
                                <!-- Product Type -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Type:</label>
                               <select name="product_type" id="product_type" class="form-control" >
    <option value="">Select Type</option>
    <?php if (!empty($type)): ?>
        <?php foreach ($type as $pt): ?>
            <option value="<?= $pt->id ?>" <?= set_select('product_type', $pt->id, $result->product_type == $pt->id) ?>>
                <?= $pt->product_type ?>
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
                                                <option value="<?= $v->id ?>"
                                                    <?= (isset($result->vendor_id) && $result->vendor_id == $v->id) ? 'selected' : '' ?>>
                                                    <?= $v->shop_name ?? $v->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Active Status -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Is Active:<span class='text-danger'>*</span></label>
                                        <select class="form-control" name="is_active">
                                            <option <?= set_select("is_active", "Y", $result->is_active == 'Y') ?> value="Y">Yes</option>
                                            <option <?= set_select("is_active", "N", $result->is_active == 'N') ?> value="N">No</option>
                                        </select>
                                        <font size="2" color="red"><?= $errors['is_active'] ?? '' ?></font>
                                    </div>
                                </div>
                                <!-- Category Status -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Highlight:</label>
                                        <select class="form-control" name="category_status">
                                            <option value="">Select</option>
                                            <?php foreach ($status as $sts): ?>
                                                <option value="<?= $sts->id ?>"
                                                    <?= set_select("category_status", $sts->id, $result->categorystatus == $sts->id) ?>>
                                                    <?= $sts->name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                            
                          
                                <!-- Tax -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tax %:</label>
                                        <input type="text" class="form-control" name="tax" value="<?= !empty(set_value('tax')) ? set_value('tax') : $result->tax ?>">
                                        <font size="2" color="red"><?= $errors['tax'] ?? '' ?></font>
                                    </div>
                                </div>
                                <!-- Shipping Cost -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping Cost(Inside Kerela)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shipping" value="<?= !empty(set_value('shipping')) ? set_value('shipping') : $result->shipping ?>">
                                        <font size="2" color="red"><?= $errors['shipping'] ?? '' ?></font>
                                    </div>
                                </div>
                                                      <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping Cost(Outside Kerela)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shipping_outside" value="<?= !empty(set_value('shipping_outside')) ? set_value('shipping_outside') : $result->shipping_outside ?>">
                                        <font size="2" color="red"><?= $errors['shipping_outside'] ?? '' ?></font>
                                    </div>
                                </div>
                                  </div>
                            <!-- Dynamic Variant Table -->
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
<tbody>
<?php
$variants = old('variant') ?? $variantItem ?? [];
if (!empty($veriantitem)):
    foreach ($veriantitem as $key => $val):
        $isObj = is_object($val);
?>
    <tr>
        <td>
            <input type="text" name="variant[]" class="form-control"
                value="<?= $isObj ? $val->veriant : $val ?>" >
        </td>

        <td>
            <input type="number" name="price[]" class="form-control" step="0.01"
                value="<?= $isObj ? $val->price : ($price[$key] ?? '') ?>" >
        </td>

        <td>
            <input type="number" name="offerPerPrice[]" class="form-control" step="0.01"
                value="<?= $isObj ? $val->if_offer_per_price : ($offerPerPrice[$key] ?? '') ?>">
        </td>

        <td>
            <button type="button" class="btn btn-danger deleteRow">Remove</button>
        </td>
    </tr>
<?php endforeach; endif; ?>
</tbody>

        </table>
        <button type="button" id="addVariant" class="btn btn-primary">Add Variant</button>
        <font size="2" color="red"><?= $errors['variant'] ?? '' ?></font>
    </div>
</div>


                            <!-- Category -->
                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-6">
                                    <label>Category</label>
                                    <select class="form-control select2" multiple name="cate[]">
                                        <option value="">Select</option>
                                        <?php foreach ($mainmenu as $val): ?>
                                            <option value="<?= $val->id ?>"
                                                <?= !empty($category) && in_array($val->id, array_column($category, "category_id")) ? 'selected' : '' ?>>
                                                <?= $val->name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Order -->
                                <div class="col-md-3">
                                    <label>Order</label>
                                    <input type="number" name="order" class="form-control"
                                        value="<?= old('order', $result->orderlist) ?>">
                                </div>
                            </div>

                            <!-- Offers & Description -->
                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Offers:</label>
                                        <textarea class="form-control" id="editor1" name="offers" rows="3"><?= old('offers', $result->offers) ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Product Description:</label>
                                        <textarea class="form-control" id="editor2" name="description" rows="3"><?= old('description', $result->description) ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <a href="<?= base_url('products') ?>" class="btn btn-danger" style="margin-top:25px;">Back</a>
                                    <input style="margin-top:25px;float:right" type="submit" value="Update" class="btn btn-success">
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
            <td><input type="text" name="variant[]" class="form-control" ></td>
            <td><input type="number" name="price[]" step="0.01" class="form-control" ></td>
            <td><input type="number" name="offerPerPrice[]" step="0.01" class="form-control"></td>
            <td><button type="button" class="btn btn-danger deleteRow">Remove</button></td>
        </tr>`;
        $("#productVariants tbody").append(row);
    });

    // Delete Variant Row
    $(document).on("click", ".deleteRow", function () {
        $(this).closest("tr").remove();
    });

<?php if (empty(old('variant')) && empty($veriantitem)): ?>
    $("#addVariant").trigger("click");
<?php endif; ?>

});
</script>



<?= $this->endSection() ?>
