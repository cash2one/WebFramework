<html>
<head>
  <meta charset="utf-8">
  <style type="text/css">
    table#input th {text-align: right}
    table#input td input[type=text], table td input[type=password] {background: yellow}
  </style>
</head>

<body>
<center>
<table id='input'>
  <tr>
    <th>服务: </th>
    <td>
      <input checked type=checkbox id='click-service' title='日志路径：nb011:/disk4/eadop/click-resin/logs/log; nb015:/disk4/eadop/click-resin/logs/log; nc092:/disk4/eadop/click-resin/logs/log; nc072:/disk4/eadop/click-resin/logs/log' />点击服务
      <input checked type=checkbox id='anti-service' title='日志路径：hs026:/disk2/eadop/antifrauder/logs/log; hs027:/disk2/eadop/antifrauder/logs/log; nc096:/disk2/eadop/antifrauder/logs/log' />反作弊服务<br>
    </td>
  </tr>
  <tr>
    <th>过滤条件(包含): </th>
    <td><input type=text id='filter_str' value='clickerIp=61.135.255.83' size='50' />(或者 imprIp=61.135.255.83)</td>
  </tr>
  <tr>
    <th>最大读取行数: </th>
    <td><input type=text id="max_lines_cnt" value='1000' size='10' /></td>
  </tr>
  <tr>
    <th>Ldap: </th>
    <td>
        <input type=text id="ldap" size='15' />
    </td>
  </tr>
  <tr>
    <th>集群密码: </th>
    <td>
        <input type=password id="passwd" size='15' />
    </td>
  </tr>
  <tr>
    <td></td>
    <td><input type=button id='query_btn' value="请求日志" /> <label id='tip'><font color='red'>注意：请求开始需要计算日志行数，请耐心等待</font></label></td>
  </tr>
</table>
</center>

<script src="./js/user-input.php.js"></script>
</body>
</html>
