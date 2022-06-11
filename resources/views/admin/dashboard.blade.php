@extends('admin.layouts.app')

@section('content')
    <!-- Morris charts -->
    <link rel="stylesheet" href="{!! asset('assets/vendor_components/morris.js/morris.css') !!}">
    <style>
        .text-red {
            color: red;
        }

        .demo-radio-button label {
            font-size: 15px;
            font-weight: 600 !important;
            margin-bottom: 5px !important;
        }

        .box-title {
            font-size: 15px;
            margin: 0 0 7px 0;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .total-earnings-text {
            font-size: 15px;
        }

        .total-earnings {
            font-size: 30px;
            margin-bottom: 60px;
        }

        #map {
            height: 50vh;
            margin: 10px;
        }

        #legend {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 10px;
            margin: 10px;
            border: 3px solid #000;
        }

        #legend h3 {
            margin-top: 0;
        }

        #legend img {
            vertical-align: middle;
        }

        .g-3 h6 {
            font-weight: 600;
        }

        .g-3 a {
            font-weight: 600;
        }

        .g-3 .bg-holder {
            position: absolute;
            width: 100%;
            min-height: 100%;
            top: 0;
            left: 0;
            background-size: cover;
            background-position: center;
            overflow: hidden;
            will-change: transform, opacity, filter;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            background-repeat: no-repeat;
            z-index: 0;
        }

        .g-3 .bg-card {
            background-size: contain;
            background-position: right;
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .g-3 .display-4 {
            font-size: 2.5rem;
            font-weight: 300;
            line-height: 1.2;
        }

        .badge {
            display: inline-block;
            padding: .35556em .71111em;
            font-size: .75em;
            font-weight: 600;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            background-image: var(--bs-gradient);
        }

        .badge-soft-warning {
            color: #9d5228;
            background-color: #fde6d8;
        }

        .badge-soft-success {
            color: #00864e;
            background-color: #ccf6e4;
        }

        .g-3 .dropdown-menu,
        .dropdown-grid {
            width: -webkit-fill-available;
            border: 1px solid #c5c5c5;
        }

    </style>

    <!-- Start Page content -->
    <section class="content">


        <div class="row g-3">
            <div class="col-sm-6 col-md-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                        style="background-image:url({{ asset('assets/images/corner-3.png') }});">
                    </div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Drivers registered
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-warning"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{ $total_drivers[0]['total'] }}</div><a class="font-weight-semi-bold fs--1 text-nowrap" href="{{url('drivers')}}">See
                            all<span class="fa fa-angle-right ml-1" data-fa-transform="down-1"></span></a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                        style="background-image:url({{ asset('assets/images/corner-2.png') }});">
                    </div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Drivers Approved<span class="badge badge-soft-success rounded-pill ml-2">{{number_format($total_drivers[0]['approve_percentage'],2)}}%</span>
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-success"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{ $total_drivers[0]['approved'] }}</div><a class="font-weight-semi-bold fs--1 text-nowrap" href="{{url('drivers')}}">See
                            all<span class="fa fa-angle-right ml-1" data-fa-transform="down-1"></span></a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                        style="background-image:url({{ asset('assets/images/corner-2.png') }});">
                    </div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Drivers waiting for approval<span class="badge badge-soft-success rounded-pill ml-2">{{number_format($total_drivers[0]['decline_percentage'],2)}}%</span>
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-warning"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{ $total_drivers[0]['decline'] }}</div><a class="font-weight-semi-bold fs--1 text-nowrap"
                            href="{{url('drivers')}}">See all<span class="fa fa-angle-right ml-1" data-fa-transform="down-1"></span></a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                        style="background-image:url({{ asset('assets/images/corner-1.png') }});">
                    </div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Users registered
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-danger"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{ $total_users }}</div><a class="font-weight-semi-bold fs--1 text-nowrap" href="{{url('users')}}">See
                            all<span class="fa fa-angle-right ml-1" data-fa-transform="down-1"></span></a>
                    </div>
                </div>
            </div>
        </div>

        @if(!auth()->user()->hasRole('owner'))
        <div class="row g-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="font-weight-600">Notified SOS</h3>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="box-body row">
                                <div id="js-request-partial-target" class="table-responsive">
                                    <include-fragment>
                                        <p id="no_data" class="lead no-data text-center">
                                            <img src="{{asset('assets/img/dark-data.svg')}}" style="width:150px;margin-top:25px;margin-bottom:25px;" alt="">
                                            <h4 class="text-center" style="color:#333;font-size:25px;">NO DATA FOUND</h4>
                                        </p>
                                    </include-fragment>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="font-weight-600">Today's Trips</h3>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="box-body row">
                                <div class="col-md-6">
                                    <canvas id="trips" height="200"></canvas>
                                </div>

                                <div class="col-md-6 m-auto">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon rounded" style="background-color:#7460ee"><i
                                                        class="ion ion-stats-bars text-white"></i></span>
                                                <div class="info-box-content" style="color: #455a80">
                                                    <h4 class="font-weight-600">
                                                        {{$currency}} {{$todayEarnings[0]['total']}}
                                                        <br>
                                                        Today Earnings
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon rounded" style="background-color:#FC4B6C"><i
                                                        class="ion ion-stats-bars text-white"></i></span>
                                                <div class="info-box-content" style="color: #455a80">
                                                    <h4 class="font-weight-600">

                                                        {{$currency}} {{$todayEarnings[0]['cash']}}

                                                        <br>
                                                        By Cash
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon rounded" style="background-color:#26C6DA"><i
                                                        class="ion ion-stats-bars text-white"></i></span>
                                                <div class="info-box-content" style="color: #455a80">
                                                    <h4 class="font-weight-600">

                                                        {{$currency}} {{$todayEarnings[0]['wallet']}}

                                                        <br>
                                                        By Wallet
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon rounded" style="background-color:#7460ee"><i
                                                        class="ion ion-stats-bars text-white"></i></span>
                                                <div class="info-box-content" style="color: #455a80">
                                                    <h4 class="font-weight-600">

                                                        {{$currency}} {{$todayEarnings[0]['card']}}

                                                        <br>
                                                        By Card/Online
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon rounded" style="background-color:#FC4B6C"><i
                                                        class="ion ion-stats-bars text-white"></i></span>
                                                <div class="info-box-content" style="color: #455a80">
                                                    <h4 class="font-weight-600">

                                                        {{$currency}} {{$todayEarnings[0]['admin_commision']}}

                                                        <br>
                                                        Admin Commision
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon rounded" style="background-color:#26C6DA"><i
                                                        class="ion ion-stats-bars text-white"></i></span>
                                                <div class="info-box-content" style="color: #455a80">
                                                    <h4 class="font-weight-600">
                                                        {{$currency}} {{$todayEarnings[0]['driver_commision']}}
                                                        <br>
                                                        Driver Earnings
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-12">
                <!-- DONUT CHART -->
                <div class="box">
                    <div class="box-header with-border pb-0 mb-20">

                        <h3 class="font-weight-600">Overall Earnings</h3>
                        <ul class="box-controls pull-right">
                            <li><a class="box-btn-close" href="#"></a></li>
                            <li><a class="box-btn-slide" href="#"></a></li>
                            <li><a class="box-btn-fullscreen" href="#"></a></li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="box-body chart-responsive">
                                <canvas id="chart_1" height="200"></canvas>
                            </div>
                        </div>

                        <div class="col-md-6 m-auto pr-25">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon rounded" style="background-color:#7460ee"><i
                                                class="ion ion-stats-bars text-white"></i></span>
                                        <div class="info-box-content" style="color: #455a80">
                                            <h4 class="font-weight-600">

                                                {{$currency}} {{$overallEarnings[0]['total']}}
                                                <br>
                                                Overall Earnings
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-body">
                                        <div class="font-size-18 flexbox align-items-center" style="color: #7460ee">
                                            <span style="color: #455a80">By Cash</span>
                                            <span>{{$currency}} {{$overallEarnings[0]['cash']}}</span>

                                        </div>
                                        <div class="progress progress-xxs mt-10 mb-0">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ number_format($overallEarnings[0]['cash_percentage'], 2) }}%; height: 4px;background-color: #7460ee;"
                                                aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-right"><small class="font-weight-300 mb-5"><i
                                                    class="fa fa-sort-up text-success mr-1"></i>
                                                {{ $overallEarnings[0]['cash_percentage'] }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-body">
                                        <div class="font-size-18 flexbox align-items-center" style="color: #7460ee">
                                            <span style="color: #455a80">By Wallet</span>
                                            <span>{{$currency}} {{$overallEarnings[0]['wallet']}}</span>
                                        </div>
                                        <div class="progress progress-xxs mt-10 mb-0">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ number_format($overallEarnings[0]['wallet_percentage'], 2) }}%; height: 4px;background-color: #7460ee"
                                                aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-right"><small class="font-weight-300 mb-5"><i
                                                    class="fa fa-sort-up text-success mr-1"></i>
                                                {{ number_format($overallEarnings[0]['wallet_percentage'], 2) }}%</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-body">
                                        <div class="font-size-18 flexbox align-items-center" style="color: #7460ee">
                                            <span style="color: #455a80">By Card/Online</span>
                                            <span>{{$currency}} {{$overallEarnings[0]['card']}}</span>
                                        </div>
                                        <div class="progress progress-xxs mt-10 mb-0">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ number_format($overallEarnings[0]['card_percentage'], 2) }}%; height: 4px;background-color: #7460ee"
                                                aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-right"><small class="font-weight-300 mb-5"><i
                                                    class="fa fa-sort-up text-success mr-1"></i>
                                                {{ number_format($overallEarnings[0]['card_percentage'], 2) }}%</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon rounded" style="background-color: #fc4b6c"><i
                                                class="ion ion-stats-bars text-white"></i></span>
                                        <div class="info-box-content" style="color: #fc4b6c">
                                            <h4 class="font-weight-600">

                                                {{$currency}} {{$overallEarnings[0]['admin_commision']}}
                                                <br>
                                                Admin Commision
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon rounded" style="background-color:#26c6da"><i
                                                class="ion ion-stats-bars text-white"></i></span>
                                        <div class="info-box-content" style="color: #26c6da">
                                            <h4 class="font-weight-600">

                                                {{$currency}} {{$overallEarnings[0]['driver_commision']}}
                                                <br>
                                                Driver Earnings
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.box -->

            </div>


            <div class="col-12 col-lg-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="font-weight-600">Cancellation Chart</h3>
                        <ul class="box-controls pull-right">
                            <li><a class="box-btn-close" href="#"></a></li>
                            <li><a class="box-btn-slide" href="#"></a></li>
                            <li><a class="box-btn-fullscreen" href="#"></a></li>
                        </ul>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart" id="bar-chart" style="height: 300px;"></div>
                            </div>
                            <div class="col-md-6 m-auto">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="box box-body bg-primary">
                                            <div class="flexbox">
                                                <span class="ion ion-ios-person-outline font-size-50"></span>
                                                <span class="font-size-40 font-weight-200">{{$trips[0]['total_cancelled']}}</span>
                                            </div>
                                            <div class="text-right">Total Request Cancelled</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box box-body bg-primary" style="background-color: #1e88e5 !important">
                                            <div class="flexbox">
                                                <span class="ion ion-ios-person-outline font-size-50"></span>
                                                <span class="font-size-40 font-weight-200">{{$trips[0]['auto_cancelled']}}</span>
                                            </div>
                                            <div class="text-right">Cancelled due to no Drivers</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box box-body bg-primary" style="background-color: #26c6da !important">
                                            <div class="flexbox">
                                                <span class="ion ion-ios-person-outline font-size-50"></span>
                                                <span class="font-size-40 font-weight-200">{{$trips[0]['user_cancelled']}}</span>
                                            </div>
                                            <div class="text-right">Cancelled by User</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box box-body bg-primary" style="background-color: #fc4b6c !important">
                                            <div class="flexbox">
                                                <span class="ion ion-ios-person-outline font-size-50"></span>
                                                <span class="font-size-40 font-weight-200">{{$trips[0]['driver_cancelled']}}</span>
                                            </div>
                                            <div class="text-right">Cancelled by Driver</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>

    <script src="{{ asset('assets/vendor_components/jquery.peity/jquery.peity.js') }}"></script>

    <script>
        $(function() {
            'use strict';

            // pie chart
            $("span.piee").peity("pie", {
                height: 220,
                width: 300,
            });

        }); // End of use strict

    </script>

    <!-- Morris.js charts -->
    <script src="{{ asset('assets/vendor_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/morris.js/morris.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            "use strict";

            var barData = JSON.parse('<?php echo json_encode($data); ?>');
            var tripData = JSON.parse('<?php echo json_encode($trips); ?>');
            barData = Object.values(barData);

            var barChartData = barData[0]
            var overallEarning = barData[1]

            var bar = new Morris.Bar({
                element: 'bar-chart',
                resize: true,
                data: [barChartData],
                barColors: ['#1e88e5', '#26c6da', '#fc4b6c'],
                barSizeRatio: 0.5,
                barGap: 5,
                xkey: 'y',
                ykeys: ['a', 'u','d'],
                labels: ['Cancelled due to no Drivers', 'Cancelled by User', 'Cancelled by Driver'],
                hideHover: 'auto',
                color: '#666666'
            });

            if ($('#chart_1').length > 0) {
                var ctx1 = document.getElementById("chart_1").getContext("2d");
                var data1 = {
                    labels: overallEarning['months'],
                    datasets: [{
                            label: "Overall Earnings",
                            backgroundColor: "#bdb5ed",
                            borderColor: "#9080f1",
                            pointBorderColor: "#ffffff",
                            pointHighlightStroke: "#26c6da",
                            data: overallEarning['values']
                        },
                       

                    ]
                };

                var areaChart = new Chart(ctx1, {
                    type: "line",
                    data: data1,

                    options: {
                        tooltips: {
                            mode: "label"
                        },
                        elements: {
                            point: {
                                hitRadius: 90
                            }
                        },

                        scales: {
                            yAxes: [{
                                stacked: true,
                                gridLines: {
                                    color: "rgba(135,135,135,0)",
                                },
                                ticks: {
                                    fontFamily: "Poppins",
                                    fontColor: "#878787"
                                }
                            }],
                            xAxes: [{
                                stacked: true,
                                gridLines: {
                                    color: "rgba(135,135,135,0)",
                                },
                                ticks: {
                                    fontFamily: "Poppins",
                                    fontColor: "#878787"
                                }
                            }]
                        },
                        animation: {
                            duration: 3000
                        },
                        responsive: true,
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(33,33,33,1)',
                            cornerRadius: 0,
                            footerFontFamily: "'Poppins'"
                        }

                    }
                });
            }

            tripData = Object.values(tripData);

            if ($('#trips').length > 0) {
                var ctx7 = document.getElementById("trips").getContext("2d");
                var data7 = {
                    labels: [
                        "Completed",
                        "Cancelled",
                        "Scheduled"
                    ],
                    datasets: [{
                        data: [tripData[0].today_completed,tripData[0].today_cancelled,tripData[0].today_scheduled],
                        backgroundColor: [
                            "#7460ee",
                            "#fc4b6c",
                            "#26c6da"
                        ],
                        hoverBackgroundColor: [
                            "#7460ee",
                            "#fc4b6c",
                            "#26c6da"
                        ]
                    }]
                };
                var doughnutChart = new Chart(ctx7, {
                    type: 'doughnut',
                    data: data7,
                    options: {
                        animation: {
                            duration: 4000
                        },
                        responsive: true,
                        legend: {
                            labels: {
                                fontFamily: "Poppins",
                                fontColor: "#878787"
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(33,33,33,1)',
                            cornerRadius: 0,
                            footerFontFamily: "'Poppins'"
                        },
                        elements: {
                            arc: {
                                borderWidth: 0
                            }
                        }
                    }
                });
            }
        });

    </script>

@endsection