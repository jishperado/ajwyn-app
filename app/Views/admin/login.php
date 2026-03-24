<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IHD Management System</title>

    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="width=1024, maximum-scale=1.0, target-densitydpi=device-dpi">
    <link rel="shortcut icon" href="<?= getenv('app_baseURL') ?>assets/img/icon.jpg">

    <link rel="stylesheet" href="<?= getenv('app_baseURL') ?>assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?= getenv('app_baseURL') ?>assets/css/language/l_one.css">

    <link rel="stylesheet" href="<?= getenv('app_baseURL') ?>assets/css/dist/abey.css">

    <script>
        setTimeout(function() {
            document.getElementById('aap').className = 'waa';
        }, 5000);
    </script>


</head>

<body class="hold-transition">
    <div class="wrapper">

        <div class="content-wrapper">




            <section class="content" style="margin:5%; ">

                <div class="col-xs-12"></div>





                <div class="col-lg-3 col-xs-12" style="background-color:#33363d;">



                </div>





                <!--next-->





                <div class="col-lg-3 col-xs-12" style="background-color:#FFF; ">

                    <div class="box box-primary">





                        <div class="box-body">



                            <div class="padding-info">




                                <div class="div_hide">
                                    <div class="login-box">


                                        <div class="login-box-body" style=" min-height:400px; border-right: solid 1px; border-color:#CCC; color:#3984af; font-size:12px; margin-bottom:20px; margin-top:20px;">
                                            <center><img style="margin-top: 30px;width:80%" src="<?=  base_url('asset/images/ajwyn_logo.png') ?>" /></center><br>
                                            <b>CLLIT</b><br><a href="https://www.cllit.com" target="_blank">By Carmellight Technologies</a>


                                        </div>
                                    </div>
                                </div>




                            </div>

                        </div>

                    </div>

                </div>





                <!--next-->




                <div class="col-lg-3 col-xs-12" style="background-color:#FFF;">



                    <!--work start-->


                    <div class="login-box">


                        <div class="login-box-body" style="min-height:400px;margin-bottom:20px; margin-top:20px;" align="center">
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
                            <div class="div_view">

                                <img src="https://www.webtest.cllit.com/web/images/logo.png" width="50%" /><br><br>
                            </div>

                            <h4 class="login-box-msg">USER LOGIN</h4>


                            <p class="login-box-msg" style="margin-bottom: 20px; text-transform: uppercase;">Ajwyn Administration  Panel </p>


                            <!--error message part start wakin-->

                            <p class="login-box-msg" style="font-size:12px; color:#F00;">

                            </p>

                            <!--error message part end wakin-->

                            <form action="" method="post" name="wakin" id="wakin">
                                <div class="form-group has-feedback">
                                    <input type="text" autocomplete="off" name="name" class="form-control" placeholder="Username" value="<?= set_value('name') ?>">
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    <font size="2" color="red"><?= $errors['name'] ?? '' ?> </font>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" autocomplete="off" name="pwd" class="form-control" placeholder="Password">
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    <font size="2" color="red"><?= $errors['pwd'] ?? '' ?> </font>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat" style="width:100%;">Sign In</button>

                                    </div>
                                  


                                </div>
                            </form>


                        </div>
                    </div>

                </div>




                <!--work end-->

        </div>





        <!--next-->



        <div class="col-lg-3 col-xs-12" style="background-color:#33363d;">



        </div>





        <!--next-->







        </section>


    </div>



    </div>


    <script src="<?= getenv('app_baseURL') ?>assets/js/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?= getenv('app_baseURL') ?>assets/js/bootstrap.min.js"></script>

</body>

</html>