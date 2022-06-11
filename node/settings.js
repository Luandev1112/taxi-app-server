var Redis = require('ioredis');
var redis = new Redis();

module.exports = {

    "config": {},

    "kickStartSettings": function() {

        this.getSettingValues();
    },

    "getSettingValues": function() {
        var obj = this;
        redis.get('settings', function(err, settings) {
            obj.config = JSON.parse(settings);
        });
    }
};