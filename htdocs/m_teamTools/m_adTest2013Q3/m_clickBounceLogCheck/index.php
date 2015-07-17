<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../../css-base/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>

<h3>Click Bounce Server广告字段查看(<a target='_blank' href="https://dev.corp.youdao.com/outfoxwiki/Advertisement/DSP/LogSpecification#head-474f61af324cafd1822c25d83c35d13bd56114b5">竞价日志</a>)</h3>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">输入</a></li>
    <li><a href="#tabs-2">结果</a></li>
  </ul>
  <div id="tabs-1">
    <h4>ClickBounceServer cb日志</h4>
    <textarea id="cb_log" style="width:100%" rows="10" placeholder="输入本次请求在cb.log中的日志..."></textarea>

    <h4>点击日志</h4>
    <textarea id="click_log" style="width:100%" rows="10" placeholder="输入本次请求的点击日志..."></textarea>

    <input type=button id="check" value="检查" />
    <a href="" id="try_it">载入样例日志</a>
  </div>
  <div id="tabs-2">
  </div>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
