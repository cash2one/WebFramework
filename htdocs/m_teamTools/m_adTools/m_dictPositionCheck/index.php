<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>

</head>

<body>
<h3>词典所有广告展示</h3>
<form action="" method="POST" width = 100%>
    <p>resin Host:<input type="text" name="host" value="qt101x"/> (eg:qt101x) </p>
    <p>请求端口：<input type="text" name="port" value="11111"> (eg:11111)</p>
	<input type="submit" name="start" value="开始请求" />
</form>
<?php
if($_POST["host"] && $_POST["port"]) {
    $host=$_POST["host"];
    $port=$_POST["port"];
    $url_head="http://$host.corp.youdao.com:$port";
    //词典客户端首页
    $url_body_1="/imp/request.s?req=YoudaoDict5.0&uid=testuser&syndid=50&posid=101&rnd=123456";
    $url_body_2="/imp/request.s?req=-&adnum=1&syndid=998&doctype=xml&rnd=1352105333953&query=what&uid=5ddb4cdcf927aa20"; //4.4之前版本
    //词典客户端结果页
    $url_body_3="/imp/request.s?req=-&adnum=1&syndid=52&posid=102&doctype=xml&rnd=1338530582656&query=fuck&uid=adc57ed3ea08f89e1&offers=ead_dictr_top%20ead_dictr_right%20ead_dictr_1%20ead_dictr_2%20ead_dictr_3%20ead_dictr_4%20ead_dictr_5%20ead_dictr_result_bottom%20&appVer=5.0.34.1193";
    $url_body_4="/imp/request.s?req=-&adnum=1&syndid=998&doctype=xml&rnd=1352105333953&query=what&uid=5ddb4cdcf927aa20&offers=ead_dictr_top%20ead_dictr_right%20ead_dictr_1%20ead_dictr_2%20ead_dictr_3%20ead_dictr_5";//4.之前版本
    //词典底部通栏V1
    $url_body_5="/imp/request.s?req=deskdict.main5.0.34.1193&uid=adc57ed3ea08f89e1&syndid=54&posid=105&doctype=db&op=r&rnd=4&appVer=5.0.34.1193";
    //词典底部通栏V2
    $url_body_6="/imp/request.s?req=deskdict.main5.0.35.9705&uid=***&syndid=56&memberid=108&op=r&strategy=brand&tn=text_560_30&width=560&height=30&appVer=5.0.35.9705";
    $url_body_7="/imp/request.s?req=deskdict.main5.0.35.9705&uid=***&syndid=56&memberid=108&op=r&strategy=brand&tn=text_560_30&width=560&height=30&appVer=5.0.35.9705&callback=fun123";
    //allinone弹窗广告
    $url_body_8="/imp/request.s?req=popup&syndid=59&memberid=120&tn=text_150_100&width=150&height=100&uid=xxx";
    $url_body_9="/imp/request.s?req=popup&syndid=59&memberid=121&tn=text_150_210&width=150&height=210&uid=xxx";
    $url_body_10="";
    //右下角弹窗
    $url_body_11="/imp/request.s?req=http%3A%2F%2Fdict.youdao.com&syndid=59&memberid=130&tn=text_250_50&width=250&height=50&layout=0";
    //取词广告
    $url_body_12="/imp/request.s?type=image&op=r&posid=103&k=&advType=&query=x&pos=0&uid=adc57ed3ea08f89e1&appVer=5.0.33.3225&req=-&syndid=53&doctype=xml&adnum=1";//图片
    $url_body_13="/imp/request.s?type=text&op=r&posid=104&k=&advType=&query=x&pos=0&uid=adc57ed3ea08f89e1&appVer=5.0.33.3225&req=-&syndid=53&doctype=xml&adnum=1";//文字
    //词典web端
    $url_body_14="/imp/request.s?req=http%3A%2F%2Fdict.youdao.com&doctype=dw&memberid=110636&tn=text_960_75&width=960&height=75&posid=201&ref2=http://dict.youdao.com&syndid=57&rnd=654";//网页端->首页-> 底部通栏 960*75
    $url_body_15="/imp/request.s?req=http%3A%2F%2Fdict.youdao.com&doctype=dw&memberid=110636&tn=text_960_60&width=960&height=60&posid=202&ref2=http://dict.youdao.com&syndid=57&time=1338177126990";//网页端-->结果页-->顶部通栏 960x60
    $url_body_16="/imp/request.s?req=http%3A%2F%2Fdict.youdao.com&doctype=dw&memberid=110636&tn=text_250_250&width=250&height=250&posid=203&ref2=http://dict.youdao.com&syndid=57&time=1338177126296";//网页端-->结果页-->右侧中部 250x250
    $url_body_17="/imp/request.s?req=http%3A%2F%2Fdict.youdao.com&doctype=dws&syndid=57&posid=0&memberid=110636&tn=text_250_320&width=250&height=320&ref2=http://dict.youdao.com/&time=1338177127308";//网页端-->结果页-->右侧中部 4条效果文字
    $url_body_18="/imp/request.s?req=http%3A%2F%2Ffanyi.youdao.com%2F%3Fkeyfrom%3Dnavindex.product&rnd=20&doctype=dw&syndid=58&posid=301&memberid=107861&tn=text_960_75&width=960&height=60";//翻译端-->结果页-->底部通栏 960x75
    $url_body_19="/imp/request.s?req=http%3A%2F%2Ffanyi.youdao.com%2Ftranslate&rnd=539&doctype=dws&syndid=58&posid=0&memberid=107861&tn=text_960_25&width=960&height=25";//翻译端-->结果页-->底部通栏 4条效果文字

    $url=array($url_head.$url_body_1,
                $url_head.$url_body_2,
                $url_head.$url_body_3,
                $url_head.$url_body_4,
                $url_head.$url_body_5,
                $url_head.$url_body_6,
                $url_head.$url_body_7,
                $url_head.$url_body_8,
                $url_head.$url_body_9,
                $url_head.$url_body_10,
                $url_head.$url_body_11,
                $url_head.$url_body_12,
                $url_head.$url_body_13,
                $url_head.$url_body_14,
                $url_head.$url_body_15,
                $url_head.$url_body_16,
                $url_head.$url_body_17,
                $url_head.$url_body_18,
                $url_head.$url_body_19);
    $detail=array("词典客户端-首页（4.4之后版本）",
                  "词典客户端-首页（4.4之前版本）",
                  "词典客户端-结果页（4.4之后版本）",
                  "词典客户端-结果页（4.4之前版本）",
                  "词典底部通栏V1",
                  "词典底部通栏V2-客户端请求广告的url",
                  "词典底部通栏V2-带callback参数的请求（效果广告）",
                  "allinone弹窗-右上角150*100",
                  "allinone弹窗-右下角150*210", 
                  "allinone弹窗-浮层广告",
                  "客户端右下角弹窗",
                  "取词广告-图片",
                  "取词广告-文字",
                  "网页端->首页-> 底部通栏 960*75",
                  "网页端-->结果页-->顶部通栏 960x60",
                  "网页端-->结果页-->右侧中部 250x250",
                  "网页端-->结果页-->右侧中部 4条效果文字",
                  "翻译端-->结果页-->底部通栏 960x75",
                  "翻译端-->结果页-->底部通栏 4条效果文字",);
  
    for($i=0;$i<=18;$i++)
    {
       // echo "<hr/>";
        $m=$i+1;
        echo "<h2>$m.$detail[$i]</h2>";
        //$contents = file_get_contents($url[$i]);
        //echo $contents;
        echo ("<iframe width=\"100%\" height=\"200\" src=\"$url[$i]\" frameborder=\"0\"  ></iframe>");

    }


/*
        $contents = file_get_contents($url[0]);
        echo $contents;
        echo "<hr/>"; 
*/
	//如果出现中文乱码使用下面代码  
	//$getcontent = iconv("gb2312", "utf-8",$contents); 
   
}
?>
</body>
</html>
