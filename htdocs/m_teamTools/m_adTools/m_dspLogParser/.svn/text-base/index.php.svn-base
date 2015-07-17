<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>
<h3>dsp日志解析工具</h3>
<form action="" method="POST" width = 100%>
    <table>
    <tr><td>点击日志: </td><td><textarea name="clickLog" rows="10" cols="75"></textarea></td>
    <td>bid日志: </td><td><textarea name="bidLog" rows="10" cols="75"></textarea></td></tr>
    <tr><td>impr日志: </td><td><textarea name="imprLog" rows="10" cols="75"></textarea></td>
    <td>pv日志: </td><td><textarea name="pvLog" rows="10" cols="75"></textarea></td></tr>
    <tr><td><input type="submit" name="query" value="日志检查"></input></td>
    </table>
</form>
<?php
$imprName=array("time","sponsorId","campaignId","groupId","advId","keyword","syndId","siteId","imprPos","imprIp","imprUrl","oriCost","actualCost","score","rand","uid","moreInfo","codeId");
$pvName=array("time","OUTFOX_SEARCH_USER_ID","syndicationId","memberId","adNum","ImprIp","URL","moreInfo");
$clickName=array("sponsorId","campaignId","adGroupId","advId","keywordId","syndId","siteId","imprPos","qs","rank","origCost","actuCost","imprIp","clickerIp","clickerId","imprTime","clickTime","commitTime","imprReq","referer","textMap","codeId","memberId");
$imprMoreInfo=array("LOG","BID","USER_OUTSIDE_ID","POSITION_ID","WIN_PRICE","WIDTH","HEIGHT","RESPONSE_TIME","BID_PRICE","AGENT","PAGE_URL","SEND_MODE","BID_COOKIE","Crowd","UserCategory");
$bidMoreInfo=array("LOG","BID","USER_OUTSIDE_ID","POSITION_ID","MIN_CPM_PRICE","BID_PRICE","WIDTH","HEIGHT","CHARGE_TYPE","TEST","CPA","AGENT","Crowd","Item","UserCategory","Page");
$pvMoreInfo=array("BID","USER_OUTSIDE_ID","POSITION_ID","MIN_CPM_PRICE","WIDTHS","HEIGHTS","PING","TEST","AGENT","PRETARGET_GIDS","SITE_CATEGORY","UserCategory");
$clickMoreInfo=array("BID","POSITION_ID","WIDTH","HEIGHT","CPA","BIDDER","CLICKED_ITEM","Crowd","Item","UserCategory","Page");
function processLog($impr,$imprName,$imprMoreInfo) {
   $imprArray=explode(",",$impr);
   if (count($imprArray)!=count($imprName)){
      echo "<h3 style='color:red'>length error: valueLen=".count($imprArray).", keyLen=".count($imprName)."</h3>";
   }
   
   echo "<table  border='1'><tr>";
   for($i=0;$i<count($imprName);$i++) {
      echo "<td>".$imprName[$i]."</td>";
   }
   echo "</tr><tr>";
   for($i=0;$i<count($imprArray);$i++) {
      if(strlen($imprArray[$i])==0) {
         echo "<td style='color:red'> empty!!! </td>";
      } else {
         if(strcmp($imprName[$i],"moreInfo")==0) {
            echo "<td>see below</td>";
         } else {
            echo "<td>".$imprArray[$i]."</td>";
         }
      }
      if(strcmp($imprName[$i],"moreInfo")==0) {
      $imprMore=explode("#&@!",$imprArray[$i]);
      for($j=0;$j<count($imprMore);$j++) {
         $keyValue=explode("\":",$imprMore[$j]);
         $key=str_replace("\"","",$keyValue[0]);
         $key=str_replace("{","",$key);
         $value=str_replace("}","",$keyValue[1]);
         $imprMoretmp[$key]=$value;
      }
      } 
   }
   echo "</tr></table>";
   echo "<p></p>";
   echo "<table width='1600' border='1'><tr>";
   for($k=0;$k<count($imprMoreInfo);$k++) {
       echo "<td>".$imprMoreInfo[$k]."</td>";
   }
   echo "</tr><tr>";
   for($k=0;$k<count($imprMoreInfo);$k++) {
       if(array_key_exists($imprMoreInfo[$k],$imprMoretmp)) {
          if(strlen($imprMoretmp[$imprMoreInfo[$k]])>0) {
             echo "<td style='word-break:break-all'>".$imprMoretmp[$imprMoreInfo[$k]]."</td>";
          } else {
             echo "<td style='color:red'> empty!!!</td>";
          }
       } else {
          echo "<td style='color:red'> not exist!!!"." </td>";
       }
   }
   echo "</tr></table>";

}

function processClickLog($click,$clickName,$clickMoreInfo) {
   $start=strpos($click,"[");  #get begin:ClickAction[
   $click1=substr($click,$start+1);   #click1 stands for clickLog
   #print "<p>click1".$click1."</p>";
   $superKeyword=explode("}",$click1);   # superKeyword[0] means text with superKeyword, 1 means text after superKeyword
   $super=explode("superKeyword=",$superKeyword[0]); #super[0] means text before superKeyword. 1 means  superKeyword value 
   $superValue=str_replace("=","<>",$super[1]);
   #print "<p>".$superValue."</p>";
   #print "<p> superKeyword1".$superKeyword[1]."</p>";
   $text=explode("]",$superKeyword[1]);   #text[0] means text after superKeyword and before textMap end
   #print $text[0];
   
   $textMap=explode("textMap=",$text[0]);    #textMap[0] means text after superKeyword and before textMap key.
   $textValue=str_replace(",","#&@!",$textMap[1]); 
   $textValue=str_replace("=",":",$textValue);
   $click=$super[0]."superKeyword=".$superValue.$textMap[0]."textMap=".$textValue.$superKeyword[2];
   #print $click;
   $clickArray=explode(",",$click);
   for($i=0;$i<count($clickArray);$i++) {
      if(!strpos($clickArray[$i],"=")) {
          $clickArray[$i-1]=$clickArray[$i-1] ." ". $clickArray[$i];
          $clickArray[$i]="";
      }
      
   }
   for($i=0;$i<count($clickArray);$i++) {
      if(strlen($clickArray[$i])>0) {
         $clickTmp=explode("=",$clickArray[$i]);
         $key=ltrim($clickTmp[0]);
         $value=$clickTmp[1];
         $clickInfo[$key]=$value;
         #echo "<p>".$key." ".$value."</p>";
      }
   }
   echo "<table width='1650' border='1'><tr>";
   for($j=0;$j<count($clickName);$j++) {
       echo "<td>".$clickName[$j]."</td>";
   }
   echo "</tr><tr>";
   for($j=0;$j<count($clickName);$j++) {
       if(array_key_exists($clickName[$j],$clickInfo)) {
   #print "<p>".$click1."</p>";
          if(strlen($clickInfo[$clickName[$j]])>0) {
             echo "<td style='word-break:break-all'>".$clickInfo[$clickName[$j]]."</td>";
          } else {
             echo "<td style='color:red'>empty!!!</td>";
          }
       } else {
          echo "<td style='color:red'>not exists!!!</td>";  
       }
   }
   echo "</tr></table>";
   echo "<p></p>";
   #moreInfo
   echo "<table width='1650' border='1'><tr>";
   for($j=0;$j<count($clickMoreInfo);$j++) {
       echo "<td>".$clickMoreInfo[$j]."</td>";
   }
   echo "</tr><tr>";
   $moreInfo=explode("#&@!",$clickInfo["superKeyword"]);
   for($k=0;$k<count($moreInfo);$k++) {
       #print $moreInfo[$k];
       if(!strpos($moreInfo[$k],"\":")) {
           #print "cacacacacacaca";
           continue;
       }
       $tmp=explode("\":",$moreInfo[$k]);
       $key=str_replace("\"","",$tmp[0]);
       $key=str_replace("{","",$key);
       #echo "<p>".$key."</p>";
       $value=$tmp[1];
       #echo "<p>".$key." ".$value."</p>";
       $clickMoreInfoTmp[$key]=$value;
   }
   for($j=0;$j<count($clickMoreInfo);$j++) {
       if(array_key_exists($clickMoreInfo[$j],$clickMoreInfoTmp)) {
          if(strlen($clickMoreInfoTmp[$clickMoreInfo[$j]])>0) {
             echo "<td style='word-break:break-all'>".$clickMoreInfoTmp[$clickMoreInfo[$j]]."</td>";
          } else {
             echo "<td style='color:red'>empty!!!</td>";
          }   
       } else {
          echo "<td style='color:red'>not exists!!!</td>";  
       }
   }
   echo "</tr></table>";
}

if(array_key_exists("clickLog", $_POST)) {
   $click=$_POST["clickLog"];
   if(strlen($click)>0) {
      echo "<h3>clickLog</h3>";
      processClickLog($click,$clickName,$clickMoreInfo);
      echo $click;
   }
}
if(array_key_exists("bidLog", $_POST)) {
   $bid=$_POST["bidLog"];
   if(strlen($bid)>0) {
      echo "<h3>bidLog</h3>";
      processLog($bid,$imprName,$bidMoreInfo);
   }
   #echo $bid;
}
if(array_key_exists("imprLog", $_POST)) {
   $impr=$_POST["imprLog"];
   if(strlen($impr)>0) {
      echo "<h3>imprLog</h3>";
      processLog($impr,$imprName,$imprMoreInfo);
   }
   #echo $impr;
}
if(array_key_exists("pvLog", $_POST)) {
   $pv=$_POST["pvLog"];
   $pv=str_replace("\\,","#&@!",$pv);
   if(strlen($pv)>0) {
      echo "<h3>pvLog</h3>";
      processLog($pv,$pvName,$pvMoreInfo);
   }
   #echo $pv;
}
?>
</body>
</html>
