<?= $this->extend('admin/admin_surround') ?>


<?= $this->section('property') ?>
<div class="content-wrapper" style="min-height: 1472px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Control panel
                <small>
                    Product Q&A
                </small>
            </h1>
         
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Product Q&A
                            </h3><br>
                              <a href="<?= base_url('products') ?>" style="float:right">  <button type="button" class="btn btn-info">Go Back</button></a>




                        </div><!-- /.box-header -->
                        <div class="box-body">

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
                            <form action="" method="post">

    <div class="qa-wrapper">

     <?php if (!empty($qa)): ?>
    <?php foreach ($qa as $index => $item): ?>
        <div class="qa-item row" style="margin-bottom:15px;">
            <div class="col-md-5">
                <label>Question</label>
                <textarea name="question[]" class="form-control" rows="3" required><?= esc($item->question) ?></textarea>
            </div>

            <div class="col-md-5">
                <label>Answer</label>
                <textarea name="answer[]" class="form-control" rows="3" required><?= esc($item->answer) ?></textarea>
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>

                <?php if ($index == count($qa) - 1): ?>
                    <!-- LAST ROW → Show Add button -->
                    <button type="button" class="btn btn-success add-row" style="width:100%;">Add</button>
                <?php endif; ?>

                <!-- Show remove on all rows -->
                <button type="button" class="btn btn-danger remove-row" style="width:100%; margin-top:5px;">Remove</button>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

        <!-- Empty row for adding new Q&A if none exist -->
        <?php if (empty($qa)): ?>
            <div class="qa-item row" style="margin-bottom:15px;">
                <div class="col-md-5">
                    <label>Question</label>
                    <textarea name="question[]" class="form-control" rows="3" placeholder="Enter question" required></textarea>
                </div>

                <div class="col-md-5">
                    <label>Answer</label>
                    <textarea name="answer[]" class="form-control" rows="3" placeholder="Enter answer" required></textarea>
                </div>

                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-success add-row" style="width:100%;">Add</button>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <button type="submit" class="btn btn-primary">Save Q&A</button>

</form>


                                    
                    </div><!-- /.col -->
                    <!-- /.col -->
                </div><!-- /.row -->

        </section><!-- /.content -->
    </div>
    <script>
$(document).ready(function () {

    // Add new row with textarea fields
    $(document).on("click", ".add-row", function () {
        let html = `
        <div class="qa-item row" style="margin-bottom:15px;">
            <div class="col-md-5">
                <textarea name="question[]" class="form-control" rows="3" placeholder="Enter question" ></textarea>
            </div>

            <div class="col-md-5">
                <textarea name="answer[]" class="form-control" rows="3" placeholder="Enter answer" ></textarea>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-row" style="width:100%;">Remove</button>
            </div>
        </div>
        `;

        $('.qa-wrapper').append(html);
    });

    // Remove row
    $(document).on("click", ".remove-row", function () {
        $(this).closest(".qa-item").remove();
    });

});
</script>

   
<?= $this->endSection() ?>