<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../../../css-base/jquery-ui.min.css" />

    <script src="../../../js-base/jquery.min.js"></script>
    <script src="../../../js-base/jquery-ui.min.js"></script>
    <script src="../../../js-base/json.min.js"></script>

    <style>
        th {text-align: right}
        table {width: 90%}
        table th {width: 120px; padding-right: 10px}
        table td input[type='text'] {width: 100%}
        table td textarea {width: 100%;}
        a {text-decoration: none}
        a:hover {text-decoration: underline}
        h3 {display: inline}
    </style>

    <?php
        date_default_timezone_set("PRC");
        include("./php/dbUtil.php");
        $userList = Array("选择用户", "张怡", "李丽", "卢秋英", "张培", "吴昊", "朱丹阳", "李红艳");

        function get_readable_time($secs) {
            $sum = 0;

            $sec = $secs % 60;
            $sum += $sec;

            $min = ($secs - $sum) % 3600;
            $sum += $min;
            $min /= 60;

            $hour = ($secs - $sum) % (3600 * 24);
            $sum += $hour;
            $hour /= 3600;

            $day = ($secs - $sum) / (3600 * 24);
            return $day . "天" . $hour . "小时" . $min . "分钟!";
        }

        function show_msg() {
            $group1 = array("张怡", "李丽", "卢秋英", "张培");
            $group2 = array("吴昊", "朱丹阳", "李红艳");
            $group1_date_str = "2014-02-12 14:00:00";

            $timestamp1 = strtotime($group1_date_str);
            $cur_time = time();
            $msg = "";
            for ($i = -2; $i < 100; $i ++) {
                $timestamp = $timestamp1 + $i * 2 * 7 * 24 * 60 * 60;
                if ($timestamp - $cur_time > 0) {
                    if ($i % 2 == 0) {
                        $msg = "本次分享人：" . implode(" ", $group1);
                    } else {
                        $msg = "本次分享人：" . implode(" ", $group2);
                    }                    
                    break;
                }
            }

            $secLeft = $timestamp - $cur_time;
            $msg = $msg . ", 分享时间: " . date("Y-m-d 14:00:00", $timestamp) . ", 还剩" . get_readable_time($secLeft);
            echo "<font color='green'>$msg</font>";
        }
    ?>
</head>

<div style="height:10px"></div>
<h3>广告测试典型BUG分享工具 (<a target=_blank href="https://dev.corp.youdao.com/outfoxwiki/Test/AD/bugSharing">wiki</a>)</h3> -- <?php show_msg() ?> 
<div style="height:10px"></div>

<div id="tabs">
    <ul>
        <li><a href="#tab-bug-list">分享列表</a></li>
        <li><a href="#tab-add-bugs">添加</a></li>
        <li><a href="#tab-attachment-upload">附件上传</a></li>
        <li><a href="#tab-attachment-list">附件列表</a></li>
    </ul>

    <div id="tab-bug-list">
        <table border=1>
            <tr>
                <th style='text-align:center'>分享时间</th>
                <th style='text-align:center'>分享者</th>
                <th style='text-align:center'>分享点</th>
                <th style='text-align:center'>分类</th>
                <th style='text-align:center'>操作</th>
            </tr>
        <?php
            $list = dbUtil(ReadAllContents);
            $temp_list = array();
            // sort($list);
            // $list = array_reverse($list); 
            foreach ($list as $row) {
               array_push($temp_list, sprintf("<tr><td>%s</td><td>%s</td><td title='%s'>%s</td><td>%s</td><td><a target=_blank href='./php/viewDetail.php?id=%s'><font color='blue'>编辑/查看</font></a></td></tr>\n", $row[1], $row[2], $row[6], mb_substr($row[6], 0, 100, "utf-8"), $row[5], $row[0])); 
            }
            sort($temp_list);
            $temp_list = array_reverse($temp_list);
            echo implode('', $temp_list);
        ?>
        </table>
    </div>

    <div id="tab-add-bugs">
        <table> 
            <tr>
                <th>分享人:</th>
                <td colspan='2'>
                    <select id="users">
                    <?php
                        foreach ($userList as $user) {
                            echo "<option>$user</option>", "\n";
                        }
                    ?>     
                    </select>
                </td>
            </tr>
            <tr>
                <th>标题:</th>
                <td colspan='2'><input type=text id="title" /></td>
            </tr>
            <tr>
                <th>描述:</th>
                <td><textarea rows="15" cols='60' id="content" /></textarea></td>
                <td>
                    <input type=radio name='style' id='format1' checked /> 无格式<br>
                    <input type=radio name='style' id='format2' /> 格式二<br>
                </td>
            </tr>
            <tr>
                <th>分类:</th>
                <td colspan='2'>
                    <select id="category">
                        <?php 
                            $cateList = dbUtil(ReadCategory);
                            foreach ($cateList as $cateName) {
                                echo "<option>$cateName</option>";
                            }
                        ?>
                    </select> <a href='' id='add_cate' title='添加分类'>+</a>
                </td>
            </tr>
            <tr>
                <th>核心分享点:</th>
                <td colspan='2'><input type=text id="corePoints" /></td>
            </tr>
            <tr>
                <th></th>
                <td colspan='2'><input type=button id="submit" value="保存" /></td>
            </tr>
        </table>
    </div>

    <div id="tab-attachment-upload">
        <form action="./php/upload_file.php" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td style='width:80px'>文件名:</td>
                    <td><input type="file" name="file" id="file"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="submit" value="上传"></td>
                </tr>
            </table>
        </form>
    </div>

    <div id="tab-attachment-list">
        <table border='1'>
            <tr>
                <th style='text-align:center'>上传日期</th>
                <th style='text-align:center'>图片</th>
                <th style='text-align:center'>url</th>
            </tr>
            <?php
                $files = glob("attachments/*/*");
                $files = array_reverse($files);
                foreach ($files as $file) {
                    $list = explode("/", $file);
                    $filePath = "http://tb037x.corp.youdao.com:28081/m_teamTools/m_adTest2013Q3/m_adTestClassicBugs/$file";
                    echo "<tr><td>" . date("Y-m-d", $list[1]) . "</td><td><img width=50 height=50 src='$file' /></td><td><a style='color:blue' target='blank' href='$filePath'>$filePath</a></td></tr>", "\n";
                }
            ?>
        </table>
    </div>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
