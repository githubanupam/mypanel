<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="<?=base_url()?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=base_url()?>assets/js/popper.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?=base_url()?>assets/js/plugins/pace.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/plugins/bootstrap-notify.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <title>Login - Sanjog Admin</title>
    <style type="text/css">
        .body{
            background: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.5)), url("<?=base_url()?>assets/images/lalbazar.jpg");
            background-size: 100% 100%;
        }
        .outer {
          display: table;
          position: absolute;
          height: 100%;
          width: 100%;
          background: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,.5)), url("<?=base_url()?>assets/images/lalbazar.jpg");
          background-size: 100% 100%;
        }

        .middle {
          display: table-cell;
          vertical-align: middle;
        }

        .inner {
          margin-left: auto;
          margin-right: auto;
          padding:0 6vw;
        }
        .login-container{
            margin:auto;
            border-radius: 5px;
            padding: 20px 20px;
        }
        .logo-container{
            margin:auto;
        }
    </style>
</head>

<body>
    <div class="outer">
        <div class="middle">
            <div class="inner container">
                <div class="row">
                    <div class="logo-container text-center col-md-4">
                        <img src="<?=base_url()?>assets/images/tittle.png" width="100px" height="100px">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="login-container bg-light col-md-4"> 
                        <?=form_open('login/loginMe', 'class="login-form"');?>
                            <h3 class="text-center"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
                            <hr>
                            <?php
                                $error = $this->session->flashdata('error');
                                if($error){
                            ?>
                                    <script type="text/javascript">
                                        $.notify({
                                            title: "Opps!! : ",
                                            message: "You are using wrong username or password!",
                                            icon: 'fa fa-exclamation-triangle' 
                                        },{
                                            type: "danger"
                                        });
                                    </script>
                            <?php 
                                }

                                $success = $this->session->flashdata('success');
                                if($success){ 

                            ?>
                                    <script type="text/javascript">
                                        $.notify({
                                            title: "Welcome : ",
                                            message: "Connect with sanjog!",
                                            icon: 'fa fa-check' 
                                        },{
                                            type: "success"
                                        });
                                    </script>
                            <?php 
                                } 
                            ?> 
                            <div class="form-group">
                                <label class="control-label">USERNAME</label>
                                <input name="username" class="form-control" type="text" placeholder="Username" value="<?=set_value('username')?>" autofocus>
                                <?php echo form_error('username')?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">PASSWORD</label>
                                <input name="password" class="form-control" type="password" placeholder="Password" value="<?=set_value('password')?>">
                                <?php echo form_error('password')?>
                            </div>
                            
                            <!-- <div class="form-group">
                                <div class="utility">
                                    <p class="semibold-text mb-2"><a href="<?php echo base_url() ?>login/forgotPassword">Forgot Password ?</a></p>
                                </div>
                            </div> -->
                            <br><br>
                            <div class="form-group btn-container">
                                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                            </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>