
const mysql = require('mysql');
const env =  require('dotenv').config({path: '../.env'});



var connection = mysql.createConnection({
    host     : env.parsed.DB_HOST,
    user     : env.parsed.DB_USERNAME,
    password : env.parsed.DB_PASSWORD,
    database : env.parsed.DB_DATABASE,
    port: env.parsed.DB_PORT
});



module.exports = {
    select: function(query,func)
    {
        var obj=this;
        connection.query(query, function (error, results, fields) {
            if (error) throw obj.error(error);
            func(results);
        });


    },
    responseQuery: function(query,func)
    {
        var obj=this;
        connection.query(query, function (error, results, fields) {
            if (error) throw obj.error(error);
            console.log(results);
        });
    },
    update: function(query,func)
    {
        var obj=this;
        connection.query(query, function (error, results, fields) {
            if (error) throw obj.error(error);
            console.log(results);
        });
    },
    error:function(error)
    {
        console.log(error);
    }
}