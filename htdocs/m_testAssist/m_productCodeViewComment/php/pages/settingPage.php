<?php
    header("Content-type: text/html; charset=utf-8");
    include("../util/htmlUtil.php");
?>

<center>
<form method="POST" action="./php/pages/results/settingResultPage.php">
<table>
    <tr>
        <td>
            类型:<select id="set_type" name="set_type">
                <option value="type_name_map">产品名与集合名映射</option>
            </select>
            <input type="submit" value="设置" title="没填写的会被忽略" />
        </td>
    </tr>  
    <tr>
        <td id="set_detail"></td>
    </tr>
</table>
</form>
</center>

<div id="code_info"></div>

<script>
$(function(e) {
    show_set_content();

    $("select#set_type").change(function(e) {
        show_set_content();
    });

    function show_set_content() {
        var set_type = $("select#set_type").val();
        if (set_type == "type_name_map") {
            $("td#set_detail").html("").load("./php/tools/get_name_map_table.php");
        }
    }
});
</script>
