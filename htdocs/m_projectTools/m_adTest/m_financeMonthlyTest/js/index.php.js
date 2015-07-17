// === setttings ====
var _finance_host = null;
var _finance_dir  = null;

// triggered when document ready
$(function() {
    // hide some divs
    $("div#user_input div#show_table_input").hide();
    $("div#user_input div#delete_data_input").hide();
    $("div#deploy_conf").hide();

    // taggle for config show/hide
    $("a#show_conf").toggle(function(e) {
        $("a#show_conf").html("隐藏配置");
        $("div#deploy_conf").show();
    }, function() {
        $("a#show_conf").html("显示配置");
        $("div#deploy_conf").hide();
    });

    // read conf
    $.post("./php/read_conf.php", function(data) {
        var list = data.split(":");
        _finance_host = list[0];
        _finance_dir  = list[1].replace('/war/WEB-INF/hibernate.properties', '');
        $("input#finance_host").val(_finance_host);
        $("input#finance_dir").val(_finance_dir);
    });

    // set conf
    $("input#set_config").click(function(e) {
        var _finance_host = $("input#finance_host").val();
        var _finance_dir  = $("input#finance_dir").val(); 
        var data = _finance_host + ":" + _finance_dir + '/war/WEB-INF/hibernate.properties';
        $("input#set_config").attr("disabled", true);
        $.post("./php/set_conf.php", {"data": data}, function(data) {
            if (data == "0") {
                alert("设置成功！");

            } else {
                alert("设置失败！");
            }

            $("input#set_config").attr("disabled", false);
        });
    });
    
    // ===  查询部分 ====
    $("input#show_table_data").click(function(e) {
        var readable = $("input#readable").attr("checked") ? 1 : 0;
        var input_sponsor_id = $("input#sponsor_id").val();
        if (input_sponsor_id == "") {
            alert("Error: 请输入广告商ID!");
            return false;
        }

        var checked_tables = $("input.table_checkbox:checked");
        if (checked_tables.length == 0) {
            alert("Erorr: 没有选择表来读取!");
            return false;
        }

        $("input#show_table_data").attr("disabled", true);

        var table_list = [];
        $.each(checked_tables, function(idx, input) {
            table_list.push($(input).attr("for"));
        });
        var table_list_str = table_list.join(",");
        
        var conf_str = _finance_host + ":" + _finance_dir + '/war/WEB-INF/hibernate.properties';
        $("div#content").load("./php/load_tables.php", {"table_list": table_list_str, "sponsor_id": input_sponsor_id, "conf_str":conf_str, "readable": readable}, function(data) {
            $("input#show_table_data").attr("disabled", false);
        });
    });

    $("a#show_tables").click(function(e) {
        $("div#user_input div#show_table_input").show().siblings().hide();
        $("div#content").html("");

        e.preventDefault();
    });

    $("input#check_all_query").click(function(e) {
        var checked = $(this).attr("checked") ? true : false;
        $("input.table_checkbox").attr("checked", checked);
    });

    // ===  删除部分 ====
    $("input#delete_table_data").click(function(e) {
        var input_sponsor_id = $("input#sponsor_id2").val();
        if (input_sponsor_id == "") {
            alert("Error: 请输入广告商ID!");
            return false;
        }

        var checked_tables = $("input.table_checkbox2:checked");
        if (checked_tables.length == 0) {
            alert("Erorr: 没有选择表来操作!");
            return false;
        }

        ret = confirm("确定删除?");
        if (ret != true) return false;

        $("input#delete_table_data").attr("disabled", true);

        var table_list = [];
        $.each(checked_tables, function(idx, input) {
            table_list.push($(input).attr("for"));
        });
        var table_list_str = table_list.join(",");
        
        var conf_str = _finance_host + ":" + _finance_dir + '/war/WEB-INF/hibernate.properties';
        $.post("./php/delete_tables_sponsor.php", {"table_list": table_list_str, "sponsor_id": input_sponsor_id, "conf_str": conf_str} ,function(data){
            if (data == "0") {
                alert("删除成功!");
            } else {
                alert("删除失败!");
            }

            $("input#delete_table_data").attr("disabled", false);
        });
    });

    $("a#delete_tables_data").click(function(e) {
        $("div#user_input div#delete_data_input").show().siblings().hide();
        $("div#content").html("");

        e.preventDefault();
    });

    $("input#check_all_delete").click(function(e) {
        var checked = $(this).attr("checked") ? true : false;
        $("input.table_checkbox2").attr("checked", checked);
    });

    $("a#doSettlement").click(function(e){
        var ret = confirm("url:" + $(this).attr("href") +", 确认做结算？");
        if (ret != true) {
            e.preventDefault();
        }
    });

    $("a#doChargeUp").click(function(e){
        var ret = confirm("url:" + $(this).attr("href") +", 确认做结算？");
        if (ret != true) {
            e.preventDefault();
        }
    });

    $("a#doChargeUp2").click(function(e){
        var ret = confirm("url:" + $(this).attr("href") +", 确认做结算？");
        if (ret != true) {
            e.preventDefault();
        }
    });
});
