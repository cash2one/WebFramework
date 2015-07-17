$(function() {

// =========================
// 全局变量定义区
// =========================
var _product_for_update = null;
var _product_for_load   = null;
var _note_id_for_update = null;

var $content_div = $("<tr class='hello'><td colspan='4'><div></div></td></tr>");

var _svn_addr = null;

// =========================
// 变量定义区
// =========================
var $product_index_div = $("div#product_index_div");
var $new_index_div     = $("div#new_index_div");
var $update_index_div  = $("div#update_index_div");
var $note_list_div     = $("div#note_list_div");
var $new_note_div      = $("div#new_note_div");
var $update_note_div      = $("div#update_note_div");

var $product_index_tbody = $("tbody#product_index_tbody");
var $note_list_tbody = $("tbody#note_list_tbody");

var $new_product_link = $("a#new_product_index_btn");
var $new_index_btn   = $("input#new_index_btn");
var $new_index_ret_btn = $("input#new_index_return_btn");
var $update_index_btn  = $("input#update_index_btn");
var $update_index_ret_btn = $("input#update_index_return_btn");
var $new_note_link = $("a#new_note_btn");
var $note_list_ret_link = $("a#note_list_return_btn");
var $save_note_btn = $("input#save_note_btn");
var $new_note_ret_btn = $("input#new_note_return_btn");
var $save_note_btn2 = $("input#save_note_btn2");
var $update_note_ret_btn = $("input#update_note_return_btn");

// =========================
// 初始化区
// =========================
$new_index_div.hide();
$update_index_div.hide();
$note_list_div.hide();
$new_note_div.hide();
$update_note_div.hide();

$content_div.hide();

load_product_list();

// =========================
// 页面交互区
// =========================

// 当离开页面时，弹窗
window.onbeforeunload = function(){
    if ($new_note_div.is(":visible") || $update_note_div.is(":visible")) {
        return '提示：离开前请确保数据已经保存完毕!';
    }
}

// 在首页点击"新建"链接
$new_product_link.click(function(e) {
    $product_index_div.hide();

    $("input#prod_index_title").val("");
    $("input#index_author").val("");
    $("input#svn_addr").val("");
    $("input#comment").val("");
    $new_index_div.show();

    $("input#prod_index_title").focus();

    e.preventDefault();
});

// 在创建新产品页，点击"创建"按钮
$new_index_btn.click(function(e) {
    var product = $("input#prod_index_title").val();
    var author  = $("input#index_author").val();
    var svn_addr = $("input#svn_addr").val();
    var comment  = $("input#comment").val();
    if (product == "" || author == "" || svn_addr == "") {
        alert("Error: 请保证产品名，作者，svn地址已填写!");
        return false;
    }

    var php_file = "./php/create_prod_index.php";
    $.getJSON(php_file, {"product": product, "author": author, "svn": svn_addr, "comment": comment}, function(retObj) {
        if (retObj["ret"] != 0) {
            alert(retObj["msg"]);
            return false;
        }

        $new_index_div.hide();
        $product_index_div.show();
        load_product_list();
    });
});


// 在更新产品页，点击"更新"按钮
$update_index_btn.click(function(e) {
    var product = $("input#prod_index_title2").val();
    var author  = $("input#index_author2").val();
    var svn_addr = $("input#svn_addr2").val();
    var comment  = $("input#comment2").val();
    if (product == "" || author == "" || svn_addr == "") {
        alert("Error: 请保证产品名，作者，svn地址已填写!");
        return false;
    }

    var php_file = "./php/update_prod_index.php";
    $.getJSON(php_file, {"old_product": _product_for_update, "product": product, "author": author, "svn": svn_addr, "comment": comment}, function(retObj) {
        if (retObj["ret"] != 0) {
            alert(retObj["msg"]);
            return false;
        }

        $update_index_div.hide();
        $product_index_div.show();
        load_product_list();
    });
});

// 点击'编辑'链接
$(document).on("click", "a#edit_prod_index_btn", function(e) {
    $product_index_div.hide();

    var $tds = $("td", $(this).parent().parent());
    _product_for_update = $tds.eq(1).html();
    var author = $tds.eq(2).html();
    var svn = $("a", $tds.eq(3)).html();
    var comment = $tds.eq(4).html();

    $("input#prod_index_title2").val(_product_for_update);
    $("input#index_author2").val(author);
    $("input#svn_addr2").val(svn);
    $("input#comment2").val(comment);
    $update_index_div.show();

    $("input#prod_index_title2").focus();

    e.preventDefault();
});

// 点击"查看笔记"链接
$(document).on("click", "a#view_note_list_btn", function(e) {
    var $tds = $("td", $(this).parent().parent());
    _product_for_load = $tds.eq(1).html();
    _svn_addr = $("a", $tds.eq(3)).html();

    $product_index_div.hide();
    $note_list_div.show();

    load_note_list();

    e.preventDefault();
});

// 在创建新产品页，点击"返回"按钮
$new_index_ret_btn.click(function(e) {
    $new_index_div.hide();
    $product_index_div.show();
});

// 在编辑新产品页，点击"返回"按钮
$update_index_ret_btn.click(function(e) {
    $update_index_div.hide();
    $product_index_div.show();
});

// 笔记列表页面，点击“新建”链接
$new_note_link.click(function(e) {
    $note_list_div.hide();
    $("input#note_title").val("");
    $("textarea#note_content").val("");
    $("input#note_author").val("");
    $new_note_div.show();
    $("input#note_title").focus();

    e.preventDefault();
});

// 笔记列表页面，点击"编辑"链接
$(document).on("click", "a#note_edit_btn", function(e) {
    $("input#note_title2").val("");
    $("textarea#note_content2").val("");
    $("input#note_author2").val("");

    var $tds = $("td", $(this).parent().parent());
    _note_id_for_update = $(this).parent().parent().attr("id");
    var title = $tds.eq(1).html();
    var sponsor = $tds.eq(2).html();

    $note_list_div.hide();

    $("input#note_title2").val(title);
    var php_file = "./php/load_note_content.php";
    $.get(php_file, {"product": _product_for_load, "id": _note_id_for_update}, function(result) {
        $("textarea#note_content2").val(result);
    });
    $("input#note_author2").val(sponsor);
    $("input#note_title2").focus();
    $update_note_div.show();

    e.preventDefault();
});

// 笔记列表页面，点击"查看细节"链接
$(document).on("click", "a#note_detail", function(e) {
    if ($(this).parent().parent().next().hasClass("hello") && $content_div.is(":visible")) {
        $content_div.hide();
        return false;
    }

    var $tr = $(this).parent().parent();
    var note_id_for_view = $tr.attr("id");
    var php_file = "./php/load_note_content.php";
    $.get(php_file, {"product": _product_for_load, "id": note_id_for_view}, function(result) {
        result = result.replace(/&/g, "&amp;");
        result = result.replace(/>/g, "&gt;");
        result = result.replace(/</g, "&lt;");
        result = result.replace(/(public\s+.*?\))/g, "<b>$1</b>");

        result = result.replace(/ /g, "&nbsp;");
        result = result.replace(/\n/g, "<br>");

        if (_svn_addr != null || _svn_addr != "") {
            result = result.replace(/(src\/.*?\.java)/g, "<a target=_blank href='" + _svn_addr + "/$1'>$1</a>");
            result = result.replace(/(war\/.*?\.jsp)/g, "<a target=_blank href='" + _svn_addr + "/$1'>$1</a>");
        }

        $content_div.show();
        $tr.after($content_div);
        $content_div.html(result);
    });

    e.preventDefault();
});

// 笔记列表页面，点击"删除"链接
$(document).on("click", "a#note_del_btn", function(e) {
    var ret = confirm("确认要删除该笔记?");
    if (ret != true) {
        return false;
    }

    var $tr = $(this).parent().parent();
    var note_id_for_del = $tr.attr("id");
    var php_file = "./php/delete_note.php";
    $.getJSON(php_file, {"product": _product_for_load, "id": note_id_for_del}, function(retObj) {
        if (retObj["ret"] != 0) {
            alert(retObj["msg"]);
            return false;
        }

        $tr.remove();
    });

    e.preventDefault();
});


// 笔记列表页面，返回链接点击
$note_list_ret_link.click(function(e) {
    $note_list_div.hide();
    $product_index_div.show();

    e.preventDefault();
});

// 新建笔记页面，保存笔记
$save_note_btn.click(function(e) {
    var title = $("input#note_title").val();
    var content = $("textarea#note_content").val();
    var sponsor = $("input#note_author").val();

    if (title == "" || content == "" || sponsor == "") {
        alert("错误：输入不能为空!");
        return false;
    }

    var php_file = "./php/create_note.php";
    $.post(php_file, {"product": _product_for_load, "title": title, "content": content, "sponsor": sponsor}, function(ret_str) {
        var retObj = $.parseJSON(ret_str);
        if (retObj["ret"] != 0) {
            alert(retObj["msg"]);
            return false;
        }

        $new_note_div.hide();
        load_note_list();
        $note_list_div.show();
    });
});

// 更新笔记页面，保存笔记
$save_note_btn2.click(function(e) {
    var title = $("input#note_title2").val();
    var content = $("textarea#note_content2").val();
    var sponsor = $("input#note_author2").val();

    if (title == "" || content == "" || sponsor == "") {
        alert("错误：输入不能为空!");
        return false;
    }

    var php_file = "./php/update_note.php";
    $.getJSON(php_file, {"product": _product_for_load, "id":_note_id_for_update, "title": title, "content": content, "sponsor": sponsor}, function(retObj) {
        if (retObj["ret"] != 0) {
            alert(retObj["msg"]);
            return false;
        }

        $update_note_div.hide();
        load_note_list();
        $note_list_div.show();
    });
});

// 新建笔记页面，返回链接点击
$new_note_ret_btn.click(function(e) {
    var ret = confirm("返回会导致更新的数据丢失，确认返回？");
    if (ret != true) {
        return false;        
    }

    $new_note_div.hide();
    load_note_list();
    $note_list_div.show();

    e.preventDefault();
});

// 更新笔记页面，返回链接点击
$update_note_ret_btn.click(function(e) {
    var ret = confirm("返回会导致更新的数据丢失，确认返回？");
    if (ret != true) {
        return false;        
    }

    $update_note_div.hide();
    load_note_list();
    $note_list_div.show();

    e.preventDefault();
});

// =========================
// 函数区
// =========================
function load_product_list() {
    var php_file = "php/load_prod_list.php";
    $product_index_tbody.load(php_file);
}

function load_note_list() {
    var php_file = "php/load_note_list.php";
    $note_list_tbody.load(php_file, {"product": _product_for_load});
}

}); // end of outer bracket
