@extends('admin.layouts.app')

 <!-- Morris charts -->
    <link rel="stylesheet" href{!! asset('assets/vendor_components/morris.js/morris.css') !!}">
    <style>
         .timeline .timeline-item>.timeline-point {
        color: yellow !important;
        padding: 3px;
    }
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

        /*#legend {
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
        }*/

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


@section('content')


    <section class="content">

        <div class="row">

             <div class="col-12">
                <div class="box">
                    <div class="box-body box-profile">
                        <div class="row">
                            <div class="col-md-2 m-auto text-right">
                                <img class="avatar avatar-xxl avatar-bordered"
                                    src="{{$item->user->profile_pic ?: asset('/assets/img/user-dummy.svg') }}" alt="">
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="profile-user-info">
                                    <h3>
                                        <span class="text-gray">{{$item->name}}  @if ($item->available == '1')
    <span class="badge badge-success font-size-10">{{trans('view_pages.online')}}</span>
    @else
    <span class="badge badge-danger font-size-10">{{trans('view_pages.offline')}}</span>
    @endif</span>
                                    </h3>
                                    <p>
                                         @php $rating = $item->rating($item->user_id); @endphp
                                        @foreach (range(1, 5) as $i)
                                            <span class="fa-stack" style="width:1em">


                                                @if ($rating > 0)
                                                    @if ($rating > 0.5)
                                                        <i class="fa fa-star checked" style="color: yellow"></i>
                                                    @else
                                                        <i class="fa fa-star-half-o" style="color: yellow"></i>
                                                    @endif
                                                @else

                                                    <i class="fa fa-star-o " style="color: yellow"></i>
                                                @endif

                                                @php $rating--; @endphp
                                            </span>
                                        @endforeach
                                    </p>

                                  
                                       {{--  <span>
                                            <i class="fa fa-star" style="color: yellow"></i>
                                            <i class="fa fa-star" style="color: yellow"></i>
                                            <i class="fa fa-star" style="color: yellow"></i>
                                            <i class="fa fa-star" style="color: yellow"></i>
                                            <i class="fa fa-star" style="color: yellow"></i>
                                        </span> --}}
                                       <p>
                                    
                                        <span class="text-gray">
                                           {{$item->user->email}} <br>
                                            {{$item->user->mobile}}
                                        </span>
                                        </p>
                                        @if ($item->available == 0)
                                        <p>
                                         <span class="text-gray">
                                           
                                            Last Logout : {{$item->getConvertedUpdatedAtAttribute()}} <br>
                                        </span>
                                    </p>
                                    @endif
                                    
                                </div>
                            </div>
                           <div class="col-md-2 m-auto">
                                <img class="w-fill" src="{{ $item->vehicleType->icon ?: asset('assets/images/2.jpg') }}"
                                    alt="">
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="profile-user-info">
                                    <h3>
                                        <span class="text-gray">{{ $item->carMake->name }}</span>
                                    </h3>
                                    <p>
                                        <span class="text-gray">
                                            {{ $item->carModel->name }} ({{ $item->vehicleType->name }})
                                        </span>
                                    </p>
                                    <p>
                                        <span class="text-gray">
                                            {{ $item->car_number }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

       
            {{-- card --}}
             <div class="row g-3">
            <div class="col-sm-6 col-md-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                        style="background-image:url({{ asset('assets/images/corner-3.png') }});">
                    </div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Today's Trip
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-warning"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{$todayTrips}}</div>
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
                        <h6>Today's Earning
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-success"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{$currency}} {{$todayEarning}}</div>
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
                        <h6>Total Trips
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-warning"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{$totalTrips}}</div>
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
                        <h6>Total Earnings
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-danger"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{$currency}} {{$totalEarning}}</div>
                    </div>
                </div>
            </div> <div class="col-sm-6 col-md-3">
                <div class="card overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                        style="background-image:url({{ asset('assets/images/corner-1.png') }});">
                    </div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Wallet Amount
                        </h6>
                        <div class="display-4 fs-4 mb-2 font-weight-normal font-sans-serif text-danger"
                            data-countup="{&quot;endValue&quot;:58.386,&quot;decimalPlaces&quot;:2,&quot;suffix&quot;:&quot;k&quot;}">
                            {{$currency}} {{$wallet_amount }}</div>
                    </div>
                </div>
            </div>
        </div>



         <div class="row">
            <div class="col-12">

                <div class="box">

                    <div class="box-header with-border">
                                <h3 class="font-weight-600">Driver Location :</h3>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>
                   
                         <div class="box-body">
                           
                         <div id="map"></div>
                       
                    </div>
                </div>

            </div>
        </div>


            <div class="row">
            <div class="col-12 col-lg-12">
                <!-- DONUT CHART -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="font-weight-600">Total earnings</h3>
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
                                        <span class="info-box-icon rounded"
                                            style="background-color:#7460ee;padding: 20px;"><i
                                                class="ion ion-stats-bars text-white"></i></span>
                                        <div class="info-box-content" style="color: #455a80">
                                            <h4 class="font-weight-600">
                                                {{$currency}} {{$total_overall_earnings}}
                                                <br>
                                                Total Earnings
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-body">
                                        <div class="font-size-18 flexbox align-items-center" style="color: #7460ee">
                                            <span style="color: #455a80">By Cash</span>
                                            <span>{{$currency}} {{$overall_earning_cash}}</span>
                                        </div>
                                        <div class="progress progress-xxs mt-10 mb-0">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ number_format($overall_earning_cash_percent, 2) }}%; height: 4px;background-color: #7460ee;"
                                                aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-right"><small class="font-weight-300 mb-5"><i
                                                    class="fa fa-sort-up text-success mr-1"></i> {{ number_format($overall_earning_cash_percent, 2) }}%</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-body">
                                        <div class="font-size-18 flexbox align-items-center" style="color: #7460ee">
                                            <span style="color: #455a80">By Wallet</span>
                                            <span>{{$currency}} {{$overall_earning_wallet}}</span>
                                        </div>
                                        <div class="progress progress-xxs mt-10 mb-0">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ number_format($overall_earning_wallet_percent, 2) }}%; height: 4px;background-color: #7460ee" aria-valuenow="65"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-right"><small class="font-weight-300 mb-5"><i
                                                    class="fa fa-sort-up text-success mr-1"></i> {{ number_format($overall_earning_wallet_percent, 2) }}%</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-body">
                                        <div class="font-size-18 flexbox align-items-center" style="color: #7460ee">
                                            <span style="color: #455a80">By Card/Online</span>
                                            <span>{{$currency}} {{$overall_earning_card}}</span>
                                        </div>
                                        <div class="progress progress-xxs mt-10 mb-0">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ number_format($overall_earning_card_percent, 2) }}%; height: 4px;background-color: #7460ee" aria-valuenow="65"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="text-right"><small class="font-weight-300 mb-5"><i
                                                    class="fa fa-sort-up text-success mr-1"></i> {{ number_format($overall_earning_card_percent, 2) }}%</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon rounded"
                                            style="background-color: #fc4b6c;padding: 20px;"><i
                                                class="ion ion-stats-bars text-white"></i></span>
                                        <div class="info-box-content" style="color: #fc4b6c">
                                            <h4 class="font-weight-600">
                                                {{$currency}} {{$overall_earning_commision}}
                                                <br>
                                               Admin Commision
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon rounded"
                                            style="background-color:#26c6da;padding: 20px;"><i
                                                class="ion ion-stats-bars text-white"></i></span>
                                        <div class="info-box-content" style="color: #26c6da">
                                            <h4 class="font-weight-600">
                                               {{$currency}} {{$overall_earning_driver_commision}}
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
            </div>

            <div class="row">
             <div class="col-12 col-lg-12">
                <!-- DONUT CHART -->
                 <div class="box">
                    <div class="box-header with-border">
                        <h3 class="font-weight-600">Trip statistics</h3>
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
                                        <!-- small box -->
                                    <div class="small-box text-white" style="background-color:#7460ee">
                                        <div class="inner">
                                            <h3>{{$total_completedTrips}}</h3>

                                            <p>Completed Trips</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-pie-chart"></i>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                       <!-- small box -->
                                    <div class="small-box text-white" style="background-color:#fc4b6c">
                                        <div class="inner">
                                            <h3>{{$total_cancelledTrips}}</h3>

                                            <p>Cancelled Trips</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-pie-chart"></i>
                                        </div>
                                    </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            </div>



        <div class="row">
            <div class="col-md-12">
                <div class="row">


                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="font-weight-600">Ongoing trip Info</h3>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="tickets" class="table mt-0 table-hover no-wrap table-striped table-bordered"
                                        data-page-size="10">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th>Vehicle No</th>
                                                <th>Pickup details</th>
                                                <th>Drop details</th>
                                                <th>Trip Status</th>
                                                <th>Trip Request</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trip_info as $trip)


                                            <tr>
                                                <td>
                                                    {{$trip->driverDetail->car_number ?? ''}}
                                                </td>
                                                <td>
                                                    {{$trip->requestPlace->pick_address }}
                                                </td>
                                                <td>
                                                     {{$trip->requestPlace->drop_address }}
                                                </td>
                                                @if($trip->is_cancelled == 1)
                                                    <td><span class="label label-danger">@lang('view_pages.cancelled')</span></td>
                                                    @elseif($trip->is_completed == 1)
                                                    <td><span class="label label-success">@lang('view_pages.completed')</span></td>
                                                    @elseif($trip->is_trip_start == 0 && $trip->is_cancelled == 0)
                                                    <td><span class="label label-warning">@lang('view_pages.not_started')</span></td>
                                                    @else
                                                    <td>-</td>
                                                @endif
                                                <td>
                                                   <a href="{{url('requests/trip_view',$trip->id) }}">View</a>
                                                </td>
                                            </tr>
                                             @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        


        <div class="row">
            <div class="col-md-12">
                <div class="row">


                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="font-weight-600">Shift History</h3>
                                <ul class="box-controls pull-right">
                                    <li><a class="box-btn-close" href="#"></a></li>
                                    <li><a class="box-btn-slide" href="#"></a></li>
                                    <li><a class="box-btn-fullscreen" href="#"></a></li>
                                </ul>
                            </div>

                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="tickets" class="table mt-0 table-hover no-wrap table-striped table-bordered"
                                        data-page-size="10">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th>#</th>
                                                <th>Vehicle No</th>
                                                <th>Shift Start</th>
                                                <th>Shift End</th>
                                                <th>Shift Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($history as $key=>$hist)
                                               
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$hist->driver->car_number ?? '' }}</td>
                                                <td>
                                                    {{$hist->getConvertedOnlineAtAttribute()}}
                                                </td>
                                                <td>
                                                    {{$hist->getConvertedOfflineAtAttribute()}}
                                                </td>
                                                <td>{{$hist->getConvertedDurationAtAttribute()}}</td>
                                               
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-right">
                                        <span  style="float:right">
                                        {{$history->links()}}
                                        </span>
                                        </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>


           
        </div>
       

    </section>
    <script src="{{ asset('assets/vendor_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/morris.js/morris.min.js') }}"></script>
    <script>
        $(function() {
            "use strict";

            //BAR CHART
            var barData = JSON.parse('<?php echo json_encode($data); ?>');
            barData = Object.values(barData);
            var bar = new Morris.Bar({
                element: 'bar-chart',
                resize: true,
                data: barData,
                barColors: ['#7460ee', '#fc4b6c'],
                barSizeRatio: 0.5,
                barGap: 5,
                xkey: 'y',
                ykeys: ['a', 'u'],
                labels: ['Completed Trips', 'Cancelled Trips'],
                hideHover: 'auto'
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            "use strict";

            
           

            if ($('#chart_1').length > 0) {
                var ctx1 = document.getElementById("chart_1").getContext("2d");
                var data1 = {
                    labels: ["January", "February", "March", "April", "May", "June", "July"],
                    datasets: [{
                        label: "Overall Earnings",
                        backgroundColor: "#bdb5ed",
                        borderColor: "#9080f1",
                        pointBorderColor: "#ffffff",
                        pointHighlightStroke: "#26c6da",
                        data: [{{$jan_overall_earning}}, {{$feb_overall_earning}}, {{$mar_overall_earning}}, {{$apr_overall_earning}}, {{$may_overall_earning}}, {{$jun_overall_earning}}, {{$jul_overall_earning}}]
                    }]
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
            if ($('#chart_6').length > 0) {
                var ctx6 = document.getElementById("chart_6").getContext("2d");
                var data6 = {
                    labels: [
                        "By Cash",
                        "By Wallet",
                        "By Card/Online"
                    ],
                    datasets: [{
                        data: [15956, 5000, 10956],
                        backgroundColor: [
                            "#7460ee",
                            "#fc4b6c",
                            "#398bf7"
                        ],
                        hoverBackgroundColor: [
                            "#7460ee",
                            "#fc4b6c",
                            "#398bf7"
                        ]
                    }]
                };

                var pieChart = new Chart(ctx6, {
                    type: 'pie',
                    data: data6,
                    options: {
                        animation: {
                            duration: 3000
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
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places"></script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.19.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.19.0/firebase-database.js"></script>
<!-- TODO: Add SDKs for Firebase products that you want to use https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.19.0/firebase-analytics.js"></script>

<script>
    // var lat = 11.015956;
    // var lng = 76.968985;
    {{-- var lat = "{{$item->requestPlace->pick_lat}}" --}}
    {{-- var lng = "{{$item->requestPlace->pick_lng}}" --}}
    var pickLat = [];
    var pickLng = [];
    var default_lat = '{{ $default_lat }}';
    var default_lng = '{{ $default_lng }}';
    var driverLat, driverLng, bearing, type;
    var marker = [];
    var onTrip, available;
    onTrip = available = true;
   
    var driverId = "{{ $item->id }}"
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true
    });

    // Your web app's Firebase configuration
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
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

    var map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(default_lat, default_lng),
            zoom: 10,
            mapTypeId: 'roadmap',
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.TOP_CENTER,
            },
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
            },
            scaleControl: true,
            streetViewControl: false,
            fullscreenControl: true,
        });

    directionsRenderer.setMap(map);

    var iconBase = "{{ asset('map/icon/') }}";
    var icons = {
        available: {
            name: 'Available',
            icon: iconBase + '/taxi1.svg'
        },
        ontrip: {
            name: 'OnTrip',
            icon: iconBase + '/taxi.svg'
        },
       
    };

    var requestRef = firebase.database().ref('drivers/'+driverId);

    requestRef.on('value', async function(snapshot) {
        var tripData = snapshot.val();

        
            await loadDriverIcons(tripData);
           
    });

    function loadDriverIcons(val){
        Object.entries(val).forEach(([key, valu]) => {

            // var iconImg = icons['ontrip'].icon;

         var iconImg = '';
               
                    if (valu.is_available == true && valu.is_active == 1) {
                        iconImg = icons['available'].icon;
                    } else if (valu.is_active == 1 && valu.is_available == false) {
                        iconImg = icons['ontrip'].icon;
                    } else {
                        iconImg = icons['available'].icon;
                        //@TODO
                    }

        var carIcon = new google.maps.Marker({
            position: new google.maps.LatLng(val.l[0], val.l[1]),
            icon: {
                url: iconImg,
                scaledSize: new google.maps.Size(40, 40)
            },
            map: map
        });

        marker.push(carIcon);
        carIcon.setMap(map);

        setTimeout(() => {
            rotateMarker(iconImg, val.bearing);
        }, 3000);
   
 });
    }


   

    // To rotate truck based on driver bearing
    function rotateMarker(carimage, bearing) {
        if(document.querySelectorAll(`img[src='${carimage}']`).length > 0)
             var bearing = Math.floor((Math.random() * 180) + 0);
            document.querySelectorAll(`img[src='${carimage}']`)[0].style.transform = 'rotate(' + bearing + 'deg)';
    }

    // Delete truck icons once map reloads
    function deleteAllMarkers() {
        for (var i = 0; i < marker.length; i++) {
            marker[i].setMap(null);
        }
    }

    // Draw path from pickup to drop - map api
    function calcRoute(pickup, drop) {
        var request = {
            origin: pickup,
            destination: drop,
            travelMode: google.maps.TravelMode['DRIVING']
        };

        directionsService.route(request, function(response, status) {
            if (status == 'OK') {
                directionsRenderer.setDirections(response);
                var leg = response.routes[0].legs[0];
                makeMarker(leg.start_location, icons['pickup'].icon, icons['pickup'].name, map);
                makeMarker(leg.end_location, icons['drop'].icon, icons['drop'].name, map);
            }
        });
    }

    function makeMarker(position, icon, title, map) {
        new google.maps.Marker({
            position: position,
            map: map,
            icon: icon,
            title: title
        });
    }
</script>
@endsection




