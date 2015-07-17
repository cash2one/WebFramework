<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<?php
    $bsPath = get_value("bsPath", "/disk1/EadAutoTest/backend/env/");
    $host   = get_value("host", "hs030");   
    $port   = get_value("port", "5555");
    $autoPath   = get_value("autoPath", "/disk1/EadAutoTest/automation-bs/");
    $autoHost   = get_value("autoHost", "hs030");
    $rs     = get_value("rs", "qt104x.corp.youdao.com:18080");
    $nginx  = get_value("nginx", "qt104x.corp.youdao.com:8070");
    $cb  = get_value("cb", "http://nc111x.corp.youdao.com:48992");

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
<h3>dsp渲染点击回测</h3>
<form action="" method="POST" style="width:100%">
    <p>自动回测服务路径:<input type="text" name="bsPath" value="<?php echo $bsPath ?>"/> (eg:/disk1/luqy/dsp/bs-trunk,bs需要ant compile-all,否则无法执行^_^) </p>
    <p>自动回测服务器名：<input type="text" name="host" value="<?php echo $host ?>"> (eg:qt101)</p>
    <p>自动回测服务端口: <input type="text" name="port" value="<?php echo $port ?>"/> (eg:9012)</p>
    <p>automation服务路径：<input type="text" name="autoPath" value="<?php echo $autoPath ?>"/></p>
    <p>automation服务器名：<input type="text" name="autoHost" value="<?php echo $autoHost ?>"/></p>
    <p>rs服务访问地址: <input type="text" name="rs" value="<?php echo $rs ?>" /></p> 
    <p>nginx服务访问地址: <input type="text" name="nginx" value="<?php echo $nginx ?>" /></p>
    <p>cb服务访问地址: <input type="text" name="cb" value="<?php echo $cb ?>" /></p>
    <hr/>
    渲染回测：
    <input type="submit" name="tanx" value="tanx请求广告" />
    <input type="submit" name="adx" value="adx请求广告" />
    <input type="submit" name="ead" value="ead请求广告" />
    <!--input type="submit" name="tade" value="tade请求广告" /-->
    <!--input type="submit" name="allyes" value="allyes请求广告" /-->
    <!--input type="submit" name="bex" value="bex请求广告" /-->
    <hr/>
    点击回测:
    <input type="submit" name="forClick" value="tanx点击回测" />
    <input type="submit" name="forClick_adx" value="adx点击回测" />
    <input type="submit" name="forClick_bex" value="bex点击回测" />
    <input type="submit" name="forClick_tade" value="tade点击回测" />
</form>
<?php
   if(!empty($_POST['tanx'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.TanxBidTool -s ";
       $caseName="caseRender.txt";
   }
   elseif(!empty($_POST['adx'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.AdxBidTool -s ";
       $caseName="caseRender.txt";
   }
   elseif(!empty($_POST['ead'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.EadBidTool -s ";
       $caseName="caseRender.txt";
   }
   elseif(!empty($_POST['allyes'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.AllyesBidTool -s ";
       $caseName="caseRender.txt";
   }
   elseif(!empty($_POST['bex'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.BexBidTool -s ";
       $caseName="caseRender.txt";
   }
   elseif(!empty($_POST['forClick'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.TanxBidTool -s ";
       $caseName="caseClick.txt";
   }
   elseif(!empty($_POST['forClick_adx'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.AdxBidTool -s ";
       $caseName="caseClick.txt";
   }
   elseif(!empty($_POST['forClick_bex'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.BexBidTool -adStyles 1 2 -s ";
       $caseName="caseClick.txt";
   }
   elseif(!empty($_POST['forClick_tade'])){
       $exeStr="sh bin/run.sh outfox.ead.dsp.bs.TadeBidTool -s ";
       $caseName="caseClick.txt";
   }
   else {
        exit(0);
   }
   function processRender($output,$name,$rs,$nginx) {
      $snippet="";
      foreach($output as $s) {
       if(stripos($s,"html_snippet")) {
           $snippet=str_ireplace("\\","",$s);
           $snippet=str_ireplace("dsp-render.youdao.com",$rs,$snippet);
           $snippet=str_ireplace("dsp-impr2.youdao.com",$nginx,$snippet);
           #AawYgmR8fFGxwLkAAAB9G937kxIpC7vkZg   29
           #AQr2BCRQKVFmLoAAAAABeS%2F5jKaADz7zbQ%3D%3D  6
           $snippet=str_ireplace("%%SETTLE_PRICE%%","AQr2BCRQKVFmLoAAAAABeS%2F5jKaADz7zbQ%3D%3D",$snippet);
           if(!empty($_POST['tanx'])){
              $snippet = str_ireplace("%%SETTLE_PRICE%%","AQr2BCRQKVFmLoAAAAABeS%2F5jKaADz7zbQ%3D%3D",$snippet);
           } else if(!empty($_POST['adx'])){
              $snippet = str_ireplace("%%WINNING_PRICE%%","UX1HkAACVWMKpB3JeQAswJyEIZD1SnsGeTwXXw",$snippet);
              $start=stripos($snippet,"CLICK_URL_UNESC");
              $tmpStr=substr($snippet,$start+strlen("CLICK_URL_UNESC%%"),strlen($snippet));
              $end=stripos($tmpStr,"'");
              $clickStr=substr($tmpStr,0,$end);
              $clickUrl=urldecode($clickStr);
              $snippet=str_replace($clickStr,$clickUrl,$snippet);
              $snippet=str_replace("%%CLICK_URL_UNESC%%","",$snippet);
           } else if(!empty($_POST['allyes'])){
              $snippet = str_ireplace("\${AUCTION_PRICE}","0.01",$snippet);
              $snippet = str_ireplace("\u003d","=",$snippet);
              $snippet = str_ireplace("\u0026","&",$snippet);
           }
       }#end of snippet
       if(stripos($s,"click_through_url")) {
               $start=stripos($s,"http");
               $end=strlen($s)-23;
               $clickUrl=substr($s,$start,$end);
               $snippet = str_ireplace("%%CLICK_URL%%",$clickUrl,$snippet);
       }
    } #end foreach
    #$snippet=str_ireplace("html_snippet","",$snippet);
    $string_w="<div><p>".$name."</p>".$snippet."</div>";
    return $string_w;
   } #end of function processRender
   function processClick($output,$name,$cb) {
    $check['ST-SINGLE']="Page field not null in Click log (NOW IT WILL ALWASY BE FAILED; Waiting for fixed)";
    $check['RT-DOUBLE-3']="Crowd field not null in Click log";
    $check['UC-DOUBLE-3']="UserCategory field not null in Click log";
    $check['RT-ST-5']="Page & Crowd field not null in Click log";
    $check['BT-ST-5']="Page & UserCategory field not null in Click log";
    $check['ALLT']="Page & UserCategory & Crowd field is null in Click log";
    $check['CPA-2']="CPA field is bigger than 0 in Click log";
    $check['LEFENG-API']="Jump to a specified url";
    $check['WBIAO-API-CPC']="Jump to a specified url";
    $check['WBIAO-API-CPA']="Jump to a specified url";
    $check['ZHENPING-API']="Jump to a specified url";
    $check['JIEHUN-API']="Jump to a specified url";
    $lp['ST-SINGLE']=base64_encode("http://www.baidu.com");
    $lp['RT-DOUBLE-3']=base64_encode("http://www.baidu.com");
    $lp['UC-DOUBLE-3']=base64_encode("http://www.baidu.com");
    $lp['RT-ST-5']=base64_encode("http://www.baidu.com");
    $lp['BT-ST-5']=base64_encode("http://www.baidu.com");
    $lp['ALLT']=base64_encode("http://www.baidu.com");
    $lp['CPA-2']=base64_encode("http://www.baidu.com");
    $lp['LEFENG-API']=base64_encode("http://www.lefeng.com/zhuanti/");
    $lp['WBIAO-API-CPC']=base64_encode("http://www.wbiao.cn/activity/fathers-day/cmbchina");
    $lp['WBIAO-API-CPA']=base64_encode("http://www.wbiao.cn/activity/fathers-day/cmbchina");
    $lp['ZHENPING-API']=base64_encode("http://www.zhenpin.com/product-1014.html");
    $lp['JIEHUN-API']=base64_encode("http://www.jiehun.com.cn");
    $getHtml = 1;
    $snHtml = ""; 
    foreach($output as $s) {
       if(stripos($s,"click_through_url")) {
           if(stripos($s,"youdao.com")) {
               $getHtml = 0;
               $start=stripos($s,"http");
               $end=strlen($s)-2;
               $clickUrl=substr($s,$start,$end);
               $clickUrl="<a href=".$clickUrl." target='blank' >".$clickUrl." </a>";
               return "<p>".$name."</p>".$clickUrl;
           }
       }
       
       if(stripos($s,"html_snippet")) {
           $snHtml=str_ireplace("\\","",$s);
           if(!empty($_POST['forClick_adx'])){
             $start=stripos($s,"http");
             $tmpStr=substr($s,$start,strlen($s));
             $end=stripos($tmpStr,"'");
             $clickStr=substr($tmpStr,0,$end);
             $clickStr=urldecode($clickStr);
             $clickUrl="<a href=".$clickStr." target='blank' >".$clickStr." </a>";
             return "<p>".$name."</p>".$clickUrl;
           }
           #echo $snHtml;
       }

       if(stripos($s,"extdata:") or stripos($s,"ext:")) {
           $click_array = explode("\"",$s);
           $ext = $click_array[1];
           #$click = $cb."/cb?yd_ext=".$ext."&yd_lp=aHR0cDovL3d3dy53Ymlhby5jbi9zYWxlLzI";
           $click = $cb."/cb?yd_ext=".$ext."&yd_lp=".$lp[$name];
           $clickUrl = "<p>".$name."(".$check[$name].")"."</p>"."<a href='".$click."' target='_blank'>".$click."</a>";
           return $clickUrl;
        }
    }#end of foreach
    if($getHtml == 1) {
       return "<p>".$name."</p>".$snHtml;    
    }
   }
   
   $caseStr="scp ".$autoHost.":".$autoPath.$caseName." .";
   exec($caseStr,$output);
   $file_handle=fopen($caseName,"r");
   $file_w=fopen("render.html","w");
   $string_w = "<html><head></head><body>";
   $string_c = "";
   while(!feof($file_handle)) {
      $line = fgets($file_handle);
      if(stripos($line,"#")===false and stripos($line,"ID")>0){
          $para = explode("):(",$line);
          $tmp = explode(",",$para[0]);
          $tmp2 = explode("=",$tmp[0]);
          $name = $tmp2[1];
          $xmlPath = $para[1];
          $input = $para[2];
          $cpStr = "cd ".$autoPath."; python control.py ".$name.";";
          $runStr = "ssh ".$host." '".$cpStr."cd ".$bsPath.";".$exeStr.$host.":".$port."".$input." ';";
           echo $runStr;
           unset($output);
           exec($runStr,$output);
           if(!empty($_POST['forClick']) or !empty($_POST['forClick_bex']) or !empty($_POST['forClick_tade']) or !empty($_POST['forClick_adx'])) {
                $tmp=processClick($output,$name,$cb);
                $string_c = $string_c.$tmp;
           }else {
                $tmp = processRender($output,$name,$rs,$nginx);
                $string_w = $string_w.$tmp;
           } 
      }#end if valid line
   } #end while
   if(!empty($_POST['forClick']) or !empty($_POST['forClick_bex']) or !empty($_POST['forClick_tade']) or !empty($_POST['forClick_adx'])) {
        echo $string_c;
   }else {
        $string_w = $string_w."</body></html>";
        fwrite($file_w,$string_w);
        fclose($file_w);
        $link="http://tb037x.corp.youdao.com:28081/m_teamTools/m_adTestDsp/m_dspRenderClick/render.html";
        echo "渲染回测链接：<a href=".$link." target='blank'>".$link."</a>";
   } 
    
?>
</body>
</html>
