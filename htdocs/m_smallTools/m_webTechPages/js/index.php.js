$(function() {
    // 变量定义区
    var $add_table = $("table#add_table");
    var $show_ul = $("ul#show_items");
    var $holder  = $('div.holder');
    var $desc_div = $("<div class='desc'></div>").hide();
    var old_click_url = "";

    // 初始化区
    $add_table.hide();
    load_category();

    var php_file = "./php/load_select_boxes.php";
    $("td#class_it").html("").load(php_file);

    // 事件处理区
    $("a#add_new").toggle(
        function(e) {
            $show_ul.hide();
            $holder.hide();
            $desc_div.hide();
            $add_table.show();
            $("input#url").focus();
            e.preventDefault();
        }, function() {
            $add_table.hide();
            $show_ul.show();
            $holder.show();
            e.preventDefault();
    });

    $("a#edit_mode").click(function(e){
        load_url_info("edit");
        e.preventDefault();
    });

    $("input#manual").click(function(e) {
        var content = $(this).val();
        if (content == "逗号分隔") {
            $(this).val("");
        }
    });
    $("input#manual").blur(function(e) {
        var content = $(this).val();
        if (content == "") {
            $(this).val("逗号分隔");
        }
    });

    $("input#cont_btn").click(function(e) {
        var url = $("input#url").val();
        if (url == "") {
            alert("错误: URL不能为空!");
            return false;
        }

        if (url.substring(0, 4) != "http") {
            alert("错误：URL格式不对");
            return false;
        }

        var title = $("input#title").val();
        var desc = $("textarea#desc").val();
        var class_str = ""; 
        
        var class_list = [];
        $.each($("td#class_it input:checked"), function(idx, input) {
            class_list.push($(input).attr("for"));
        });
        class_str = class_list.join(",");

        var manual_class = $("input#manual").val();
        if (manual_class != "逗号分隔") {
            class_str += "," + $("input#manual").val();
        }

        var php_file = "./php/insert_url_info.php";
        $.get(php_file, {"url":url, "title":title, "desc":desc, "class_str":class_str}, function(data) {
            if (data != "0") {
                alert(data);
                return false;
            } else {
               location.reload(); 
            }
        });
    });

    $("select#category").change(function(e) {
        load_url_info("show");
    });
    $("input#search_btn").click(function(e) {
        load_url_info("show");
    });
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
            load_url_info("show");
        }
    });

    $(document).on("hover", "ul#show_items li.url-info", function(e) {
        $desc_div.insertAfter($(this));
        if (old_click_url == $(this).attr("name")) {
            $desc_div.toggle();
        } else {
            $desc_div.show();
        }
        old_click_url = $(this).attr("name");
        load_desc($(this).attr("name"));
    });
    $(document).on("click", "ul#show_items li.url-info a", function(e) {
        e.stopPropagation();
    });
    
    // 函数定义区
    function load_url_info(type) {
        var classname = $("select#category").val();
        var s_word = $("input#search_word").val();
        var php_file = "./php/load_url_info.php";
        $("ul#show_items").html("").load(php_file, {"class": classname, "s_word": s_word, "type": type}, function(e){
            // 分页插件
            $holder.jPages({
                containerID : "show_items",
                perPage     : 12,
                // animation   : "fadeInDown",
                delay       : 50,
                // direction   : "auto"
            })
        });
        $desc_div.hide();
    }

    function load_desc(url) {
        var php_file = "./php/load_desc.php";
        $desc_div.html("").load(php_file, {"url": url});
    }

    function load_category() {
        var php_file = "./php/load_category.php";
        $("select#category").html("").load(php_file, function(e){
            load_url_info("show");
        });
    }
});
