<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8" />
    <title>Admin login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <!-- <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}"> -->
    <link rel="shortcut icon" href="{{ fav_icon() ?? asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="{{ url('assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <!-- Bootstrap extend-->
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap-extend.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('assets/css/master_style.css') }}">

    <!-- Fab Admin skins -->
    <link rel="stylesheet" href="{{ url('assets/css/skins/_all-skins.css') }}">
    <style>
        .error-style {
            list-style: none;
            color: red;
            text-align: center;
            margin-top: 15%;
            padding: 0;
        }

        body {
            background: none !important;
        }

        .login-email input {
            height: 55px;
            background: #f9f9f9;
            border: 1px solid #bfbfbf;
            border-radius: 10px !important;
            padding-left: 25px;
            margin-bottom: 20px;
        }

        .login-btn button {
            font-size: 12px;
        }

    </style>
</head>

<body class="hold-transition login-page">

    <div class="row m-auto">

        <div class="col-md-4 col-12 p-0 m-auto">
            <div class="login-box">
                <div class="login-box-body text-center">
                    <div class="print-error-msg" style="position: absolute;right: 0;left: 0;">
                        <ul class="error-style"></ul>
                    </div>
                    <img src="assets/images/favicon.png" alt="">
                    <h3 class="text-center">Admin Panel</h3>
                    <p class="login-box-msg"></p>
                    <!-- action="{{ url('api/spa/login') }}" method="post" -->
                    <form class="login_form" id="form" enctype="multipart/form-data">
                        <div class="col-12 form-group has-feedback"
                            style="display:flex;margin-bottom:0px;background: #fff;padding: 0px;">
                            <div class="col-md-11 mx-auto p-0 login-email">
                                <input type="email" style="border-radius:none;" class="form-control rounded"
                                    name="email" id="emailaddress" required="" placeholder="Enter Your Number"
                                    maxlength="74" data-validation="required length email" data-validation-length="8-74"
                                    data-validation-error-msg-email="Please enter valid email address">
                            </div>
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="col-5 text-center pb-15 login-btn m-auto">
                            <button class="btn btn-info btn-block submit_button" type="submit">Generate
                                OTP</button>
                        </div>
                        <div class="col-12 form-group has-feedback"
                            style="display:flex;margin-bottom:0px;background: #fff;padding: 0px;">
                            <div class="col-md-11 mx-auto p-0 login-email">
                                <input type="email" style="border-radius:none;" class="form-control rounded"
                                    name="email" id="emailaddress" required="" placeholder="Enter OTP" maxlength="74"
                                    data-validation="required length email" data-validation-length="8-74"
                                    data-validation-error-msg-email="Please enter valid email address">
                            </div>
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="col-5 text-center pb-15 login-btn m-auto">
                            <button class="btn btn-info btn-block submit_button" type="submit">Submit</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>


        <div class="col-md-8 d-none d-md-block p-0" style="height: 100vh">
            <img src="assets/images/bg.jpg" alt="Book" style="height: 100vh;max-width: none;">
        </div>


    </div>

    <!-- jQuery 3 -->
    <script src="{{ url('assets/vendor_components/jquery/dist/jquery.min.js') }}"></script>


    <!-- Bootstrap 4.0-->
    <script src="{{ url('assets/vendor_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/jquery.form-validator.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <?php if (session()->has('success')) {
    $alertMessage = session()->get('success'); ?>
    <script>
        var alertMessage = "<?php echo $alertMessage; ?>";

        //alert(alertMessage);
        $.toast({
            heading: '',
            text: alertMessage,
            position: 'top-right',
            loaderBg: '#5ba035',
            icon: 'success',
            hideAfter: 5000,
            stack: 1
        });

    </script>
    <?php
    } ?>

</body>

</html>
