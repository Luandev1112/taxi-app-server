var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redisAdapter = require('socket.io-redis')
var redis = new Redis();
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

Firebase.initializeApp({

    databaseURL: "https://cabeezie.firebaseio.com/",

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

                geoQuery.on('ready',function(){

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
                geoQuery.on("key_entered", function(key, location) {
                    driversRef.child(key).on('value', function(snap) {
                        let driver = snap.val();

                        var date = new Date();
                        var timestamp = date.getTime();
                        var conditional_timestamp = new Date(timestamp - 15 * 60000);

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

app.get('/:lat/:lng', function(req, res) {
    // console.log("yess");
    return queryGeoLocationForOfflineDrivers(req, res);
});

// default route
app.get('/', function(req, res) {
    return res.send({ success: true, message: 'hello' })
});
app.get('/:lat/:lng/:vehicle_type', function(req, res) {
    console.log("yess");
    return queryGeoLocation(req, res);
});


const database = require('./globalmysql');
const query = require('./sqlquery');
const format = require('response-format');
const settings = require('./settings');


var user_socket = [];
var driver_socket = [];
var dispatcher_socket = [];
const env = require('dotenv').config({
    path: '../.env'
});

const socket_port = env.parsed.SOCKET_PORT;
const node_app_port = env.parsed.NODE_APP_PORT;
const privkey = env.parsed.SOCKET_SSL_KEY_PATH;
const fullchain = env.parsed.SOCKET_SSL_CERT_PATH;
const socket_https = env.parsed.SOCKET_HTTPS;

app.listen(node_app_port, function() {
    console.log('Node app is running on port ' + node_app_port);
});


settings.kickStartSettings();

if (socket_https == 'yes') {
    // console.log("Secure connection");
    fs = require("fs");

    var options = {
        key: fs.readFileSync(privkey, 'utf8'),
        cert: fs.readFileSync(fullchain, 'utf8')
    };
    server = require('https').createServer(options);

    server.listen(socket_port);

} else {
    server.listen(socket_port);
}

io.adapter(redisAdapter({ host: 'localhost', port: 6379 }));


var backend_user = io.of('/php/user');

backend_user.on('connection', function(socket) {

    socket.on('transfer_msg', function(message) {

        var data_object = message;

        console.log("from-backend" + "event-name-is" + data_object.event);

        if (data_object.user_type == "user") {
            if (user_socket["user" + data_object.id]) {

                console.log("user-connected" + data_object.id + "event-name-is" + data_object.event);

                user_socket["user" + data_object.id].emit(data_object.event, data_object.message);

            } else {
                console.log("user-not-connected" + data_object.id);

            }
        }
        if (data_object.user_type == "driver") {

            if (driver_socket["driver" + data_object.id]) {
                console.log("driver-connected" + data_object.id + "event-name-is" + data_object.event);
                driver_socket["driver" + data_object.id].emit(data_object.event, data_object.message);
            } else {
                console.log("driver-not-connected" + data_object.id);
            }
        }
    });
});

var mobile_user = io.of('/user');
mobile_user.on('connection', function(socket) {
    settings.kickStartSettings();
    // console.log("connected");
    socket.on('disconnect', function(message) {
        user_socket["user" + socket.serverId] = '';
        mobile_user.adapter.clientRooms(socket.id, function(err, rooms) {
            if (rooms != null) {
                mobile_user.adapter.remoteLeave(socket.id, roomName, function(err) {
                    if (err) { /* unknown id */ }

                });
            }
        });

    });
    // Start connecting by the user
    socket.on('start_connect', function(message) {
        var JsonInObj = JSON.parse(message);
        socket.serverId = JsonInObj.id;
        user_socket["user" + JsonInObj.id] = socket;
    });

});

var mobile_driver = io.of('/driver');

mobile_driver.on('connection', function(socket) {

    var lat_Obj = {};
    var lat_array = [];

    socket.on('start_connect', function(message) {
        var driver_object = JSON.parse(message);
        if ((driver_object) != undefined && driver_object != '' && driver_object.id != undefined && driver_object.id != '') {
            socket.serverId = driver_object.id;
            driver_socket["driver" + driver_object.id] = socket;
        }
    });
    // Disconnect from socket
    socket.on('disconnect', function(message) {
        if (socket.serverId != undefined) {
            driver_socket["driver" + socket.serverId] = '';
        }
    });
});
