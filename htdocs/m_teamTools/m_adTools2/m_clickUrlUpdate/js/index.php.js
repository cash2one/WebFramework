$(function() {
    // === 变量定义区
    var $input_box = $("textarea#ta_click_url_input");

    // === 初始化区
    $input_box.focus();

    // === 事件处理区
    $("a#get_detail").click(function(e) {
        load_detail();
        e.preventDefault();
    });

    $("a#get_click").click(function(e) {
        load_click();
        e.preventDefault();
    });

    // === 函数定义区
    function load_detail() {
        var click_url = $("textarea#ta_click_url_input").val().replace(/\n/g, "");
        var php_file = "./php/read_detail.php";
        $("span#ta_click_url_output").html("");
        $("table#result").html("").load(php_file, {"click_url": click_url});
    }

    function get_format_str($trs, kv_sep, field_sep) {
        if ($trs == null || $trs.length == 0) return null;
         
        var ret_str = "";
        $.each($trs, function(idx, $tr) {
            var key = $("th ", $tr).html();  
            var value = $("td input", $tr).val();
            if (key == "pre_url") {
                key = "click-head";
            }

            if (ret_str == "") {
                ret_str = key + kv_sep + value;
            } else {
                ret_str += field_sep + key + kv_sep + value;
            }
        });

        return ret_str;
    }

    function load_click() {
        var $pre_url_tr = $("table tr.pre_url");
        var $d_tr = $("table tr.d");
        var $s_tr = $("table tr.s");
        var $cac_all_tr = $("table tr.cac_all");
        var $k_tr = $("table tr.k");
        var $textMap_tr = $("table tr.t");

        var detail_str = "";
        var pre_url = get_format_str($pre_url_tr, "", null);
        if (pre_url != null) {
            detail_str = pre_url;
        }

        var d_val = get_format_str($d_tr, "", null);
        if (d_val != null) {
            if (detail_str == "") {
                detail_str = d_val;
            } else {
                detail_str += "" + d_val;
            }
        }

        var s_val = get_format_str($s_tr, "", null);
        if (s_val != null) {
            if (detail_str == "") {
                detail_str = s_val;
            } else {
                detail_str += "" + s_val;
            }
        }

        var cac_all_val = get_format_str($cac_all_tr, "", null);
        if (cac_all_val != null) {
            if (detail_str == "") {
                detail_str = cac_all_val;
            } else {
                detail_str += "" + cac_all_val;
            }
        }

        var k_val = get_format_str($k_tr, "", "");
        if (k_val != null) {
            if (detail_str == "") {
                detail_str = "k" + k_val;
            } else {
                detail_str += "k" + k_val;
            }
        }

        var textMap_val = get_format_str($textMap_tr, "", "");
        if (textMap_val != null) {
            detail_str += "textMap" + textMap_val;
        }

        var php_file = "./php/read_clickUrl.php";
        $("span#ta_click_url_output").html("").load(php_file, {"detail_str": detail_str});
    }
});
