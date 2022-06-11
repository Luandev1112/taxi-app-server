
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
                    <div class="alert alert-danger" id="error" style="display: none;"></div>
                    <div class="alert alert-success" id="successAuth" style="display: none;"></div>

                    <!-- action="{{ url('api/spa/login') }}" method="post" -->
                    <form class="login_form" id="form"  method="GET" action>
                        @csrf
                        
                        <div class="col-12 form-group has-feedback"
                            style="display:flex;margin-bottom:0px;background: #fff;padding: 0px;">
                            <div class="col-md-11 mx-auto p-0 login-email">
                                {{-- <label for="Mobile">Mobile</label> --}}
                                <input type="phone" style="border-radius:none;" class="form-control rounded"
                                    name="phone" id="number" required="" placeholder="+91 9798655432"
                                    maxlength="15" data-validation="required length phone" data-validation-length="8-74"
                                    data-validation-error-msg-email="Please enter valid mobile number">

                                <div id="recaptcha-container"></div>
                            </div>
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="col-5 text-center pb-15 login-btn m-auto">
                            <button class="btn btn-info btn-block submit_button" type="button" onclick="sendOTP();">Generate
                                OTP</button>
                        </div>
                        <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
                   

                        <div class="col-12 form-group has-feedback"
                            style="display:flex;margin-bottom:0px;background: #fff;padding: 0px;">
                            <div class="col-md-11 mx-auto p-0 login-otp">
                                <input type="text" style="border-radius:none;" class="form-control rounded"
                                    name="otp" id="verification"" required="" placeholder="Enter OTP" maxlength="74"
                                    data-validation="required length otp" data-validation-length="8-74"
                                    data-validation-error-msg-email="Please enter valid OTP">
                            </div>
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="col-5 text-center pb-15 login-btn m-auto">
                            <button class="btn btn-info btn-block submit_button" type="button" onclick="verify()">Submit</button>
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

    <!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.4.2/firebase-app.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.4.2/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

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


    <script>
       var firebaseConfig = {
                apiKey: "{{get_settings('firebase-api-key')}}",
    authDomain: "{{get_settings('firebase-auth-domain')}}",
    databaseURL: "{{get_settings('firebase-db-url')}}",
    projectId: "{{get_settings('firebase-project-id')}}",
    storageBucket: "{{get_settings('firebase-storage-bucket')}}",
    messagingSenderId: "{{get_settings('firebase-messaging-sender-id')}}",
    appId: "{{get_settings('firebase-app-id')}}",
    measurementId: "{{get_settings('firebase-measurement-id')}}"
          };
            firebase.initializeApp(firebaseConfig);
    </script>

     <script type="text/javascript">
        
       window.onload = function () {
           render();
        };

        function render() {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        function sendOTP() {
            var number = $("#number").val();
            firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                window.confirmationResult = confirmationResult;
                coderesult = confirmationResult;
                console.log(coderesult);
                $("#successAuth").text("Message sent");
                $("#successAuth").show();
            }).catch(function (error) {
                $("#error").text(error.message);
                $("#error").show();
            });
        }

        function verify() {
            var code = $("#verification").val();
            var number = $("#number").val();
            coderesult.confirm(code).then(function (result) {
                var user = result.user;
                console.log(user);
                console.log(number);
                // $("#successOtpAuth").text("Auth is successful");
                // $("#successOtpAuth").show();
                // if ($("#successOtpAuth")){
                    // window.location.href = "/adhoc2?mobile=" + number;
                // }
                
            }).catch(function (error) {

                $("#error").text(error.message);
                $("#error").show();
                window.location.href = "/adhoc2?mobile=" + number;
            });
        }
    </script>

</body>

</html>

