// get current date "yyyy-mm-dd"
function get_date_str() {
    var myDate = new Date();
    return myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate();
}

function get_quater_str() {
    var myDate = new Date();
    var year = myDate.getFullYear();    
    var mon  = myDate.getMonth() + 1
    var quater = null;

    if (mon < 4) {
        quater = "Q1";

    } else if (mon < 7) {
        quater = "Q2";

    } else if (mon < 10) {
        quater = "Q3";

    }  else {
        quater = "Q4";
    }

    return year + quater;
}

// get html str for a row
function get_row_html_str() {
    return "<tr>" +  
           "<td class='tm_td'><input type=text class='tm_box' /></td>" + 
           "<td class='cate_td'><select class='cate_box'><option>运营</option><option>运维</option><option>其他</option></select></td>" + 
           "<td class='plat_td'><input type=text class='plat_box' /></td>" + 
           "<td class='desc_td'><textarea cols='30' rows='5' class='desc_box' /></textarea></td>" + 
           "<td class='id_td'><input type=text class='id_box' /></td>" + 
           "<td class='pri_td'><select class='pri_box'><option>P1</option><option selected='selected'>P2</option><option>P3</option></select></td>" + 
           "<td class='reason_td'><textarea cols='30' rows='5' class='reason_box' /></textarea></td>" + 
           "<td class='plan_td'><textarea cols='20' rows='5' class='plan_box' /></textarea></td>" + 
           "<td class='com_td'><textarea cols='20' rows='5' class='com_box'></textarea></td>" + 
           "<td><a href='' class='row_add'>添加</a> <a href='' class='row_del'>删除</a></td>" + 
           "</tr>";
}

// add a new row
$(document).on("click", "a.row_add", function(e) {
    var ldap = $("input#ldap").val();
    if (ldap == "") {
        alert("请先输入ldap!");
        $("input#ldap").focus();
        return false;
    }

    var new_row = get_row_html_str();
    
    if ($(this).hasClass("head_row")) {
        var tbody = $(this).parent().parent().parent().next();
        $(new_row).prependTo($(tbody));
        
        $("input.plat_box", $(tbody)).val("");
        $("textarea.com_box", $(tbody)).val("记录者: " + $("input#ldap").val());
    } else {
        $(new_row).insertAfter($(this).parent().parent());

        $current_row = $(this).parent().parent();
        $next_row = $current_row.next()
        $("input.plat_box", $next_row).val($("input.plat_box", $current_row).val());
        $("select.cate_box", $next_row).get(0).selectedIndex = $("select.cate_box", $current_row).get(0).selectedIndex;
        $("textarea.com_box", $next_row).val($("textarea.com_box", $current_row).val());
    }

    $(".tm_box").datepicker({"dateFormat": "yy-mm-dd" });

    e.preventDefault();
});

// remove current row
$(document).on("click", "a.row_del", function(e) {
    var ret = confirm("Confirm to delete row ?");
    if (ret != 1) return false;

    $(this).parent().parent().remove();

    e.preventDefault();
});

$("a#go_to_issue_page").click(function(e) {
    var quater = get_quater_str();
    var url = "https://dev.corp.youdao.com/outfoxwiki/Test/AD/LiveIssues/" + quater + "?action=edit&editor=text";
    // open issue page to paste
    window.open(url);
    
    e.preventDefault();
});

$(document).on("click", "input#output", function(e) {
    // http://www.codeproject.com/Articles/25388/Accessing-parent-window-from-child-window-or-vice
    $("div#output_div").html("");

    var rows = $("tbody#issues_tbody tr");
    $.each(rows, function(index, row) {
        var date = $(".tm_box", $(row)).val();
        var cate = $("select.cate_box", $(row)).val();
        var plat = $("input.plat_box", $(row)).val();
        var desc = $("textarea.desc_box", $(row)).val();
        var ticket_id = $("input.id_box", $(row)).val();
        var pri = $("select.pri_box", $(row)).val();
        var reason = $("textarea.reason_box", $(row)).val();
        var plan = $("textarea.plan_box", $(row)).val();
        var comment = $("textarea.com_box", $(row)).val();
        
        var event = cate + "(" + plat + "):" + desc + "&lt;&lt;BR&gt;&gt;";
        if (ticket_id != "") {
            if (cate == "运营") {
                event += "[[" + ticket_id + "|view ticket]]";
            } else {
                event += "[[" + ticket_id + "|view issue]]";
            }
        }

        var row_str = "|| " + date + " || " + event + " || || " + pri + " || " + reason + " || " + plan + " || " + comment + "||<br>";
        $("div#output_div").append(row_str.replace("\n", "&lt;&lt;BR&gt;&gt;"));
    });
});

$(function() {
    $("input#ldap").focus();
});
