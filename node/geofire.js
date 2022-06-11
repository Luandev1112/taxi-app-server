var axios = require('axios');

var express = require('express');
var app = express();
var bodyParser = require('body-parser');
var Firebase = require('firebase');
var GeoFire = require('geofire');

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({
    extended: true
}));


const env = require('dotenv').config({
    path: '../.env'
});

const node_app_port = env.parsed.NODE_APP_PORT;

const firebase_db_url = env.parsed.FIREBASE_DATABASE_URL;

Firebase.initializeApp({

    databaseURL: firebase_db_url,

    serviceAccount: './firebase.json', //this is file that I downloaded from Firebase Console

});


var fire_db = Firebase.database();
var driversRef = Firebase.database().ref().child('drivers');
// Create a GeoFire index
var geoFire = new GeoFire.GeoFire(driversRef);



function queryGeoLocation(req, res) {
    try {
        const lat = parseFloat(req.params.lat);
        const long = parseFloat(req.params.lng);
        const vehicle_type = req.params.vehicle_type;
        const radius = 10;
        var fire_drivers = [];

        let geoQuery = geoFire.query({ center: [lat, long], radius: radius });

        getGeoData = function(geoQuery) {
            return new Promise(function(resolve, reject) {

                geoQuery.on('ready',function(key,location){
                    console.log("ready",key);

                    resolve(fire_drivers);
                    geoQuery.cancel();
                });
                
                geoQuery.on("key_entered", function(key, location) {
                    driversRef.child(key).on('value', function(snap) {
                        let driver = snap.val();

                        var date = new Date();
                        var timestamp = date.getTime();
                        var conditional_timestamp = new Date(timestamp - 5 * 60000);

                        if (conditional_timestamp < driver.updated_at) {
                            if (driver.is_active == 1 & driver.is_available == 1 & driver.vehicle_type == vehicle_type) {
                                fire_drivers.push(driver);
                            }
                        }

                        resolve(fire_drivers);
                    });
                });

            });
        };
        //getGeoData(geoQuery).then(checkIfDateIsNewer).then(function(data) {
        getGeoData(geoQuery).then(function(data) {
            res.send({ success: true, message: 'success', data: data });
            // console.log(data);
        }).catch((err) => {
            res.status(500).send("Error: " + err);
        });
    } catch (err) {
        res.status(500).send("Error: " + err);
    }
}

function queryGeoLocationForOfflineDrivers(req, res) {
    try {
        const lat = parseFloat(req.params.lat);
        const long = parseFloat(req.params.lng);
        const radius = 100000;
        var fire_drivers = [];

        let geoQuery = geoFire.query({ center: [lat, long], radius: radius });

        getGeoData = function(geoQuery) {
            return new Promise(function(resolve, reject) {

                geoQuery.on('ready',function(key,location){
                    console.log("ready",key);

                    resolve(fire_drivers);
                    geoQuery.cancel();
                });
                
                geoQuery.on("key_entered", function(key, location) {
                    driversRef.child(key).on('value', function(snap) {
                        let driver = snap.val();

                        var date = new Date();
                        var timestamp = date.getTime();
                        var conditional_timestamp = new Date(timestamp - 5 * 60000);

                        if (conditional_timestamp > driver.updated_at) {
                            fire_drivers.push(driver);
                        }

                        resolve(fire_drivers);
                    });
                });

            });
        };
        //getGeoData(geoQuery).then(checkIfDateIsNewer).then(function(data) {
        getGeoData(geoQuery).then(function(data) {
            res.send({ success: true, message: 'success', data: data });
            // console.log(data);
        }).catch((err) => {
            res.status(500).send("Error: " + err);
        });
    } catch (err) {
        res.status(500).send("Error: " + err);
    }
}
// default route
app.get('/', function(req, res) {
    return res.send({ success: true, message: 'hello' })
});
app.get('/:lat/:lng/:vehicle_type', function(req, res) {
    console.log("yess");
    return queryGeoLocation(req, res);
});

app.get('/:lat/:lng', function(req, res) {
    console.log(res);
    return queryGeoLocationForOfflineDrivers(req, res);
});

app.listen(node_app_port, function() {
    console.log('Node app is running on port ' + node_app_port);
});
