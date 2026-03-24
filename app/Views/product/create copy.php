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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Type:<span class="text-danger">*</span></label>
                                         <select name="product_type" id="product_type" class="form-control" required>
                                             <option value="">Select Type</option>
                                           <option <?= set_select('product_type', 'cake') ?> value="cake">Cake</option>
                                           <option <?= set_select('product_type', 'cream') ?> value="cream">Cream</option>
                                           <option <?= set_select('product_type', 'oil') ?> value="oil">Oil</option>
                                        </select>
                             
                                        <font size="2" color="red"><?= $errors['product_type'] ?? '' ?></font>
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Name:<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="product_name" value="<?= set_value('product_name') ?>" placeholder="Enter product name">
                                        <font size="2" color="red"><?= $errors['product_name'] ?? '' ?></font>
                                    </div>
                                </div>

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
                            </div>

                            <!-- Category & Image -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Category Status:</label>
                                        <select class="form-control" name="category_status">
                                            <option value="">Select</option>
                                            <?php foreach ($status as $sts): ?>
                                                <option <?= set_select('category_status', $sts->id) ?> value="<?= $sts->id ?>"><?= $sts->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <font size="2" color="red"><?= $errors['category_status'] ?? '' ?></font>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Image:</label>
                                        <input type="file" class="form-control" name="photo">
                                        <font size="2" color="red"><?= $errors['photo'] ?? '' ?></font>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Tax (%):</label>
                                    <input type="number" name="tax" class="form-control" value="<?= set_value('tax', 0) ?>">
                                    <font size="2" color="red"><?= $errors['tax'] ?? '' ?></font>
                                </div>

                            </div>
                            
                            <div class="row" style="margin-bottom:15px;">

                                <div class="col-md-3">
                                    <label>Shipping (Inside Kerela) :</label>
                                    <input type="number" name="shipping" class="form-control" value="<?= set_value('shipping', 0) ?>">
                                    <font size="2" color="red"><?= $errors['shipping'] ?? '' ?></font>
                                </div>

                                <div class="col-md-3">
                                    <label>Shipping (Outside Kerela) :</label>
                                    <input type="number" name="shipping_outside" class="form-control" value="<?= set_value('shipping_outside', 0) ?>">
                                    <font size="2" color="red"><?= $errors['shipping_outside'] ?? '' ?></font>
                                </div>

                            </div>

                            <!-- Dynamic Fields (based on Product Type) -->
                            <div id="dynamicFields"></div>

                            <!-- Category, Order, Offers, Description -->
                            <div class="row">
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
$(document).ready(function() {
  function loadVariantFields(type, callback = null) {
    if (type) {
        $.ajax({
            url: "<?= base_url('products/getFieldsByType') ?>",
            type: "POST",
            data: { 
                type: type, 
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>' 
            },
            success: function(html) {
                $('#dynamicFields').html(html);
                if (callback) setTimeout(callback, 100);
            }
        });
    } else {
        $('#dynamicFields').html('');
    }
}

    $('#product_type').change(function() {
        loadVariantFields($(this).val());
    });

    const selectedType = "<?= old('product_type') ?>";
    if (selectedType) {
        loadVariantFields(selectedType, function() {
            restoreOldVariants(selectedType);
        });
    }

    function restoreOldVariants(type) {
        const oldVariant = <?= json_encode(old('variant') ?? []) ?>;
        const oldPrice = <?= json_encode(old('price') ?? []) ?>;
        const oldOfferPerPrice = <?= json_encode(old('offerPerPrice') ?? []) ?>;
        const oldEgglessPrice = <?= json_encode(old('egglessPrice') ?? []) ?>;
        const oldOfferPerEggless = <?= json_encode(old('offerPerEggless') ?? []) ?>;
        if (oldVariant.length > 0) {
            const $table = $('#productVariants tbody');
            $table.empty();

            for (let i = 0; i < oldVariant.length; i++) {
                let row = '<tr>';

               
                row += `<td><input type="text" name="variant[]" class="form-control" value="${oldVariant[i] ?? ''}" required></td>`;
                row += `<td><input type="number" name="price[]" class="form-control" value="${oldPrice[i] ?? ''}" step="0.01" required></td>`;
                row += `<td><input type="number" name="offerPerPrice[]" class="form-control" value="${oldOfferPerPrice[i] ?? ''}" step="0.01"></td>`;

              
                if (type === 'cake') {
                    row += `<td><input type="number" name="egglessPrice[]" class="form-control" value="${oldEgglessPrice[i] ?? ''}" step="0.01"></td>`;
                    row += `<td><input type="number" name="offerPerEggless[]" class="form-control" value="${oldOfferPerEggless[i] ?? ''}" step="0.01"></td>`;
                }

                row += `<td><button class="btn btn-danger deleteRow">Remove</button></td>`;
                row += '</tr>';

                $table.append(row);
            }
        }
    }
});

</script>

<?= $this->endSection() ?>
