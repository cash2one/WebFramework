$(function() {
    // 全局变量定义区
    var _show_all_versions = true;

    // 初始化区
    $("div#search").show();
    build_search_page();
    $("div#links a[name='search']").addClass("selected");

    // 事件处理区
    $("div#links a").click(function(e) {
        $("div#links a").removeClass("selected");
        $(this).addClass("selected");
        $("div.content").hide();
        var name = $(this).attr("name");
        $("div#" + name).show();

        if (name == "search") {
            _show_all_versions = true;
            build_search_page();

        } else if (name == "add") {
            build_add_page();

        } else if (name == "view_comment") {
            _show_all_versions = false;
            build_view_comment_page();

        } else if (name == "configure") {
            load_type_content();
        }

        e.preventDefault();
    });

    $("div#add a").click(function(e) {
        var prodName = prompt("输入产品名:");
        prodName = prodName.replace(/(^\s*)|(\s*$)/g, "");
        if (prodName == "" || prodName == null) return false;

        $("div#add select[name='prodName']").append("<option selected>" + prodName + "</option>");
        e.preventDefault();

    });

    $("div#search select[name='prodName']").change(function(e) {
        load_prodVersion($(this), $("div#search select[name='version']"));
    });

    $("div#view_comment select[name='prodName']").change(function(e) {
        load_prodVersion($(this), $("div#view_comment select[name='version']"));
    });

    $("div#view_comment a[name='add_user']").click(function(e) {
        var userName = prompt("输入用户名:");
        userName = userName.replace(/(^\s*)|(\s*$)/g, "");
        if (userName == "" || userName == null) return false;

        $("div#view_comment select[name='userName']").append("<option selected>" + userName + "</option>");
        e.preventDefault();
    });

    $("div#configure select[name='op_type']").change(function(e) {
        load_type_content();
    });

    $("div#add input[type='submit']").click(function(e) {
        var ret = confirm("确认添加?");
        if (ret != true) {
            return false;
        }
    });

    $("div#configure input[type='submit']").click(function(e) {
        var ret = confirm("确认设定?");
        if (ret != true) {
            return false;
        }
    });

    // 函数定义区
    function load_prodNameOptions($prodSelectObj) {
        $prodSelectObj.html("").load("./php/tools/loadProductOptions.php");
    }

    function load_prodNameOptions2($prodSelectObj, $versionSelectObj) {
        $prodSelectObj.html("").load("./php/tools/loadProductOptions.php", function(data) {
            load_prodVersion($prodSelectObj, $versionSelectObj);
        });
    }

    function load_prodNameOptions3($prodSelectObj, $versionSelectObj, $userSelectObj) {
        $prodSelectObj.html("").load("./php/tools/loadProductOptions.php", function(data) {
            load_prodVersion($prodSelectObj, $versionSelectObj);
            load_prodUser($prodSelectObj, $userSelectObj);
        });
    }

    function load_prodVersion($prodSelectObj, $versionSelect) {
        var prodName = $prodSelectObj.val();
        $versionSelect.html("");
        $.post("./php/tools/loadProductVersionOptions.php", {"prodName": prodName}, function(data) {
            if (_show_all_versions == true) {
                $versionSelect.append("<option value='*'>所有版本</option>");
            }
            $versionSelect.append(data);
        });
    }

    function load_prodUser($prodSelectObj, $userSelectObj) {
        var prodName = $prodSelectObj.val();
        $userSelectObj.html("");
        $.post("./php/tools/loadProductUserOptions.php", {"prodName": prodName}, function(data) {
            $userSelectObj.append(data);
        });
    }

    function build_search_page() {
        var $prodSelect = $("div#search select[name='prodName']");
        var $versionSelect = $("div#search select[name='version']");
        load_prodNameOptions2($prodSelect, $versionSelect, true);
    }

    function build_add_page() {
        var $prodSelect = $("div#add select[name='prodName']");
        load_prodNameOptions($prodSelect);
    }

    function build_view_comment_page() {
        var $prodSelect = $("div#view_comment select[name='prodName']");
        var $versionSelect = $("div#view_comment select[name='version']");
        var $userSelect    = $("div#view_comment select[name='userName']");
        load_prodNameOptions3($prodSelect, $versionSelect, $userSelect, false);
    }

    function load_type_content() {
        var type_val = $("div#configure select[name='op_type']").val();
        $("div#set").html("").load("./php/tools/" + type_val + ".php");
    }
});
