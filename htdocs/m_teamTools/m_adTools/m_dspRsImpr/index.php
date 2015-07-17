<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<?php
    $bsPath = get_value("bsPath", "/disk2/luqy/dsp/bs/m2.24.1-pef");
    $host   = get_value("host", "hs030");   
    $port   = get_value("port", "9998");
    $args   = get_value("args", " -c -1905753230411169973");
    $rs     = get_value("rs", "qt104x.corp.youdao.com:18080");
    $nginx  = get_value("nginx", "qt104x.corp.youdao.com:8070");

    function get_value($key, $def_value) {
        global $_POST;
        if (array_key_exists($key, $_POST))
            return $_POST[$key];
        return $def_value;
    }
?>

<style>
    input[type="text"] {width: 600px }
</style>
</head>

<body>
<h3>dsp展示渲染</h3>
<form action="" method="POST" style="width:100%">
    <!--p>bs服务路径:<input type="text" name="bsPath" value="/disk2/luqy/dsp/bs/bs-tag2.22.0-pre3-pef"/> (eg:/disk1/luqy/dsp/bs-trunk,bs需要ant compile-all,否则无法执行^_^) </p-->
    <p>竞价工具服务路径:<input type="text" name="bsPath" value="<?php echo $bsPath ?>"/> (eg:/disk1/luqy/dsp/bs-trunk,bs需要ant compile-all,否则无法执行^_^) </p>
    <p>竞价工具服务器名：<input type="text" name="host" value="<?php echo $host ?>"> (eg:qt101)</p>
    <p>bs服务端口(host:port): <input type="text" name="port" value="<?php echo $port ?>"/> (eg:9012)</p>
    <p>请求广告参数: <input type="text" name="args" value="<?php echo $args ?>"> (eg:-size 300x250 -c -8660964261892718765)</p>
    <p>rs服务访问地址: <input type="text" name="rs" value="<?php echo $rs ?>" /> 
       nginx服务访问地址: <input type="text" name="nginx" value="<?php echo $nginx ?>" /></p>
    <!--p>rs服务访问地址: <input type="text" name="rs" value="qt102x.corp.youdao.com:18080"> nginx服务访问地址: <input type="text" name="nginx" value="qt104x.corp.youdao.com:8070"></p-->
    <input type="submit" name="tanx" value="tanx请求广告" />
    <input type="submit" name="adx" value="adx请求广告" />
    <input type="submit" name="ead" value="ead请求广告" />
    <input type="submit" name="tade" value="tade请求广告" />
    <input type="submit" name="allyes" value="allyes请求广告" />
    <input type="submit" name="bex" value="baidu请求广告" />
</form>
<?php
   #$rs="qt102x.corp.youdao.com:18080";
   if(!empty($_POST['tanx'])){
       $exeStr="ssh ". $host. "  'cd ".$bsPath."; sh bin/run.sh outfox.ead.dsp.bs.TanxBidTool -s ".$port." "        .$args." -v';";
   }
   elseif(!empty($_POST['adx'])){
       $exeStr="ssh ". $host. "  'cd ".$bsPath."; sh bin/run.sh outfox.ead.dsp.bs.AdxBidTool -s ".$port." ".$args." -v';";
   }
   elseif(!empty($_POST['ead'])){
       $exeStr="ssh ". $host. "  'cd ".$bsPath."; sh bin/run.sh outfox.ead.dsp.bs.EadBidTool -s ".$port." ".$args." -v';";
   }
   elseif(!empty($_POST['allyes'])){
       $exeStr="ssh ". $host. "  'cd ".$bsPath."; sh bin/run.sh outfox.ead.dsp.bs.AllyesBidTool -s ".$port." ".$args." -v';";
   }
   elseif(!empty($_POST['bex'])){
       $exeStr="ssh ". $host. "  'cd ".$bsPath."; sh bin/run.sh outfox.ead.dsp.bs.BexBidTool -s ".$port." ".$args." -v';";
   }
   else {
        exit(0);
   }
   echo $exeStr;
   exec($exeStr,$output);
   echo $output;
   foreach($output as $s) {
       #echo "sssss".$s;
       if(stripos($s,"html_snippet")) {
           $snippet=str_ireplace("\\","",$s);
           $snippet=str_ireplace("dsp-render.youdao.com",$rs,$snippet);
           $snippet=str_ireplace("dsp-impr.youdao.com",$nginx,$snippet);
           #AawYgmR8fFGxwLkAAAB9G937kxIpC7vkZg   29
           #AQr2BCRQKVFmLoAAAAABeS%2F5jKaADz7zbQ%3D%3D  6
           $snippet=str_ireplace("%%SETTLE_PRICE%%","AQr2BCRQKVFmLoAAAAABeS%2F5jKaADz7zbQ%3D%3D",$snippet);
           echo "<p>浏览器页面:</p>";
           echo $snippet;
       }
       if(stripos($s,"dsp-render.youdao.com")) {
           $start=stripos($s,"http://dsp-render.youdao.com");
           $snippet=substr($s,$start,strlen($s)-1);
           $snippet=str_ireplace("\\","",$snippet);
           $end=stripos($snippet,"'");
           $snippet=substr($snippet,0,$end);
           $snippet=str_ireplace("dsp-render.youdao.com",$rs,$snippet);
           echo "<p>渲染样式:</p>";
           echo "<a href='"."$snippet"."' target='_blank'>".$snippet."</a>";
       }
       if(stripos($s,"http://dsp-impr.youdao.com")){
           $start=stripos($s,"http://dsp-impr.youdao.com");
           if(!empty($_POST['allyes'])){
              $end = stripos(substr($s,$start),"\"");
           } else {
              $end = stripos(substr($s,$start),"'");
           }
           $impr = substr($s,$start,$end);
           $impr = str_ireplace("dsp-impr.youdao.com",$nginx,$impr);
           if(!empty($_POST['tanx'])){
              $impr = str_ireplace("%%SETTLE_PRICE%%","AQr2BCRQKVFmLoAAAAABeS%2F5jKaADz7zbQ%3D%3D",$impr);
           } else if(!empty($_POST['adx'])){
              $impr = str_ireplace("%%WINNING_PRICE%%","UX1HkAACVWMKpB3JeQAswJyEIZD1SnsGeTwXXw",$impr);
           } else if(!empty($_POST['allyes'])){
              $impr = str_ireplace("\${AUCTION_PRICE}","1",$impr);
              $impr = str_ireplace("\u003d","=",$impr);
              $impr = str_ireplace("\u0026","&",$impr);
           }
           echo "<p>展示请求：</p>";
           echo "<a href='".$impr."' target='_blank'>".$impr."</a>";
       }
       if(stripos($s,"click_through_url")) {
           $start=stripos($s,"http");
           $end=strlen($s)-2;
           echo "<p>点击链接 ：</p>";
           echo "<a href='".substr($s,$start,$end)."' target='_blank'>".substr($s,$start,$end)."</a>";
       }
       if(stripos($s,"curl")) {
           $start = stripos($s,"\"curl\":\"http:");
           $tmp = substr($s,$start);
           $click_array = explode("\"",$tmp);
           $click = $click_array[3];
           $click = str_ireplace("\u003d","=",$click);
           $click = str_ireplace("\u0026","&",$click);
           $click = str_ireplace("p.clkservice.youdao.com","nc107x.corp.youdao.com:18383",$click);
           echo "<p>点击链接 ：</p>";
           echo "<a href='".$click."' target='_blank'>".$click."</a>";
       }
       if(stripos($s,"extdata")) {
           $click_array = explode("\"",$s);
           $ext = $click_array[1];
           echo "<p>".$ext."</p>";
           $click = "http://nc111x.corp.youdao.com:48992/cb?yd_ext=".$ext."&yd_lp=aHR0cDovL3d3dy53Ymlhby5jbi9zYWxlLzI";
           #$click = "http://qt104x.corp.youdao.com:18081/cb?yd_ext=".$ext."&yd_lp=http://www.baidu.com";
           echo "<p>点击链接 ：</p>";
           echo "<a href='".$click."' target='_blank'>".$click."</a>";
           if(!empty($_POST['bex'])){
               //31
               $impr="http://qt104x.corp.youdao.com:8070/b.gif?yd_ext=".$ext."&yd_ewp=UnDgaQAA8v57jEpgW5IA8rrRK0y5Z03c1UGbag";
               echo "<p>展示链接 ：</p>";
               echo "<a href='".$impr."' target='_blank'>".$impr."</a>";
           }
       }
   }   
?>
</body>
</html>
