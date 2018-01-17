<?php
/**
*
*
*
*
* User: KeanuZhao
* Date: 11/10/2017
* Time: 18:43
*
*
*
*/
?>

<html>
<head>
    <title>HW6</title>
    <script>

        function check(con){ // 判断用户到底写没写股票代码
            if(con == ""){ //  居然没写!
                alert("Please enter a symbol");
                return false;
            }
            else{ //看来是写了
                var s = document.getElementById("symbol");
                s.value = con;
                return true;
            }

        }

        function setSymbol(symbol){ //当点击submit后在文本框保留输入的字符
            var s = document.getElementById("symbol");
            s.value = symbol;
        }

        function showNews(){   // 显示最下方的新闻框 如果没有新闻的话就不显示
            var word = document.getElementById('newsword');
            var img = document.getElementById('newsbutton');
            var div = document.getElementById('newdiv');
            if(word.innerHTML == "click to show stock news"){ // 点击展开
                img.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png";
                word.innerHTML = "click to hide stock news";
                div.style.display = "";
            }
            else{ //再点一下关上
                img.src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png";
                word.innerHTML = "click to show stock news";
                div.style.display = "none";
            }
        }

        function clearall(){ //点击clear后扔掉所有内容
            var chart = document.getElementById('chart');
            var symbol = document.getElementById('symbol');
            var table = document.getElementById('table');
            var news = document.getElementById('news');
            var newdiv = document.getElementById('newdiv');
            chart.innerHTML = "";
            symbol.value = "";
            table.innerHTML = "";
            news.innerHTML = "";

            newdiv.innerHTML = "";
        }
    </script>
    <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
    <script>
    </script>
    <style>
     /*  为了美观    */
        div.form {
            background-color: #F2F2F2;
            text-align: center;
            width: 400px;
            height: 150px;
            border-style: ridge;
            border-color: #DDDDDD;
            margin: auto;
        }
        div.word {
            text-align: left;
            width: 48%;
            height: 20%;
            margin-top: 10px;
            padding-left: 5px;
            float: left;

        }
        h1 {
            font-family: Times;
            margin: 2px;
            font-style: italic;
            vertical-align: bottom;
        }

        body{
            text-align: center;
        }
        th.front{
            background-color: #F2F2F2;
            text-align: left;
            font-weight: bold;
            width: 30%;
            height: 25px;
        }
        th.back {
            background-color: #FAFAFA;
            text-align: center;
            font-weight: normal;
        }

    </style>
</head>

<body>

<div class="form">
    <h1>Stock Search</h1>
    <div style="height: 1px;background-color: #B7B7B7;"></div>

    <div>
        <div class="word">
            Enter Stock Ticker Symbol:*
            <br>
            <p></p>
        </div>
        <div class="word">
            <form name="stock" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
                <input type="text" id="symbol" name="symbol" value=""><br>
                <input style="margin-top: 3px;" type="submit" name="Search" value="search" onclick="return check(this.form.symbol.value)">
                <input style="margin-top: 3px;" type="reset" name="Clear" value="clear" onclick="clearall()">
            </form>
        </div>
        <div style="clear:both;height:0px;"></div>

    </div>
    <div class="word">
        <p>* - <i>Mandatory fields.</i></p>
    </div>

</div>


<?php
    function is_json($str){ //判断返回的是否是有效的json文件
        json_decode($str);
        return json_last_error() == JSON_ERROR_NONE;
    }

    $keys = null;
    $flag = false;
    if(isset($_GET["Search"])) { // 点击submit

        $symbol = $_GET["symbol"];
        $queryJSON = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$symbol.
            "&outputsize=full&apikey=D0O6J5ZT9KN0UPHI";  //开始向服务器要股票的各种内容
        try{
            $JSONcont = file_get_contents($queryJSON);
            $flag = is_json($JSONcont);


        }
        catch (Exception $e){

        }

        ?>
        <script>
            setSymbol(<?php echo json_encode($symbol);?>);
        </script>
        <?php
        echo "<div id='table' style='padding-top: 10px'>";
        echo "<table border='2' style='border-style: groove;border-color: #DDDDDD;border-collapse: collapse;margin: auto; width: 75%;'>";
        if ($flag) {
            $json_obj = json_decode($JSONcont, true);

            if (!isset($json_obj["Meta Data"])) {

                echo "<tr>";
                echo "<th class='front'>" . "Error" . "</th>";
                echo "<th class='back'>" . "Error: No record has been found, please enter a valid symbol." . "</th>";
                echo "<tr>";
            }
            else {
                // 这些是新闻的内容 将XML转为JSON
                $queryXML = "https://seekingalpha.com/api/sa/combined/".$symbol.".xml";
                $state = get_headers($queryXML);
                $xml_flag = false;
                //转完了！


                //开始徒手画表格
                echo "<tr>";
                echo "<th class='front'>" . "Stock Ticker Symbol" . "</th>";
                echo "<th class='back'>" . $json_obj["Meta Data"]["2. Symbol"] . "</th>";
                echo "<tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Close" . "</th>";
                $keys = array_keys($json_obj["Time Series (Daily)"]);
                echo "<th class='back'>" . $json_obj["Time Series (Daily)"][$keys[0]]["4. close"] . "</th>";
                echo "<tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Open" . "</th>";
                echo "<th class='back'>" . $json_obj["Time Series (Daily)"][$keys[0]]["1. open"] . "</th>";
                echo "<tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Previous Close" . "</th>";
                echo "<th class='back'>" . $json_obj["Time Series (Daily)"][$keys[1]]["4. close"] . "</th>";
                echo "<tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Change" . "</th>";
                $dif = floatval($json_obj["Time Series (Daily)"][$keys[0]]["4. close"]) - floatval($json_obj["Time Series (Daily)"][$keys[1]]["4. close"]);

                $img;
                if ($dif < 0) {
                    $img = "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width='20px' height='20px'/>";
                } else {
                    $img = "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width='20px' height='20px'/>";
                }
                echo "<th class='back'>" . number_format($dif, 2) . " " . $img . "</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Change Percent" . "</th>";
                $per = $dif / floatval($json_obj["Time Series (Daily)"][$keys[1]]["4. close"]);
                echo "<th class='back'>" . number_format($per * 100, 2) . "% " . $img . "</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Day's Range" . "</th>";
                echo "<th class='back'>" . $json_obj["Time Series (Daily)"][$keys[0]]["3. low"] . " - " . $json_obj["Time Series (Daily)"][$keys[0]]["2. high"] . "</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Volume" . "</th>";
                echo "<th class='back'>" . $json_obj["Time Series (Daily)"][$keys[0]]["3. low"] . " - " . $json_obj["Time Series (Daily)"][$keys[0]]["5. volume"] . "</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Timestamp" . "</th>";
                echo "<th class='back'>" . substr($json_obj["Meta Data"]["3. Last Refreshed"],0,10) . "</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<th class='front'>" . "Indicators" . "</th>";
                echo "<th class='back'>";
                echo "<a id='price' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>Price</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='sma' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>SMA</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='ema' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>EMA</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='stoch' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>STOCH</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='rsi' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>RSI</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='adx' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>ADX</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='cci' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>CCI</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='bbands' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>BBANDS</a>";
                echo " &nbsp &nbsp &nbsp";
                echo "<a id='macd' onclick='changeChart(this)' style='cursor: pointer; cursor: hand; color: #3131FF'>MACD</a>";
                echo "</th>";
                echo "</tr>";
                //终于画完表格


                //又该开始画图像了
                echo "</table>";
                echo "</div>";
                echo "<div id='chart' style='padding-top: 10px;'>";
                echo "<div id='container' style='width:75%;height:500px;margin: auto;display: none;' ></div>";
                echo "<div id='smachart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='emachart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='stochchart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='rsichart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='adxchart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='ccichart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='bbandschart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "<div id='macdchart' style='width:74.7%;height:500px;margin: auto;display: none; '></div>";
                echo "</div>";
                //每次只能显示一个

                //判断股票代码里有没有小写字母 如果有小写字母的话http会跳转
                if(preg_match('/[a-z]+/',$symbol)){  //用户不好好输入 有小写

                    if(strstr($state[26],"HTTP/1.1 404 Not Found")){ //没有新闻
                        $xml_flag = false;
                    }
                    else{
                        $xml_flag = true;
                    }
                }
                else{ //全是大写字母  perfect!
                    if(strstr($state[0],"HTTP/1.1 404 Not Found")){
                        $xml_flag = false;
                    }
                    else{
                        $xml_flag = true;
                    }
                }
                if($xml_flag){  //有新闻 显示最近的五个

                    $XML = simplexml_load_file($queryXML);
                    $XMLcont = json_encode($XML);
                    $xml_obj = json_decode($XMLcont,true);
                    if(!isset($xml_obj["channel"]["item"])){

                    }
                    else{

                        //新闻的框
                        echo "<div id='news' style='padding-top: 10px' onclick='showNews()'>";
                        echo "<span id='newsword' style=\"color: #9A9A9A;cursor: default; \" oncontextmenu=\"return false;\" onselectstart=\"return false\">click to show stock news</span><br>";
                        echo "<img style='cursor: default;' id='newsbutton' src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png' width='40px' height='20px'/>";
                        echo "</div>";

                        echo "<div id='newdiv' style=\"padding-top: 10px;text-align: center;display: none;\" >";
                        echo "<table border='2' style=\"border-style: groove;border-color: #DDDDDD;border-collapse: collapse;width:75%;height:200px; margin: auto;\">";


                        $items = $xml_obj["channel"]["item"];
                        $count = 0;
                        foreach($items as $item){
                            if(strstr($item["link"],"article")){
                                echo "<tr>";
                                echo "<td>";
                                echo "<a style='text-decoration:none;' target='_blank' href=".'"'.$item["link"].'"'.">".$item["title"]. "</a>&nbsp&nbsp&nbsp&nbsp";
                                echo "Publicated Time: " . explode("-",$item["pubDate"])[0];
                                echo "</td>";
                                echo "</tr>";
                                $count++;
                                if($count == 5)
                                    break;
                            }
                        }
                        echo "</table>";
                        echo "</div>";
                    }
                }



                //这三位要从php传到js
                $vols = Array();
                $clos = Array();
                $days = Array();

                if(sizeof($keys) > 121){
                    for ($i = 0; $i < 121; $i++) {
                        $vols[$i] = ($json_obj["Time Series (Daily)"][$keys[$i]]["5. volume"]);
                        $date = explode("-", $keys[$i]);
                        if(strlen($date[2]) > 2){
                            $days[$i] = $date[1] . "/" . substr($date[2],0,2);
                        }
                        else{
                            $days[$i] = $date[1] . "/" . $date[2];
                        }

                        $clos[$i] = $json_obj["Time Series (Daily)"][$keys[$i]]["4. close"];
                    }
                }
                else {
                    for ($i = 0; $i < sizeof($keys); $i++) {
                        $vols[$i] = ($json_obj["Time Series (Daily)"][$keys[$i]]["5. volume"]);
                        $date = explode("-", $keys[$i]);
                        if(strlen($date[2]) > 2){
                            $days[$i] = $date[1] . "/" . substr($date[2],0,2);
                        }
                        else{
                            $days[$i] = $date[1] . "/" . $date[2];
                        }

                        $clos[$i] = $json_obj["Time Series (Daily)"][$keys[$i]]["4. close"];
                    }
                }


                ?>

                <script>
                    //过来了
                    var vols = <?php echo json_encode($vols);?>;
                    var clos = <?php echo json_encode($clos);?>;
                    var dates = <?php echo json_encode($days);?>;
                    var symbol = "<?php echo $symbol;?>";
                    setSymbol(symbol);
                    var date1 = <?php echo json_encode($json_obj["Meta Data"]["3. Last Refreshed"] );?>;

                    var sma = document.getElementById('container');
                    sma.style.display = '';

                    var st = date1.split("-");


                    var res = [];
                    vols.forEach(function (data, index, arr) {
                        res.push(+data);
                    });

                    var list = [];

                    clos.forEach(function (data, index, arr) {
                        list.push(+data);
                    });
                    console.log(list);
                    console.log(res);
                    var minVol = Math.min.apply(null, res);
                    var maxVol = Math.max.apply(null, res);
                    var minClo = Math.min.apply(null, list);
                    var maxClo = Math.max.apply(null, list);
                    //异步加载其他图像
                    function loadXMLHttpRequest() {

                        var xmlhttp = null;
                        if (window.XMLHttpRequest) {
                            xmlhttp = new XMLHttpRequest();
                        }
                        else {
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        return xmlhttp;
                    }

                    function doIt(url,id) {


                        var xmlhttp = loadXMLHttpRequest();

                        if (xmlhttp == null) {
                            alert("Error");
                            return;
                        }

                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4) {
                                if (xmlhttp.status == 200){
                                    var responseText = xmlhttp.responseText;
                                    var json = JSON.parse(responseText);


                                    if(id == "smachart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "";
                                        var subjson = json["Technical Analysis: SMA"];
                                        var count = 0;
                                        var vals = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            vals[count] = parseFloat(subjson[key]["SMA"]);
                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('smachart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Simple Moving Average (SMA)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },
                                            plotOptions:{
                                                series: {
                                                    color: '#FF0000'
                                                }
                                            },
                                            tooltip: {
                                                //pointFormat: symbol + ":{point.y:,..2f}"
                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"SMA"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:json["Meta Data"]["1: Symbol"],
                                                    data:vals.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "emachart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        var subjson = json["Technical Analysis: EMA"];
                                        var count = 0;
                                        var vals = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            vals[count] = parseFloat(subjson[key]["EMA"]);
                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('emachart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Exponential Moving Average (EMA)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },
                                            plotOptions:{
                                                series: {
                                                    color: '#FF0000'
                                                }
                                            },
                                            tooltip: {

                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"EMA"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:json["Meta Data"]["1: Symbol"],
                                                    data:vals.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "stochchart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        var subjson = json["Technical Analysis: STOCH"];
                                        var count = 0;
                                        var slowD = [];
                                        var slowK = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            slowD[count] = parseFloat(subjson[key]["SlowD"]);
                                            slowK[count] = parseFloat(subjson[key]["SlowK"]);
                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('stochchart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Stochastic Oscillator (STOCH)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },

                                            tooltip: {
                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"STOCH"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:symbol + " SlowK",
                                                    data:slowK.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                },
                                                {
                                                    name:symbol + " SlowD",
                                                    data:slowD.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "rsichart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        var subjson = json["Technical Analysis: RSI"];
                                        var count = 0;
                                        var vals = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            vals[count] = parseFloat(subjson[key]["RSI"]);
                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('rsichart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Relative Strength Index (RSI)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },
                                            plotOptions:{
                                                series: {
                                                    color: '#FF0000'
                                                }
                                            },
                                            tooltip: {

                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"RSI"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:json["Meta Data"]["1: Symbol"],
                                                    data:vals.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "adxchart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        var subjson = json["Technical Analysis: ADX"];
                                        var count = 0;
                                        var vals = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            vals[count] = parseFloat(subjson[key]["ADX"]);
                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('adxchart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Average Directional Movement Index (ADX)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },
                                            plotOptions:{
                                                series: {
                                                    color: '#FF0000'
                                                }
                                            },
                                            tooltip: {

                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"ADX"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:json["Meta Data"]["1: Symbol"],
                                                    data:vals.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "ccichart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        var subjson = json["Technical Analysis: CCI"];
                                        var count = 0;
                                        var vals = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            vals[count] = parseFloat(subjson[key]["CCI"]);
                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('ccichart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Commodity Channel Index (CCI)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },
                                            plotOptions:{
                                                series: {
                                                    color: '#FF0000'
                                                }
                                            },
                                            tooltip: {

                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"CCI"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:json["Meta Data"]["1: Symbol"],
                                                    data:vals.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "bbandschart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "";
                                        document.getElementById('macdchart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        var subjson = json["Technical Analysis: BBANDS"];
                                        var count = 0;
                                        var RUB = [];
                                        var RMB = [];
                                        var RLB = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            RUB[count] = parseFloat(subjson[key]["Real Upper Band"]);
                                            RMB[count] = parseFloat(subjson[key]["Real Middle Band"]);
                                            RLB[count] = parseFloat(subjson[key]["Real Lower Band"]);

                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('bbandschart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Bollinger Bands (BBANDS)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },

                                            tooltip: {
                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"BBANDS"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:symbol + " Real Upper Band",
                                                    data:RUB.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                },
                                                {
                                                    name:symbol + " Real Middle Band",
                                                    data:RMB.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                },
                                                {
                                                    name:symbol + " Real Lower Band",
                                                    data:RLB.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }
                                    if(id == "macdchart"){
                                        document.getElementById('container').style.display = "none";
                                        document.getElementById('emachart').style.display = "none";
                                        document.getElementById('stochchart').style.display = "none";
                                        document.getElementById('rsichart').style.display = "none";
                                        document.getElementById('adxchart').style.display = "none";
                                        document.getElementById('ccichart').style.display = "none";
                                        document.getElementById('bbandschart').style.display = "none";
                                        document.getElementById('smachart').style.display = "none";
                                        document.getElementById('macdchart').style.display = "";
                                        var subjson = json["Technical Analysis: MACD"];
                                        var count = 0;
                                        var MS = [];
                                        var MH = [];
                                        var M = [];
                                        var dates = [];
                                        for(var key in subjson){
                                            var str = key.split("-");
                                            dates[count] = str[1]+"/"+str[2].substr(0,2);
                                            MS[count] = parseFloat(subjson[key]["MACD_Signal"]);
                                            MH[count] = parseFloat(subjson[key]["MACD_Hist"]);
                                            M[count] = parseFloat(subjson[key]["MACD"]);

                                            count++;
                                            if(121 == count)
                                                break;
                                        }

                                        Highcharts.chart('macdchart', {
                                            chart: {
                                                borderWidth: 1,
                                                type: 'spline',
                                                borderColor: '#DDDDDD'
                                            },
                                            title:{
                                                text:"Moving Average Convergence/Divergence (MACD)"
                                            },
                                            subtitle:{
                                                useHTML:true,
                                                text:"<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                                            },

                                            tooltip: {
                                                valueDecimals: 4
                                            },
                                            legend: {
                                                verticalAlign: 'middle',
                                                align: 'right',
                                                layout: 'vertical'
                                            },
                                            yAxis:{
                                                title:{
                                                    text:"MACD"
                                                }
                                            },
                                            xAxis:{
                                                categories:dates.reverse(),
                                                tickInterval:5
                                            },
                                            series:[
                                                {
                                                    name:symbol,
                                                    data:M.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                },
                                                {
                                                    name:symbol + " MACD_Hist",
                                                    data:MH.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                },
                                                {
                                                    name:symbol + " MACD_Signal",
                                                    data:MS.reverse(),
                                                    marker:{
                                                        enabled:true,
                                                        symbol: "square",
                                                        radius:2
                                                    }
                                                }
                                            ]
                                        });
                                    }

                                    //alert( json);
                                }
                                else{
                                    alert("Slow Network. " + xmlhttp.statusText );
                                    return;
                                }

                            }
                        };


                        xmlhttp.open("GET", url, true);
                        xmlhttp.send(null);

                    }


                    function changeChart(self) {

                        if (self.id == "sma") {

                            doIt("https://www.alphavantage.co/query?function=SMA&symbol="+symbol+"&interval=daily&time_period=10&series_type=close&apikey=D0O6J5ZT9KN0UPHI","smachart");



                        }
                        if (self.id == "price") {

                            document.getElementById('emachart').style.display = "none";
                            document.getElementById('stochchart').style.display = "none";
                            document.getElementById('rsichart').style.display = "none";
                            document.getElementById('adxchart').style.display = "none";
                            document.getElementById('ccichart').style.display = "none";
                            document.getElementById('bbandschart').style.display = "none";
                            document.getElementById('macdchart').style.display = "none";
                            document.getElementById('smachart').style.display = "none";
                            document.getElementById('container').style.display = "";
                        }
                        if (self.id == "ema") {
                            doIt("https://www.alphavantage.co/query?function=EMA&symbol="+symbol+"&interval=daily&time_period=10&series_type=close&apikey=D0O6J5ZT9KN0UPHI","emachart");

                        }
                        if (self.id == "stoch"){
                            doIt("https://www.alphavantage.co/query?function=STOCH&symbol="+symbol+"&interval=daily&apikey=D0O6J5ZT9KN0UPHI","stochchart");

                        }
                        if (self.id == "rsi") {
                            doIt("https://www.alphavantage.co/query?function=RSI&symbol="+symbol+"&interval=daily&time_period=10&series_type=close&apikey=D0O6J5ZT9KN0UPHI","rsichart");

                        }
                        if (self.id == "adx") {
                            doIt("https://www.alphavantage.co/query?function=ADX&symbol="+symbol+"&interval=daily&time_period=10&apikey=D0O6J5ZT9KN0UPHI","adxchart");

                        }
                        if (self.id == "cci") {
                            doIt("https://www.alphavantage.co/query?function=CCI&symbol="+symbol+"&interval=daily&time_period=10&apikey=D0O6J5ZT9KN0UPHI","ccichart");

                        }
                        if (self.id == "bbands") {
                            doIt("https://www.alphavantage.co/query?function=BBANDS&symbol="+symbol+"&interval=daily&time_period=5&series_type=close&apikey=D0O6J5ZT9KN0UPHI","bbandschart");

                        }
                        if (self.id == "macd") {
                            doIt("https://www.alphavantage.co/query?function=MACD&symbol="+symbol+"&interval=daily&series_type=close&apikey=D0O6J5ZT9KN0UPHI","macdchart");

                        }
                    }


                    // price/volume的图像
                    Highcharts.chart('container', {
                        chart: {
                            borderWidth: 1,
                            type: 'line',
                            borderColor: '#DDDDDD'
                        },
                        title: {


                            text: "Stock Price (" + st[1]+"/"+st[2].substr(0,2)+"/"+st[0] + ")"
                        },
                        subtitle: {
                            useHTML: true,
                            text: "<a style='text-decoration: none' target='_blank' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>"
                        },
                        legend: {
                            verticalAlign: 'middle',
                            align: 'right',
                            layout: 'vertical'
                        },
                        yAxis: [
                            {
                                title: {
                                    text: "Stock Price"
                                },

                                min: minClo,
                                max: maxClo
                            },
                            {
                                title: {
                                    text: "Volume"
                                },
                                opposite: true,

                                max: maxVol * 5

                            }
                        ],
                        xAxis: {
                            categories: dates.reverse(),
                            tickInterval: 5
                        },
                        series: [
                            {
                                type: "area",
                                name: symbol,
                                data: list.reverse(),
                                threshold: null,
                                yAxis: 0,
                                color: "#f37f81",
                                marker: {
                                    enabled: false
                                },
                                tooltip: {

                                    valueDecimals: 2
                                }

                            },
                            {
                                type: "column",
                                name: symbol + " Volume",
                                data: res.reverse(),
                                yAxis: 1,
                                color: "white"
                            }
                        ]
                    });
                </script>

                <?php

            }
        }
    }


        ?>

</body>
</html>