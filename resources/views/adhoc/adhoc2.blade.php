<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tagxi-Dispatcher</title>

    <link rel="shortcut icon" href="{{ fav_icon() ?? asset('assets/images/favicon.jpg') }}">
   
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('dispatcher/assets/js/config.js') }}"></script>

    <link href="{{ asset('dispatcher/assets/css/theme-rtl.min.css') }}" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('dispatcher/vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dispatcher/assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('dispatcher/assets/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('dispatcher/assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('dispatcher/jquery.fancybox.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/build/css/intlTelInput.css') }}">
    <link href="{{ asset('dispatcher/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
</head>

<style>
    #legend {
        font-family: Arial, sans-serif;
        background: #fff;
        /*transparent;*/
        padding: 5px;
        margin: 5px;
        border: 3px solid #000;
        width: 10%;
        font-size: 8px;
    }

    #legend div {
        display: flex;
        align-items: center;
    }

    #legend h5 {
        margin-top: 0;
        font-size: 15px;
    }

    #legend img {
        vertical-align: middle;
        width: 35px;
        height: 35px;
        margin: 0 10px;
        padding-top: 3px;
        vertical-align: sub;
    }

    #legend .text {
        font-weight: bold;
        font-size: 10px;
        font-style: italic;
    }

    .etarow {
        padding: 5px;
        background: aliceblue;
        margin: 3px;
    }

    .etarow div {
        font-size: larger;
        font-weight: bolder;
    }

    .detail-popup {
        display: none;
        width: 100%;
        max-width: 100%;
        height: 100%;
    }

    .detail-overflow {
        height: 89vh;
        overflow-y: scroll;
        overflow-x: hidden;
        margin-right: 25px;
    }

    .btn-type {
        width: 32%;
        border-radius: 0;
    }

    .detail-overflow::-webkit-scrollbar {
        width: 3px;
    }

    .detail-overflow::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .detail-overflow::-webkit-scrollbar-thumb {
        background: #888;
    }

    .detail-overflow::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .f-12 {
        font-size: 12px;
    }

    #map {
        height: calc(100vh - 120px);
        width: 100%;
        padding: 10px;
    }

    .modal-content {
        height: 90vh;
    }

    #book-now-map,
    #book-later-map,
    #box-content {
        width: 100%;
        height: calc(80vh - 100px);
        padding: 10px;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    #box-content::-webkit-scrollbar {
        width: 3px;
    }

    #box-content::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #box-content::-webkit-scrollbar-thumb {
        background: #888;
    }

    #box-content::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    a.notification:hover,
    a.notification:focus {
        background-color: aquamarine;
    }

    .packages .fs--1 {
        font-size: .83333rem !important;
    }

    .body-type li {
        list-style: none;
        padding: 5px;
        background: bisque;
        border-radius: 5px;
        text-align: center;
        margin: 3px;
        cursor: pointer;
    }

    .notification-avatar {
        margin-left: auto;
        margin-top: auto;
        margin-bottom: auto;
    }

    .truck-types img {
        width: 55px;
    }

    .truck-types {
        padding: 0px 30px;
        height: 70px;
        margin: 0 0 25px 10px;
        cursor: pointer;
        width: auto;
        text-align: center;
        border: 5px solid #dddddd;
        border-radius: 5px;
    }

    .truck-types:hover,
    .truck-types:focus {
        border: 5px solid #ff9933;
    }

    .pac-container {
        z-index: 10000 !important;
    }

    .iti {
        width: 100%;
    }

    .iti__flag {
        background-image: {{ asset('assets/build/img/flags.png') }};
    }

    @media (-webkit-min-device-pixel-ratio: 2),
    (min-resolution: 192dpi) {
        .iti__flag {
            background-image: {{ asset('assets/build/img/flags@2x.png') }};
        }
    }

    .swiper-slide p {
        margin-top: 15px;
    }

    .swiper-slide.active {
        border: 5px solid #ff9933;
        color: #000;
        background: transparent;
    }

    .sidebar-contact.left.active {
        background: transparent;
    }

    .active {
        background: #ff9933;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .toggle.l.pulse.active {
        background: #000000;
    }

</style>

<main class="main">
    <div class="container-fluid">
        <div class="card my-2">
            <div class="card-body  py-0 position-relative">
                <nav class="navbar navbar-light navbar-top navbar-expand-xl">
                    <a class="navbar-brand me-1 me-sm-3" href="#">
                        <div class="d-flex align-items-center"><img class="me-2" src="{{ asset('map/logo.png') }}"
                                alt="" width="150" />
                            {{-- <span class="font-sans-serif">Truck</span> --}}
                        </div>
                    </a>

                    @if (request()->route()->getName() != 'dispatcherProfile')
                        {{-- <button type="button" class="btn btn-primary btn-sm turned-button mx-4 booking_screen"
                            data-bs-toggle="modal" data-id="book-later">
                            Book Later
                        </button>

                        <button type="button" class="btn btn-primary btn-sm turned-button mr-auto booking_screen"
                            data-id="book-now" data-bs-toggle="modal">
                            Book Now
                        </button> --}}

                        <ul class="navbar-nav navbar-nav-icons flex-row align-items-center" style="margin-left: auto;">
                            <li class="nav-item dropdown"><a class="nav-link pe-0" id="navbarDropdownUser" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar avatar-xl">
                                        <img class="rounded-circle"
                                            src="{{ auth()->user()->profile_picture ?? asset('dispatcher/assets/img/team/3-thumb.png') }}"
                                            alt="" />
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
                                    <div class="bg-white dark__bg-1000 rounded-2 py-2">
                                        {{-- <span class="dropdown-item fw-bold text-warning"><span
                                                class="fas fa-crown me-1"></span><span>{{ ucfirst(auth()->user()->name) }}</span></span> --}}
                                        <div class="dropdown-divider"></div>

                                        {{-- <a class="dropdown-item" href="{{ url('dispatch/profile') }}">Profile</a> --}}

                                        <a class="dropdown-item" href="{{ url('api/spa/logout') }}">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @else
                        <div class="pull-right" style="float: right">
                            <a href="{{ url('dispatch/dashboard') }}"
                                class="btn btn-danger btn-sm turned-button mr-auto">
                                Back
                            </a>
                        </div>
                    @endif
                </nav>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 px-2 px-md-0 py-5 py-md-0 m-auto">
                    <div class="card p-3">
                        <div class="box-body">
                            <form action="#" method="post" id="tripForm">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="box-title">User Details</h6>
                                    </div>

                                    <input id="dialcodes" name="dialcodes" type="hidden" value="91">

                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <input class="form-control w-100 required_for_valid" type="text"
                                                placeholder="Name" name="name" id="name" aria-label="Username"
                                                aria-describedby="basic-addon1">
                                            <span class="text-danger" id="error-name"></span>
                                        </div>
                                    </div>

                                      <?php
                                    $string = request()->mobile;
                                    $mo = substr($string, 2)
                                        ?>

                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <div class="iti iti--separate-dial-code">
                                                <div class="iti__flag-container">
                                                    <div class="iti__selected-flag" role="combobox"
                                                        aria-owns="iti-0__country-listbox" aria-expanded="false"
                                                        title="India (भारत): +91">
                                                        <div class="iti__flag iti__in"></div>
                                                        <div class="iti__selected-dial-code">+91</div>
                                                    </div>
                                                </div><input class="form-control w-100" type="text" name="phone"
                                                    id="phone" aria-label="phone" aria-describedby="basic-addon1"
                                                    autocomplete="off" data-intl-tel-input-id="0"
                                                    style="padding-left: 74px;" placeholder="81234 56789" value="{{$mo}}">
                                            </div>
                                            <span class="text-danger" id="error-msg"></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="box-title">Location Details</h6>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <input class="form-control w-100 required_for_valid pac-target-input"
                                                type="text" placeholder="Pickup Location" name="pickup" id="pickup"
                                                aria-label="Username" aria-describedby="basic-addon1"
                                                autocomplete="off">
                                            <span class="text-danger" id="error-pickup"></span>

                                            <input type="hidden" class="form-control" id="pickup_lat" name="pickup_lat">
                                            <input type="hidden" class="form-control" id="pickup_lng" name="pickup_lng">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group mb-3">
                                            <input class="form-control w-100 required_for_valid pac-target-input"
                                                type="text" placeholder="Drop Location" name="drop" id="drop"
                                                aria-label="Username" aria-describedby="basic-addon1"
                                                autocomplete="off">
                                            <span class="text-danger" id="error-drop"></span>

                                            <input type="hidden" class="form-control" id="drop_lat" name="drop_lat">
                                            <input type="hidden" class="form-control" id="drop_lng" name="drop_lng">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-none" id="vehicleTypeDiv">
                                    <div class="row">

                                        <div class="col-12">
                                            <h6 class="box-title">Vehicle Type</h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-lg-12 mb-4 mb-lg-0">
                                                    <div class="swiper-container theme-slider">
                                                        <div class="swiper-wrapper" id="vehicles">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 py-2 addPackageBtn d-none">
                                                    <span class="badge bg-success cursor-pointer"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseExample"
                                                        aria-expanded="false" style="float:right"
                                                        aria-controls="collapseExample">Add
                                                        Packages</span>
                                                </div>
                                                <div class="collapse text-center" id="collapseExample">
                                                    <hr>
                                                    <h4>
                                                        Packages
                                                    </h4>
                                                    <div class="packages" id="packageList">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="date-option">
                                    <div class="row">

                                        <div class="col-12">
                                            <h6 class="box-title">Booking Type</h6>
                                        </div>
                                        <div class="col-6 chq-radio">
                                            <div class="form-check form-check-inline mx-0 w-100 p-0">
                                                <input class="form-check-input" id="now" type="radio"
                                                    name="booking_type" value="1">
                                                <label class="form-check-label" for="now">Book Now</label>
                                            </div>
                                            <span class="text-danger" id="error-payment_opt"></span>
                                        </div>
                                        <div class="col-6 chq-radio">
                                            <div class="form-check form-check-inline mx-0 w-100 p-0">
                                                <input class="form-check-input" id="later" type="radio"
                                                    name="booking_type" value="1">
                                                <label class="form-check-label" for="later">Book Later</label>
                                            </div>
                                            <span class="text-danger" id="error-payment_opt"></span>
                                        </div>
                                        <div class="mb-3">
                                            <input class="form-control datetimepicker" id="datetimepicker" type="text"
                                                placeholder="d/m/y H:i"
                                                data-options='{"enableTime":true,"dateFormat":"d/m/y H:i","disableMobile":true}' />
                                            <span class="text-danger" id="error-time"> </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="box-title">Payment Method</h6>
                                    </div>
                                    <div class="col-md-12 chq-radio">
                                        <div class="form-check form-check-inline mx-0 px-0">
                                            <input class="form-check-input" id="cash" type="radio" name="payment_opt"
                                                value="1">
                                            <label class="form-check-label" for="cash">
                                                <svg width="35" height="38" viewBox="0 0 40 44" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M36.7644 40.301H3.23563C1.44865 40.301 0 38.8523 0 37.0653V9.14645C0 7.35948 1.44865 5.91083 3.23563 5.91083H36.7644C38.5513 5.91083 40 7.35948 40 9.14645V37.0653C40 38.8523 38.5513 40.301 36.7644 40.301Z"
                                                        fill="url(#paint0_linear)"></path>
                                                    <path
                                                        d="M36.0167 39.534H3.98337C2.27606 39.534 0.891968 38.1499 0.891968 36.4425V9.76896C0.891968 8.06165 2.27598 6.67755 3.98337 6.67755H36.0166C37.7239 6.67755 39.108 8.06156 39.108 9.76896V36.4426C39.1081 38.1499 37.724 39.534 36.0167 39.534Z"
                                                        fill="url(#paint1_linear)"></path>
                                                    <path
                                                        d="M37.8321 14.9416L29.7672 0.973031C29.2301 0.0425448 28.0402 -0.276288 27.1097 0.261023L1.68225 14.9416H37.8321Z"
                                                        fill="url(#paint2_linear)"></path>
                                                    <path
                                                        d="M0 26.1838H40V15.5894C40 14.2521 38.916 13.1682 37.5788 13.1682H2.4212C1.08398 13.1682 0 14.2521 0 15.5894V26.1838Z"
                                                        fill="url(#paint3_linear)"></path>
                                                    <path
                                                        d="M0 22.1993V37.0653C0 38.8523 1.44865 40.301 3.23571 40.301H36.7644C38.5513 40.301 40 38.8524 40 37.0654V22.1993C40 21.0956 39.1052 20.2008 38.0015 20.2008H1.9985C0.894774 20.2008 0 21.0955 0 22.1993Z"
                                                        fill="url(#paint4_linear)"></path>
                                                    <path
                                                        d="M33.9653 14.5317V40.3011H36.7643C38.5513 40.3011 40 38.8524 40 37.0655V20.5663L33.9653 14.5317Z"
                                                        fill="url(#paint5_linear)"></path>
                                                    <path
                                                        d="M31.237 43.9289L3.15785 40.6678C1.35783 40.4586 0 38.9341 0 37.122V8.90521C0 9.51163 0.454384 10.0217 1.05659 10.0917L32.0605 13.6926C33.8605 13.9017 35.2184 15.4263 35.2184 17.2384V40.3832C35.2184 42.5177 33.3573 44.1753 31.237 43.9289Z"
                                                        fill="url(#paint6_linear)"></path>
                                                    <path
                                                        d="M31.237 38.2927L3.15785 35.0316C1.35783 34.8225 0 33.298 0 31.4858V37.122C0 38.9341 1.35783 40.4587 3.15785 40.6678L31.237 43.9289C33.3573 44.1752 35.2185 42.5178 35.2185 40.3831V34.747C35.2184 36.8816 33.3573 38.539 31.237 38.2927Z"
                                                        fill="url(#paint7_linear)"></path>
                                                    <path
                                                        d="M2.51742 13.3032C2.4939 13.3032 2.47004 13.3018 2.44608 13.2993L1.16114 13.158C0.806251 13.1189 0.550173 12.7997 0.589233 12.4449C0.628292 12.0901 0.947726 11.8343 1.30227 11.873L2.58721 12.0143C2.9421 12.0534 3.19818 12.3726 3.15912 12.7274C3.12272 13.0583 2.84269 13.3032 2.51742 13.3032Z"
                                                        fill="url(#paint8_linear)"></path>
                                                    <path
                                                        d="M28.9207 41.7994C28.8936 41.7994 28.8659 41.7976 28.8383 41.7941L26.3409 41.4766C25.9869 41.4316 25.7362 41.1079 25.7813 40.7537C25.8264 40.3997 26.1499 40.1505 26.5041 40.1941L29.0015 40.5117C29.3555 40.5566 29.6063 40.8803 29.5611 41.2345C29.5195 41.5609 29.2413 41.7994 28.9207 41.7994ZM31.3788 41.4794C31.1364 41.4794 30.9038 41.3423 30.7937 41.1083C30.6415 40.7854 30.78 40.4002 31.1029 40.2482C31.7587 39.9392 32.2519 39.4267 32.4561 38.8419C32.5738 38.505 32.9426 38.3273 33.2794 38.4447C33.6164 38.5624 33.7943 38.9311 33.6766 39.2681C33.354 40.1916 32.6358 40.9551 31.6539 41.4176C31.5648 41.4595 31.4712 41.4794 31.3788 41.4794ZM23.9261 41.1643C23.899 41.1643 23.8713 41.1625 23.8437 41.159L21.3463 40.8415C20.9923 40.7965 20.7415 40.4729 20.7867 40.1187C20.8318 39.7646 21.1553 39.5153 21.5095 39.559L24.0069 39.8766C24.3609 39.9216 24.6117 40.2452 24.5665 40.5994C24.5249 40.9257 24.2466 41.1643 23.9261 41.1643ZM18.9315 40.5291C18.9043 40.5291 18.8768 40.5273 18.8491 40.5239L16.3517 40.2063C15.9977 40.1613 15.7469 39.8377 15.7921 39.4835C15.8372 39.1295 16.1608 38.879 16.5149 38.9239L19.0123 39.2414C19.3663 39.2864 19.617 39.61 19.5719 39.9642C19.5303 40.2905 19.2519 40.5291 18.9315 40.5291ZM13.9367 39.8939C13.9096 39.8939 13.882 39.8921 13.8543 39.8887L11.3568 39.571C11.0027 39.526 10.7521 39.2024 10.7972 38.8482C10.8423 38.4941 11.166 38.2448 11.52 38.2886L14.0175 38.6062C14.3716 38.6513 14.6223 38.9748 14.5771 39.329C14.5356 39.6555 14.2573 39.8939 13.9367 39.8939ZM8.94208 39.2587C8.91495 39.2587 8.88739 39.2569 8.85967 39.2535L6.36223 38.9359C6.00812 38.8908 5.75744 38.5672 5.8026 38.213C5.84767 37.8589 6.17139 37.6095 6.52542 37.6534L9.02286 37.971C9.37697 38.0161 9.62765 38.3397 9.58249 38.6939C9.54094 39.0203 9.26271 39.2587 8.94208 39.2587ZM33.2114 37.2006C32.8543 37.2006 32.565 36.9113 32.565 36.5542V34.0368C32.565 33.6798 32.8543 33.3905 33.2114 33.3905C33.5684 33.3905 33.8577 33.6798 33.8577 34.0368V36.5542C33.8578 36.9113 33.5685 37.2006 33.2114 37.2006ZM33.2114 32.1658C32.8543 32.1658 32.565 31.8765 32.565 31.5195V29.002C32.565 28.645 32.8543 28.3557 33.2114 28.3557C33.5684 28.3557 33.8577 28.645 33.8577 29.002V31.5195C33.8578 31.8764 33.5685 32.1658 33.2114 32.1658ZM33.2114 27.1309C32.8543 27.1309 32.565 26.8416 32.565 26.4846V23.9671C32.565 23.6101 32.8543 23.3208 33.2114 23.3208C33.5684 23.3208 33.8577 23.6101 33.8577 23.9671V26.4846C33.8578 26.8415 33.5685 27.1309 33.2114 27.1309ZM33.2114 22.096C32.8543 22.096 32.565 21.8067 32.565 21.4497V18.9323C32.565 18.5752 32.8543 18.2859 33.2114 18.2859C33.5684 18.2859 33.8577 18.5752 33.8577 18.9323V21.4497C33.8578 21.8067 33.5685 22.096 33.2114 22.096ZM32.3074 17.3191C32.1478 17.3191 31.9879 17.2603 31.863 17.1419C31.3839 16.688 30.719 16.3988 29.9906 16.3274L29.9735 16.3256C29.6185 16.2874 29.3618 15.9687 29.4001 15.6138C29.4383 15.2588 29.7569 15.002 30.1119 15.0403L30.1229 15.0415C31.1296 15.1402 32.0655 15.5531 32.7522 16.2036C33.0114 16.4491 33.0223 16.8582 32.7768 17.1174C32.6496 17.2514 32.4787 17.3191 32.3074 17.3191ZM27.541 16.0543C27.5175 16.0543 27.4936 16.0529 27.4696 16.0503L24.9673 15.7752C24.6125 15.7361 24.3563 15.417 24.3954 15.0621C24.4345 14.7073 24.7539 14.452 25.1084 14.4902L27.6108 14.7654C27.9656 14.8044 28.2217 15.1236 28.1827 15.4784C28.1464 15.8093 27.8662 16.0543 27.541 16.0543ZM22.5364 15.504C22.5129 15.504 22.489 15.5026 22.4651 15.5001L19.9627 15.2249C19.6079 15.1859 19.3517 14.8667 19.3908 14.5119C19.4297 14.157 19.7497 13.9013 20.1038 13.94L22.6062 14.2151C22.961 14.2542 23.2172 14.5733 23.1781 14.9281C23.1417 15.2591 22.8617 15.504 22.5364 15.504ZM17.5316 14.9538C17.5081 14.9538 17.4842 14.9524 17.4602 14.9499L14.9579 14.6747C14.6031 14.6357 14.3469 14.3165 14.386 13.9617C14.425 13.6069 14.7442 13.3512 15.099 13.3898L17.6014 13.6649C17.9562 13.704 18.2123 14.0232 18.1733 14.378C18.137 14.7088 17.8569 14.9538 17.5316 14.9538ZM12.5268 14.4036C12.5033 14.4036 12.4795 14.4023 12.4555 14.3997L9.95317 14.1245C9.59829 14.0855 9.34221 13.7663 9.38127 13.4115C9.42024 13.0568 9.73916 12.8012 10.0943 12.8396L12.5966 13.1147C12.9514 13.1538 13.2076 13.473 13.1685 13.8278C13.1323 14.1586 12.8522 14.4036 12.5268 14.4036ZM7.5221 13.8534C7.49858 13.8534 7.47471 13.852 7.45076 13.8494L4.94843 13.5743C4.59363 13.5352 4.33746 13.216 4.37652 12.8612C4.41558 12.5065 4.73459 12.251 5.08956 12.2893L7.59189 12.5645C7.94678 12.6035 8.20286 12.9227 8.1638 13.2775C8.12748 13.6083 7.84737 13.8534 7.5221 13.8534Z"
                                                        fill="url(#paint9_linear)"></path>
                                                    <path
                                                        d="M3.94135 38.6227C3.91422 38.6227 3.88675 38.621 3.85902 38.6175L2.57657 38.4545C2.22245 38.4095 1.9717 38.0858 2.01685 37.7317C2.06184 37.3776 2.38548 37.1271 2.73959 37.172L4.02205 37.335C4.37616 37.38 4.62692 37.7037 4.58176 38.0578C4.54021 38.3842 4.2619 38.6227 3.94135 38.6227Z"
                                                        fill="url(#paint10_linear)"></path>
                                                    <path
                                                        d="M27.6891 4.81319L23.5523 7.06425C23.0135 7.35741 22.339 7.15833 22.0457 6.61948C21.7525 6.08062 21.9516 5.40613 22.4905 5.11288L26.6273 2.86191C27.1662 2.56874 27.8407 2.76782 28.1339 3.30668C28.4271 3.84545 28.228 4.52002 27.6891 4.81319Z"
                                                        fill="#61DB99"></path>
                                                    <path
                                                        d="M35.2184 31.2326L31.1131 27.1273C30.5339 26.4966 29.7027 26.101 28.779 26.101C27.0289 26.101 25.6102 27.5198 25.6102 29.2698C25.6102 30.1936 26.0058 31.0248 26.6365 31.604L35.2184 40.1859V31.2326H35.2184Z"
                                                        fill="url(#paint11_linear)"></path>
                                                    <path
                                                        d="M28.7786 32.4382C30.5287 32.4382 31.9474 31.0195 31.9474 29.2694C31.9474 27.5193 30.5287 26.1006 28.7786 26.1006C27.0286 26.1006 25.6099 27.5193 25.6099 29.2694C25.6099 31.0195 27.0286 32.4382 28.7786 32.4382Z"
                                                        fill="url(#paint12_linear)"></path>
                                                    <path
                                                        d="M28.7786 31.4562C29.9864 31.4562 30.9655 30.4771 30.9655 29.2694C30.9655 28.0616 29.9864 27.0825 28.7786 27.0825C27.5708 27.0825 26.5918 28.0616 26.5918 29.2694C26.5918 30.4771 27.5708 31.4562 28.7786 31.4562Z"
                                                        fill="url(#paint13_linear)"></path>
                                                    <path
                                                        d="M27.9478 28.8911C28.1977 28.8911 28.4003 28.6885 28.4003 28.4386C28.4003 28.1887 28.1977 27.9861 27.9478 27.9861C27.6979 27.9861 27.4953 28.1887 27.4953 28.4386C27.4953 28.6885 27.6979 28.8911 27.9478 28.8911Z"
                                                        fill="url(#paint14_linear)"></path>
                                                    <path
                                                        d="M29.6094 28.8911C29.8593 28.8911 30.0619 28.6885 30.0619 28.4386C30.0619 28.1887 29.8593 27.9861 29.6094 27.9861C29.3595 27.9861 29.1569 28.1887 29.1569 28.4386C29.1569 28.6885 29.3595 28.8911 29.6094 28.8911Z"
                                                        fill="url(#paint15_linear)"></path>
                                                    <path
                                                        d="M27.9478 30.5536C28.1977 30.5536 28.4003 30.351 28.4003 30.1011C28.4003 29.8512 28.1977 29.6486 27.9478 29.6486C27.6979 29.6486 27.4953 29.8512 27.4953 30.1011C27.4953 30.351 27.6979 30.5536 27.9478 30.5536Z"
                                                        fill="url(#paint16_linear)"></path>
                                                    <path
                                                        d="M29.6094 30.5536C29.8593 30.5536 30.0619 30.351 30.0619 30.1011C30.0619 29.8512 29.8593 29.6486 29.6094 29.6486C29.3595 29.6486 29.1569 29.8512 29.1569 30.1011C29.1569 30.351 29.3595 30.5536 29.6094 30.5536Z"
                                                        fill="url(#paint17_linear)"></path>
                                                    <defs>
                                                        <linearGradient id="paint0_linear" x1="20.6536" y1="3.92858"
                                                            x2="20.4201" y2="10.7762" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FFB92D"></stop>
                                                            <stop offset="1" stop-color="#F59500"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint1_linear" x1="21.7734" y1="8.47512"
                                                            x2="21.1374" y2="13.721" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FFB92D"></stop>
                                                            <stop offset="1" stop-color="#F59500"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint2_linear" x1="19.7567" y1="8.63025"
                                                            x2="19.7567" y2="12.6265" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#A7F3CE"></stop>
                                                            <stop offset="1" stop-color="#61DB99"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint3_linear" x1="19.9995" y1="15.548"
                                                            x2="19.9995" y2="26.7619" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FF4C54"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint4_linear" x1="19.9995" y1="23.8748"
                                                            x2="19.9995" y2="41.1937" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FF4C54"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint5_linear" x1="37.7244" y1="27.4167"
                                                            x2="34.8063" y2="27.4167" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#BE3F45" stop-opacity="0"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint6_linear" x1="11.6266" y1="24.421"
                                                            x2="26.4712" y2="31.2686" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FFB92D"></stop>
                                                            <stop offset="1" stop-color="#F59500"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint7_linear" x1="17.1359" y1="39.6675"
                                                            x2="16.5223" y2="44.3574" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#BE3F45" stop-opacity="0"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint8_linear" x1="1.87405" y1="11.8697"
                                                            x2="1.87405" y2="41.8616" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FFF465"></stop>
                                                            <stop offset="1" stop-color="#FFE600"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint9_linear" x1="19.1147" y1="11.8706"
                                                            x2="19.1147" y2="41.7995" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FFF465"></stop>
                                                            <stop offset="1" stop-color="#FFE600"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint10_linear" x1="3.29914" y1="11.8689"
                                                            x2="3.29914" y2="41.8599" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FFF465"></stop>
                                                            <stop offset="1" stop-color="#FFE600"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint11_linear" x1="33.0076" y1="33.4995"
                                                            x2="24.1276" y2="24.6194" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#BE3F45" stop-opacity="0"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint12_linear" x1="28.778" y1="27.2807"
                                                            x2="28.778" y2="31.8629" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FF4C54"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint13_linear" x1="28.7779" y1="30.6426"
                                                            x2="28.7779" y2="27.4806" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#FF4C54"></stop>
                                                            <stop offset="1" stop-color="#BE3F45"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint14_linear" x1="23.6584" y1="9.49896"
                                                            x2="24.5633" y2="13.4952" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#A7F3CE"></stop>
                                                            <stop offset="1" stop-color="#61DB99"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint15_linear" x1="25.32" y1="9.49915"
                                                            x2="26.2249" y2="13.4954" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#A7F3CE"></stop>
                                                            <stop offset="1" stop-color="#61DB99"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint16_linear" x1="23.3003" y1="9.57999"
                                                            x2="24.2052" y2="13.5762" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#A7F3CE"></stop>
                                                            <stop offset="1" stop-color="#61DB99"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="paint17_linear" x1="24.9619" y1="9.58018"
                                                            x2="25.8668" y2="13.5764" gradientUnits="userSpaceOnUse">
                                                            <stop stop-color="#A7F3CE"></stop>
                                                            <stop offset="1" stop-color="#61DB99"></stop>
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                                &nbsp; Cash
                                            </label>
                                        </div>
                                        <span class="text-danger" id="error-payment_opt"></span>
                                    </div>
                                </div>

                                <div class="col-12 mt-3 text-center">
                                    <button type="button" class="btn btn-primary btn-md turned-button form-submit">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                 <div class="col-md-6">
                        <div class="box">
                            <div class="row etarow">
                                <div class="col-4 etacol etaprice"><i class="fas fa-wallet"></i> <span>- - -</span>
                                </div>
                                <div class="col-4 etacol etatime"><i class="far fa-clock"></i> <span>- - -</span></div>
                                <div class="col-4 etacol etadistance"><i class="fas fa-map-marker-alt"></i> <span>- -
                                        -</span></div>
                            </div>

                           <div id="map"></div>
                        </div>
                    </div>

               
            </div>
        </div>
        {{-- Book Now --}}


      @push('booking-scripts')
      
        {{-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBeVRs1icwooRpk7ErjCEQCwu0OQowVt9I&libraries=places"></script> --}}
         <script src="{{ asset('assets/build/js/intlTelInput.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="{{ asset('dispatcher/assets/js/flatpickr.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/progressbar/progressbar.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/lodash/lodash.min.js') }}"></script>
    <script src="../polyfill.io/v3/polyfill.min58be.js?features=window.scroll"></script>
    <script src="{{ asset('dispatcher/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('dispatcher/vendors/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('dispatcher/assets/js/theme.js') }}"></script>
    <script src="{{ asset('dispatcher/assets/js/user.js') }}"></script>
    <script src="{{ asset('dispatcher/jquery.fancybox.min.js') }}"></script>

    
    <script src="{{asset('assets/vendor_components/jquery/dist/jquery.js')}}"></script>

    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBeVRs1icwooRpk7ErjCEQCwu0OQowVt9I&libraries=places">
    </script>
    <script>


    // 40.71.190.142
    google.maps.event.addDomListener(window, 'load', initialize);
            function initialize() {

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v10',
                center: new google.maps.LatLng(44.599, -78.443),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                zoomControl: true
            });
        new AutocompleteDirectionsHandler(map);

        }
        google.maps.event.addDomListener(window, 'load', function () {

        var pickup_location = new google.maps.places.Autocomplete(document.getElementById('pickup_location'));
        google.maps.event.addListener(pickup_location, 'place_changed', function () {
            console.log(pickup_location);
        });

        var drop_location = new google.maps.places.Autocomplete(document.getElementById('drop_location'));
        google.maps.event.addListener(drop_location, 'place_changed', function () {
        });
    });

    /**
 * @constructor
 */
function AutocompleteDirectionsHandler(map) {
  this.map = map;
  this.originPlaceId = null;
  this.destinationPlaceId = null;
  this.travelMode = 'DRIVING';
  this.directionsService = new google.maps.DirectionsService;
  this.directionsRenderer = new google.maps.DirectionsRenderer;
  this.directionsRenderer.setMap(map);

  var originInput = document.getElementById('pickup_location');
  var destinationInput = document.getElementById('drop_location');

  var originAutocomplete = new google.maps.places.Autocomplete(originInput);
  // Specify just the place data fields that you need.
  originAutocomplete.setFields(['place_id','geometry', 'name', 'formatted_address']);

  var destinationAutocomplete =
      new google.maps.places.Autocomplete(destinationInput);
  // Specify just the place data fields that you need.
  destinationAutocomplete.setFields(['place_id','geometry', 'name', 'formatted_address']);
  this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
  this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

  this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
  this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(
      destinationInput);
  // this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
}

AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(
    autocomplete, mode) {
  var me = this;
  autocomplete.bindTo('bounds', this.map);

  autocomplete.addListener('place_changed', function() {
    console.log(autocomplete.getPlace().geometry.location.lat());
    var place = autocomplete.getPlace();
    if (!place.place_id) {
      window.alert('Please select an option from the dropdown list.');
      return;
    }

    if (mode === 'ORIG') {
      me.originPlaceId = place.place_id;
    } else {
      me.destinationPlaceId = place.place_id;
    }
    me.route();
  });
};

AutocompleteDirectionsHandler.prototype.route = function() {
  if (!this.originPlaceId || !this.destinationPlaceId) {
    return;
  }
  var me = this;

  this.directionsService.route(
      {
        origin: {'placeId': this.originPlaceId},
        destination: {'placeId': this.destinationPlaceId},
        travelMode: this.travelMode
      },
      function(response, status) {
        if (status === 'OK') {
            // console.log(response);
          me.directionsRenderer.setDirections(response);
        } else {
          window.alert('Directions request failed due to ' + status);
        }
      });
};


</script>

@endpush

    </div>
</main>

</body>

</html>
