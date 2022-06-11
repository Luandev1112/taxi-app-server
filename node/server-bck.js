var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redisAdapter = require('socket.io-redis')
var redis = new Redis();
var axios = require('axios');

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
const privkey = env.parsed.SOCKET_SSL_KEY_PATH;
const fullchain = env.parsed.SOCKET_SSL_CERT_PATH;
const socket_https = env.parsed.SOCKET_HTTPS;

settings.kickStartSettings();
// redis.psubscribe('*', (err, count) => {});

// redis.on('pmessage', (subscribed, channel, data) => {
//     console.log('Channel: ' + channel);
//     console.log('Subscribed To: ' + subscribed);
//     console.log('Message Recieved: ' + data);
//     console.log('###############################################:');
//     data = JSON.parse(data);
//     io.emit(channel + ':' + data.event, data.data);
// });

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
        if (data_object.user_type == "user") {
            if (user_socket["user" + data_object.id]) {
                user_socket["user" + data_object.id].emit(data_object.event, data_object.message);

            } else {
                console.log('nooooo');
            }
        }
        if (data_object.user_type == "driver") {

            if (driver_socket["driver" + data_object.id]) {

                driver_socket["driver" + data_object.id].emit(data_object.event, data_object.message);


            } else {
                console.log("no d");
            }
        }
    });
});

var dispatcher_node = io.of('/dispatcher');
dispatcher_node.on('connection', function(socket) {

    settings.kickStartSettings();

    socket.on('disconnect', function(message) {
        dispatcher_socket["dispatcher" + socket.serverId] = '';
        dispatcher_node.adapter.clientRooms(socket.id, function(err, rooms) {
            if (rooms != null) {
                dispatcher_node.adapter.remoteLeave(socket.id, roomName, function(err) {
                    if (err) { /* unknown id */ }

                });
            }
        });

    });

    var response;
    // types
    socket.on('dispatcher_vehicle_types', function(message) {
        var jsonIobj = message;
        jsonIobj.settings = settings;
        // Select zone along with the coordinates
        database.select(query.select_zone_query(jsonIobj), function(result) {
            if (result.length != 0) {
                var roomName = "room" + result[0].id;
                dispatcher_node.adapter.clientRooms(socket.id, function(err, rooms) {
                    if (rooms != null) {
                        rooms.forEach(function(element) {
                            dispatcher_node.adapter.remoteLeave(socket.id, roomName, function(err) {
                                if (err) { /* unknown id */ }
                            });
                        });
                    }
                    dispatcher_node.adapter.remoteJoin(socket.id, roomName, function(err) {
                        if (err) { /* unknown id */ }
                    });
                });

                // Select near by driver's types
                database.select(query.get_zone_types(result[0].id, jsonIobj), function(result) {
                    var des = "";
                    result.forEach(function(item) {
                        if (item.coordinate != null) {
                            des += item.coordinate;
                            des += "|";
                        }

                    });
                    var dur_status = true;
                    callback = function(response) {
                        var dur_status = true;
                        var response = {};
                        response.types = [];
                        var i = 0;
                        var j = 0;
                        var typeDuration = [];
                        database.select(query.get_drivers_near_location(jsonIobj), function(drivers) {
                            var driver_list = [];
                            result.forEach(function(item) {
                                var duration = '--';
                                var d = 1;
                                driver_list = [];
                                typeDuration[item.type_id] = [];
                                drivers.forEach(function(driver) {
                                    if (item.type_id == driver.vehicle_type) {
                                        data = {};
                                        data.id = driver.id;
                                        data.latitude = driver.latitude;
                                        data.longitude = driver.longitude;
                                        data.type = driver.vehicle_type;
                                        data.bearing = driver.bearing;
                                        data.cur_zone = driver.current_zone;

                                        if (item.type_id == driver.vehicle_type) {
                                            driver_list.push(data);
                                        }
                                        d++;
                                    }
                                });
                                var payment;

                                response["default_selected_type"] = item.default_selected_type;
                                response['types'][i] = {};
                                response['types'][i]["zone_type_id"] = item.id;
                                response['types'][i]["type_id"] = item.type_id;
                                response['types'][i]["name"] = item.name;
                                response['types'][i]["icon"] = item.icon;
                                response['types'][i]["is_accept_share_ride"] = item.is_accept_share_ride;
                                response['types'][i]["payment_type"] = item.payment_type;
                                response['types'][i]['drivers'] = driver_list;
                                if (item.coordinate != null) {
                                    j++;
                                }
                                i++;
                            });

                            Object.keys(typeDuration).forEach(function(key) {

                            });
                            response['success'] = true;
                            console.log(response);
                            socket.emit('dispatcher_vehicle_types', response);

                        });
                    }
                    callback();
                });
            } else {
                var keys = Object.keys(socket.adapter.rooms);
                keys.forEach(function(item) {
                    if (item != socket.id) {
                        socket.leave(item);
                    }
                });
                var response = {};
                response.success = false;
                response.error_code = "800";
                response.error_message = "No Service For This location";
                socket.emit('dispatcher_vehicle_types', response);
            }
        });
    });

});


var mobile_user = io.of('/user');
mobile_user.on('connection', function(socket) {
    settings.kickStartSettings();

    console.log("connected");
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

    socket.on('user_vehicle_types', function(message) {
        // console.log("types called");
        var jsonIobj = JSON.parse(message);
        // console.log(jsonIobj);
        jsonIobj.settings = settings;
        // Select zone along with the coordinates
        database.select(query.select_zone_query(jsonIobj), function(result) {
            if (result.length != 0) {
                var roomName = "room" + result[0].id;
                mobile_user.adapter.clientRooms(socket.id, function(err, rooms) {
                    if (rooms != null) {
                        rooms.forEach(function(element) {
                            mobile_user.adapter.remoteLeave(socket.id, roomName, function(err) {
                                if (err) { /* unknown id */ }
                            });
                        });
                    }
                    mobile_user.adapter.remoteJoin(socket.id, roomName, function(err) {
                        if (err) { /* unknown id */ }
                    });
                });

                // Select near by driver's types
                database.select(query.get_zone_types(result[0].id, jsonIobj), function(result) {
                    var des = "";
                    result.forEach(function(item) {
                        if (item.coordinate != null) {
                            des += item.coordinate;
                            des += "|";
                        }

                    });
                    var dur_status = true;
                    callback = function(response) {
                        var dur_status = true;
                        var response = {};
                        response.types = [];
                        var i = 0;
                        var j = 0;
                        var typeDuration = [];
                        database.select(query.get_drivers_near_location(jsonIobj), function(drivers) {
                            var driver_list = [];
                            result.forEach(function(item) {
                                var duration = '--';
                                var d = 1;
                                driver_list = [];
                                typeDuration[item.type_id] = [];
                                drivers.forEach(function(driver) {
                                    if (item.type_id == driver.vehicle_type) {
                                        var hours = (driver.distance / 10);
                                        var time = (hours * 60);
                                        var temp = Math.round(time);
                                        if (d == 1) {
                                            duration = temp;
                                        }
                                        if (duration > temp) {
                                            duration = temp;
                                        }
                                        data = {};
                                        data.id = driver.id;
                                        data.latitude = driver.latitude;
                                        data.longitude = driver.longitude;
                                        data.type = driver.vehicle_type;
                                        data.bearing = driver.bearing;
                                        data.cur_zone = driver.current_zone;
                                        data.duration = duration;

                                        if (item.type_id == driver.vehicle_type) {
                                            var countType = typeDuration[item.type_id].length;
                                            typeDuration[item.type_id][countType] = duration;
                                            driver_list.push(data);
                                        }
                                        d++;
                                    }
                                });
                                var payment;
                                var newDuration = (duration == 0 && duration != '--' ? 1 + 'mins' : duration);
                                if (newDuration != '--') {

                                    if (newDuration != '1mins') {

                                        newDuration = duration + 'mins';
                                    }
                                }
                                response["default_selected_type"] = item.default_selected_type;
                                response['types'][i] = {};
                                response['types'][i]["zone_type_id"] = item.id;
                                response['types'][i]["type_id"] = item.type_id;
                                response['types'][i]["name"] = item.name;
                                response['types'][i]["icon"] = item.icon;
                                response['types'][i]["is_accept_share_ride"] = item.is_accept_share_ride;
                                response['types'][i]["payment_type"] = item.payment_type;
                                response['types'][i]["duration"] = newDuration;
                                response['types'][i]['drivers'] = driver_list;
                                if (item.coordinate != null) {
                                    j++;
                                }
                                i++;
                            });

                            Object.keys(typeDuration).forEach(function(key) {

                            });
                            response['success'] = true;
                            console.log(response);
                            socket.emit('user_vehicle_types', response);

                        });
                    }
                    callback();
                });
            } else {
                var keys = Object.keys(socket.adapter.rooms);
                keys.forEach(function(item) {
                    if (item != socket.id) {
                        socket.leave(item);
                    }
                });
                var response = {};
                response.success = false;
                response.error_code = "800";
                response.error_message = "No Service For This location";
                socket.emit('user_vehicle_types', response);
            }
        });
    });

    // Get near drivers new observer
    socket.on('get_near_drivers', function(message) {
        // var jsonIobj = JSON.parse(message);
        var jsonIobj = message;
        // console.log(jsonIobj);
        jsonIobj.settings = settings;
        // Select zone along with the coordinates
        database.select(query.select_zone_query(jsonIobj), function(result) {
            if (result.length != 0) {
                var roomName = "room" + result[0].id;
                mobile_user.adapter.clientRooms(socket.id, function(err, rooms) {
                    if (rooms != null) {
                        rooms.forEach(function(element) {
                            mobile_user.adapter.remoteLeave(socket.id, roomName, function(err) {
                                if (err) { /* unknown id */ }
                            });
                        });
                    }
                    mobile_user.adapter.remoteJoin(socket.id, roomName, function(err) {
                        if (err) { /* unknown id */ }
                    });
                });

                database.select(query.get_drivers_near_location(jsonIobj), function(drivers) {
                    driver_list = [];
                    var duration = '--';
                    var d = 1;
                    drivers.forEach(function(driver) {
                        var hours = (driver.distance / 10);
                        var time = (hours * 60);
                        var temp = Math.round(time);
                        if (d == 1) {
                            duration = temp;
                        }
                        if (duration > temp) {
                            duration = temp;
                        }
                        data = {};
                        data.id = driver.id;
                        data.latitude = driver.latitude;
                        data.longitude = driver.longitude;
                        data.type = driver.vehicle_type;
                        data.bearing = driver.bearing;
                        data.cur_zone = driver.current_zone;
                        data.duration = duration;
                        driver_list.push(data);
                        d++;
                    });
                    var response = {};
                    response.success = true;
                    response.drivers = driver_list;
                    socket.emit('get_near_drivers', response);

                });
            } else {
                var keys = Object.keys(socket.adapter.rooms);
                keys.forEach(function(item) {
                    if (item != socket.id) {
                        socket.leave(item);
                    }
                });
                var response = {};
                response.success = false;
                response.error_code = "800";
                response.error_message = "No Service For This location";
                socket.emit('get_near_drivers', response);
            }
        });
    });
    // Get near drivers new observer end here
});

var driver_socket = io.of('/driver');

driver_socket.on('connection', function(socket) {

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
    // Location updates
    socket.on("driver_location", function(message) {

        var driver_location_object = message;

        if (typeof driver_location_object == 'string') {

            var driver_location_object = JSON.parse(message);
        }

        database.update(query.driver_location_update(driver_location_object), function() {});

        database.select(query.get_driver_location(driver_location_object), function(result) {
            if (result.length != 0) {
                var response = {};
                response.success = true;
                response.success_message = "location_updated_successfully";
                response.id = result[0].id;
                response.lat = result[0].latitude;
                response.lng = result[0].longitude;
                socket.emit('driver_location_updated', response);
            }
        });
    });

});
