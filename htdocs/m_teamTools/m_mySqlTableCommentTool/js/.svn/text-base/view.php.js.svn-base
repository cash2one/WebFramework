$(function() {
    var _db_name = "";
    var _table_name = "";
    var _edit_mode = 0;
    var _in_edit_status = 0;

    $("input#edit_mode").click(function(e) {
        if( $("input#edit_mode").attr("checked") != "checked") {
            if (_in_edit_status == 1) {
                var ret = confirm("放弃修改？");
                if (ret != true) {
                    return false; 
                }
                _in_edit_status = 0;
            }

            _edit_mode = 0;
            $("input#save").attr("disabled", true);
        } else {
            _edit_mode = 1;
            $("input#save").attr("disabled", false);
        }

        $("table#detail").html("");
    });

    $("table a.db").click(function(e) {
        if (_in_edit_status == 1) {
            var ret = confirm("放弃修改？");
            if (ret != true) {
                return false; 
            }
            _in_edit_status = 0;
        }

        $("table#detail").html("");

        $("table a.db").removeClass("selected");
        $(this).addClass("selected");
        _db_name = $(this).html();

        $("table#table_area tbody").html("").load("./php/loadTablesStr.php", {"server_name": server_name, "server_port": server_port, "username": username, "password": password, "db_name": _db_name});
        e.preventDefault();
    });

    $(document).on("click", "table a.table", function(e) {
        if (_in_edit_status == 1) {
            var ret = confirm("放弃修改？");
            if (ret != true) {
                return false; 
            }
            _in_edit_status = 0;
        }

        $("table a.table").removeClass("selected");
        $(this).addClass("selected");
        _table_name = $(this).html();

        $("table#detail").html("").load("./php/loadFieldTablesStr.php", {"server_name": server_name, "server_port": server_port, "username": username, "password": password, "db_name": _db_name, "table_name": _table_name, "edit_mode": _edit_mode});
        e.preventDefault();
    });

    $("input#save").click(function(e) {
        var ret = confirm("Confirm to save it?");
        if (ret != true) {
            return false;
        }
        var temp_str = "";
        var trs = $("table#detail tr.item");
        $.each(trs, function(idx, tr) {
            var fieldName = $(tr).attr("name");
            var chName    = $("textarea.ch", tr).val();
            var comVal    = $("textarea.comment", tr).val();
            if (temp_str != "") {
                temp_str += "";
            }
            temp_str += fieldName + "" + chName + "" + comVal;
        });
        if (temp_str == "") {
            alert("Nothing to Save!");
            return false;
        }

        $.post("./php/saveFieldComment.php", {"server_name": server_name, "server_port": server_port, "db_name": _db_name, "table_name": _table_name, "field_str": temp_str}, function(e) {
            alert(e);
            _in_edit_status = 0;
            $("table#db_area tr td a[name='" + _db_name + "']").addClass("commented");
            $("table#table_area tr td a[name='" + _table_name + "']").addClass("commented");
        });
    });

    $(document).on("click", "table#detail textarea", function(e) {
        _in_edit_status = 1;        
    });

});

window.onbeforeunload = function(){
    return '提示：离开前请确保数据已经保存完毕!';
}
