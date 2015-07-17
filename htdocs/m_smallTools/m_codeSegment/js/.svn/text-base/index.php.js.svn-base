$(function() {
    // 变量定义区
    var $add_table = $("table#add_table");
    var $show_ul = $("ul#show_items");
    var $holder  = $('div.holder');
    var $code_div = $("<div class='code_seg'></div>").hide();
    var old_click_url = "";

    // 初始化区
    $add_table.hide();
    load_language();

    var php_file = "./php/load_select_boxes.php";
    $("td#language").html("").load(php_file);

    // 事件处理区
    $("a#add_new").toggle(
        function(e) {
            $show_ul.hide();
            $holder.hide();
            $code_div.hide();
            $add_table.show();
            $("input#title").focus();
            e.preventDefault();
        }, function(e) {
            $add_table.hide();
            $show_ul.show();
            $holder.show();
            e.preventDefault();
    });

    $("input#manual").focus(function(e) {
        $("td#language input:checked").attr("checked", false);
    });

    $("input#cont_btn").click(function(e) {
        var title = $("input#title").val();
        var content = $("textarea#code_param").val();
        var language = $("td#language input:checked").attr("for");
        var manual_lang = $("input#manual").val();

        if (language != undefined && manual_lang != "") {
            alert("ERROR, 只能选择一种语言!");
            return false;
        } else if (language == undefined && manual_lang == "") {
            alert("ERROR, 需要选择一种语言!");
            return false;
        } else if (language == undefined) {
            language = manual_lang;
        }

        var php_file = "./php/insert_code_info.php";
        $.post(php_file, {"title":title, "content":content, "language":language}, function(data) {
            if (data != "0") {
                alert(data);
                return false;
            } else {
               location.reload(); 
            }
        });
    });

    $("select#lang_type").change(function(e) {
        load_code_info();
    });
    $("input#search_btn").click(function(e) {
        load_code_info();
    });
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
            load_code_info();
        }
    });

    $(document).on("click", "ul#show_items li.url-info", function(e) {
        $code_div.insertAfter($(this));
        if (old_click_url == $(this).attr("name")) {
            $code_div.toggle();
        } else {
            $code_div.show();
        }
        old_click_url = $(this).attr("name");
        load_code($(this).attr("name"));
    });
    $(document).on("click", "ul#show_items li.url-info a", function(e) {
        e.stopPropagation();
    });
    
    // 函数定义区
    function load_code_info() {
        var lang = $("select#lang_type").val();
        var s_word = $("input#search_word").val();
        var php_file = "./php/load_code_info.php";
        $("ul#show_items").html("").load(php_file, {"language": lang, "s_word": s_word}, function(e){
            // 分页插件
            $holder.jPages({
                containerID : "show_items",
                perPage     : 12,
                // animation   : "fadeInDown",
                delay       : 50,
                // direction   : "auto"
            })
        });
        $code_div.hide();
    }

    function load_code(id) {
        var php_file = "./php/load_code.php";
        $code_div.html("").load(php_file, {"id": id});
    }

    function load_language() {
        var php_file = "./php/load_lang.php";
        $("select#lang_type").html("").load(php_file, function(e){
            load_code_info();
        });
    }
});
