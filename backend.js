var express = require('express');//
var url = require("url");//
var https = require("https");//
var http = require("http");//
var qs = require("querystring");
var parsingXML = require("xml2js").parseString;//
var sleep = require("sleep");//


var app = express();
var key = "D0O6J5ZT9KN0UPHI";
function getData(res,sym,id) {
    switch (id){
        case "xxx":
            console.log("1");
            var stock = '';
            http.get("http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input="+sym,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                    //console.log(typeof datas);
                });
                resource.on("end",function () {

                    res.send(stock);
                    res.end();

                });
            });
            break;

        case "auto":
            sleep.msleep(200);
            var stockData = {
                function: "TIME_SERIES_DAILY",
                symbol: sym,
                // outputsize: "full",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("12");
                    // console.log(stock);
                    res.send(stock);
                    res.end();
                });
            });
            break;
        case "price":
            sleep.msleep(200);
            var stockData = {
                function: "TIME_SERIES_DAILY",
                symbol: sym,
                outputsize: "full",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("1");
                    // console.log(stock);
                    res.send(stock);
                    res.end();
                });
            });
            break;
        case "sma":
            sleep.msleep(200);
            var stockData = {
                function: "SMA",
                symbol: sym,
                interval: "daily",
                time_period: 10,
                series_type: "close",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("2");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "ema":
            sleep.msleep(200);
            var stockData = {
                function: "EMA",
                symbol: sym,
                interval: "daily",
                time_period: 10,
                series_type: "close",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("3");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "stoch":
            sleep.msleep(200);
            var stockData = {
                function: "STOCH",
                symbol: sym,
                interval: "daily",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("4");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "rsi":
            sleep.msleep(200);
            var stockData = {
                function: "RSI",
                symbol: sym,
                interval: "daily",
                time_period: 10,
                series_type: "close",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("5");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "adx":
            sleep.msleep(200);
            var stockData = {
                function: "ADX",
                symbol: sym,
                interval: "daily",
                time_period: 10,
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("6");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "cci":
            sleep.msleep(200);
            var stockData = {
                function: "CCI",
                symbol: sym,
                interval: "daily",
                time_period: 10,
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("7");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "bbands":
            sleep.msleep(200);
            var stockData = {
                function: "BBANDS",
                symbol: sym,
                interval: "daily",
                time_period: 5,
                series_type: "close",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("8");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "macd":
            sleep.msleep(200);
            var stockData = {
                function: "MACD",
                symbol: sym,
                interval: "daily",
                series_type: "close",
                apikey: key
            };
            var content = qs.stringify(stockData);
            var options = {
                host: "www.alphavantage.co",
                path: "/query?" + content,
                method: "GET"
            };
            var stock = "";
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {
                    console.log("9");
                    res.send(stock);
                    res.end();
                });

            });
            break;

        case "news":

            var stock = "";
            var options = {
                host: "seekingalpha.com",
                path: "/api/sa/combined/" + sym.toUpperCase() + ".xml",
                method: "GET"
            };
            // console.log(sym.toUpperCase());
            https.get(options,function (resource) {
                resource.on("data",function (datas) {
                    stock += datas ;
                });
                resource.on("end",function () {

                    parsingXML(stock,function (err,result) {

                        console.log("10");
                        // console.log( result.toString());
                        //console.log(result);
                        res.send(result);
                        res.end();
                    })
                    // res.send(stock);
                    // res.end();
                });

            });
            break;

    }
}

app.use(function(req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type, Authorization');
    next();
});
app.get("/",function (req,res) {

    //res.setHeader(200,{'Content-Type':'text/plain'});

    var paras = url.parse(req.url, true).query;
    getData(res, paras.symbol, paras.indicator);


});
app.listen(process.env.PORT);