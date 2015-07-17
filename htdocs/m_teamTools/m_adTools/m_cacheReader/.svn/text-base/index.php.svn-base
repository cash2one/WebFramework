<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>
<h3>dsp数据域读取</h3>
<script>
function getValue() {
   document.getElementById("op").value=1;
}

function listKeys() {
   document.getElementById("op").value=0;
}
</script>
<form action="" method="POST" width = 100%>
    <p>zk服务地址:<input type="text" name="zk" id="zk" value="tb081:2181"/> (eg:tb081:2181) </p>
    <p>缓存端口：<input type="text" name="port" id="port" value="9527"> (eg:9527)</p>
    <p>数据域名称: 
        <select name="domain">
            <option value="DspAllTargetIndex">DspAllTargetIndex</option>
            <option value="DspAdGroupDataV2">DspAdGroupDataV2</option>
            <option value="DspAdGroupDataV3" selected="selected">DspAdGroupDataV3</option>
            <option value="EadAdGroupIndex">EadAdGroupIndex</option>
            <option value="DspDeliverySettingsIndex">DspDeliverySettingsIndex</option>
            <option value="DspSiteIndex">DspSiteIndex</option>
            <option value="ServerStubListData">ServerStubListData</option>
            <option value="DspAdCampaignAccountData" >DspAdCampaignAccountData</option>
            <option value="DspAdvariationCategoryIndex">DspAdvariationCategoryIndex</option>
        </select>
        数据id:<input type="text" name="key"></input>
        <input type="submit" name="query" value="查询" ></input>
        <input type="submit" name="list" value="列举全部"></input>
        <input type="hidden" name="op" id="op"></input>
    </p>
    <h3>dsp数据实时生产通知</h3>
    <p>
      广告组id:<input type="text" name="groupId"></input>
      <input type="submit" name="groupNotice" value="Group更新通知" ></input>
    </p>
    <p>
      广告系列id:<input type="text" name="campaignId"></input>
    <input type="submit" name="campaignNotice" value="Campaign更新通知"></input>
    </p>
    <p>
      广告商id:<input type="text" name="sponsorId"></input>
    <input type="submit" name="sponsorNotice" value="Sponsor更新通知"></input>
    </p>
</form>
<?php
if(array_key_exists("zk", $_POST) && array_key_exists("port", $_POST)) {
   $domain=$_POST["domain"];
   if(!empty($_POST['query'])){
        $key=$_POST['key'];
        $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh DSLReader ".$domain." getValue ".$key;
        echo $execStr;
        $ans=exec($execStr);
        $data=stristr($ans,"ansByLuqy");
        echo "<p> damain: ". $domain. " value: </p>";
        echo $data;
   } else {
        $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh DSLReader ".$domain." listKeys ";
        echo $execStr;
        $ans=exec($execStr);
        $data=stristr($ans,"ansByLuqy");
        echo "<p> domain: ". $domain. " list keys: </p>";
        echo $data;
   }
}
if(!empty($_POST['groupNotice'])){
    $groupId = $_POST['groupId'];
    $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh DSLNoticer group ".$groupId;
    echo $execStr;
    $ans=exec($execStr);
    echo $ans;
}
if(!empty($_POST['campaignNotice'])){
    $campaignId = $_POST['campaignId'];
    $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh DSLNoticer campaign ".$campaignId;
    echo $execStr;
    $ans=exec($execStr);
    echo $ans;
}
if(!empty($_POST['sponsorNotice'])){
    $sponsorId = $_POST['sponsorId'];
    echo $sponsorId;
    $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh DSLNoticer sponsor ".$sponsorId;
    echo $execStr;
    $ans=exec($execStr);
    echo $ans;
}
?>
</body>
</html>
