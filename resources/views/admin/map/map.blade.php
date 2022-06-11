@extends('admin.layouts.app')

@section('title', 'Map View')

@section('content')
<style>
    #map {
        height: 70vh;
        margin: 15px;
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
</style>
<!-- Start Page content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">

                <div class="box-header with-border">
                    <h3>{{ $page }}</h3>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div id="map"></div>

                        <div id="legend"><h3>@lang('view_pages.legend')</h3></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{get_settings('google_map_key')}}&libraries=visualization"></script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.19.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.19.0/firebase-database.js"></script>
<!-- TODO: Add SDKs for Firebase products that you want to use https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.19.0/firebase-analytics.js"></script>

<script type="text/javascript">
    var heatmapData = [];
    var pickLat = [];
    var pickLng = [];
     var default_lat = '{{$default_lat}}';
    var default_lng = '{{$default_lng}}';
     var company_key='{{auth()->user()->company_key}}';
    var driverLat,driverLng,bearing,type;
    var marker = [];

 
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

    var tripRef = firebase.database().ref('drivers');

    tripRef.on('value', async function(snapshot) {
        var data = snapshot.val();

        await loadDriverIcons(data);
    });

    var map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(default_lat, default_lng),
            zoom: 5,
            mapTypeId: 'roadmap'
        });

        var iconBase = '{{ asset("map/icon/") }}';
        var icons = {
          available: {
            name: 'Available',
            icon: iconBase + '/driver_available.png'
          },
          ontrip: {
            name: 'OnTrip',
            icon: iconBase + '/driver_on_trip.png'
          },
        
        };

        var legend = document.getElementById('legend');

        for (var key in icons) {
            var type = icons[key];
            var name = type.name;
            var icon = type.icon;
            var div = document.createElement('div');
            div.innerHTML = '<img src="' + icon + '"> ' + name;
            legend.appendChild(div);
        }

        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

    function loadDriverIcons(data){
        deleteAllMarkers();
        
        Object.entries(data).forEach(([key, val]) => {
            if( typeof val.l !=  'undefined'  ) {
            var contentString = `<div class="p-2">
                                    <h6><i class="fa fa-id-badge"></i> : ${val.name ?? '-' } </h6>
                                    <h6><i class="fa fa-phone-square"></i> : ${val.mobile ?? '-'} </h6>
                                    <h6><i class="fa fa-id-card"></i> : ${val.vehicle_number ?? '-'} </h6>
                                    <h6><i class="fa fa-truck"></i> : ${val.vehicle_type_name ?? '-'} </h6>
                                </div>`;

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            var iconImg = '';
            // if(val.company_key==company_key){

                if(val.is_available == true && val.is_active==true){
                    iconImg = icons['available'].icon;
                }else if(val.is_active==true && val.is_available==false){
                    iconImg = icons['ontrip'].icon;
                }else{
                    iconImg = icons['available'].icon;
                }
            // }
            

            var carIcon = new google.maps.Marker({
                position: new google.maps.LatLng(val.l[0],val.l[1]),
                icon: iconImg,
                map: map
            });

            carIcon.addListener('click', function() {
                infowindow.open(map, carIcon);
            });

            marker.push(carIcon);
            carIcon.setMap(map);
            // }

            // marker.addListener('click', function() {
            //     infowindow.open(map, marker);
            // });
            }
        });
    }

    // Delete truck icons once map reloads
    function deleteAllMarkers() {
        for(var i=0;i<marker.length;i++){
            marker[i].setMap(null);
        }
    }
</script>

@endsection

