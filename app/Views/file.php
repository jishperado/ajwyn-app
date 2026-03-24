<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <ul class="list-group">
                    <div id="fileExplorer">
                        <?php foreach ($fileList as $key => $fileName) { ?>
                        <li class="list-group-item">

                            <div class="thumbnail">




                                <?php $ext = substr($fileName, strrpos($fileName, '.', -1), strlen($fileName));
                                if ($ext == ".html" || $ext == ".php"){
                                    continue;
                                }
									//echo "$ext";
									if ($ext == ".pdf") {
									?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="<?php echo getenv('appurl')?>assets/pdf.png" width="50"><br />
                                        <?php $fileName; ?>
                                    </div>


                                </div>



                                <?php
									} else {
									?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="<?php echo getenv('appurl') . $fileName; ?>" width="150"> <br />
                                        <?php echo getenv('appurl') . $fileName; ?>
                                    </div>


                                </div>

                                <?php
									}
									?>
                                <div class="row">

                                    <div class="col-md-12" align="right">
                                        <button title="<?php echo getenv('appurl') . $fileName; ?>" class="btn btn-success btn-sm">Select This
                                            File</button>
                                        <input type="hidden" id="path<?= $key + 1 ?>" value="<?= $fileName ?>">
                                        <a  data-path="<?= $fileName ?>"  href="#" class="btn btn-danger btn-sm deleteconf">Delete</a>
                                    </div>

                                </div>





                            </div>
                        </li>
                        <?php } ?>



                    </div>


                </ul>
            </div>
        </div>
    </div>
   
    <div class="modal" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="head1"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="message1"></p>
                </div>
                <div class="modal-footer">
                    <form action="" id="form1" method="POST" enctype="multipart/form-data"><button type="submit"
                            class="btn btn-danger" style="margin-right:0px;">&nbsp;&nbsp;Yes</button>
                        <input type="hidden" name="path" id="path">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
     <script>
    $(document).ready(function() {
     
        $(".deleteconf").click(function() {
            $('#path').val($(this).attr("data-path"));
            $('#head1').html('Delete?');
            $('#message1').html('Are you sure you want to delete the selected items?');
            $('#deleteModal').modal('show');
        });
    });
</script>
    <script>
        $(document).ready(function() {
            var funcNum = <?php echo $_GET['CKEditorFuncNum'] . ';'; ?>
            $('#fileExplorer').on('click', 'button', function() {
                var fileUrl = $(this).attr('title');

                window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
                window.close();
            }).hover(function() {
                $(this).css('cursor', 'pointer');
            });
        });
    </script>

</body>

</html>
