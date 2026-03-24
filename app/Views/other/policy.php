<?= $this->extend('admin/admin_surround') ?>
<?= $this->section('property') ?>

<div class="content-wrapper" style="min-height: 1472px;">
    <section class="content-header">
        <h1>
            Control panel
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $result->head ?></h3>
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
                        <form action="" method="post" enctype="multipart/form-data">
                        

                        
                            <!-- Dynamic Variant Table -->
                          

                            <!-- Category -->
                         

                            <!-- Offers & Description -->
                            <div class="row">
                         

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Content</label>
                                        <textarea class="form-control" id="editor1" name="content" rows="3"><?= old('content', $result->content) ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                  
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

<script src="https://www.mesfrcschool.com/asset/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(document).ready(function(){

  CKEDITOR.replace('editor1', {
filebrowserUploadUrl: "<?= getenv('app_baseURL') ?>users/ck-editor",
filebrowserBrowseUrl: '<?= getenv('app_baseURL') ?>users/ck-browser',
filebrowserUploadMethod: "form"
});
});
</script>

<?= $this->endSection() ?>
