$(function() {

    // === global variables ===
    var _root_dir      = null;
    var _selected_file = null;
    var _json_data     = null; //json format data from server
    var _cur_bean_dict = null; //bean dict 
    var _user     = null;
    var _password = null;
    var _svn_url  = null;
    var _product  = null;
    var _name_dict = {};

    var $nav_container          = $("div#div_nav");
    var $bean_list_container    = null; //td with bean names
    var $file_name_container    = $("table tr td#filename pre");
    var $file_content_container = $("table tr td#file_content pre");
    var $bean_table = $("table#code_info");

    // map for product and src code dir
    var _prod_src_dir_dict = {
        "finance": "src",
        "image": "src/java",
        "antifrauder": "src/java",
        "omedia": "src/java",
        "adpublish": "src",
        "adpublish_service": "src",
        "audit2": "src/java",
        "adagent": "src",
        "account": "src/java",
        "dsp": "src/java",
        "dsp_publish": "src",
        "mail": "src/java",
        "channel": "src/java",
        "dict": "src/java",
        "union": "src/java",
        "search": "src/java",
    };

    // get id-name map
    $.each($("select#product option"), function(idx, opt) {
        var id = $(opt).attr('id');
        var name = $(opt).html();
        _name_dict[id] = name;
    });

    // === global functions ===
    function get_json_elements_count(jsonObj) {
        var index = 0;
        for (var element in jsonObj) {
            index += 1;
        }
        return index;
    }

    function get_select_id(selectObj) {
        var idx = $(selectObj).get(0).selectedIndex;
        return $("option", selectObj).get(idx).id;
    }

    function check_input() {
        _user = $("input#user").val();
        _password = $("input#password").val();
        _svn_url  = $("input#svn_path").val();

        // trim spaces
        _user = $.trim(_user);
        _password = $.trim(_password);
        _svn_url = $.trim(_svn_url);

        var valid = true;
        if (_user == "" || _user == null) {
            alert("Error: username empty !");
            valid = false;
        } else if (_password == "" || _password == null) {
            alert("Error: password empty !");
            valid = false;
        } else if (_svn_url == "" || _svn_url == null) {
            alert("Error: svn empty !");
            valid = false;
        }
        return valid;
    }

    $("table#user_log").load("php/read_user_log.php", function(e) {
        $.each($("table#user_log td.prod"), function(idx, td) {
            var id = $(td).html();
            var name = _name_dict[id];
            $(td).html(name);
        });
    });

    // click on view button
    $("input#view").click(function(e) {
        var is_input_valid = check_input();
        if (! is_input_valid) return false;

        var $viewBtn = $(this);
        $viewBtn.attr("disabled", true);
        $("table#user_log").hide();
        $bean_table.html("");
        $bean_table.append("<tr><td><font color='red'>请稍等一会儿...</font></td></tr>");

        _product = get_select_id($("select#product"));
        var php_file = "php/parse_project_xml.php";

        $.getJSON(php_file, {"svn": _svn_url, "prod": _product, "user": _user, "password": _password}, function(json_data) {
            $bean_table.html("");
            var ret = json_data[0];
            if (ret != "0") {
                alert(json_data[1]);
                $viewBtn.attr("disabled", false);
                return false;
            }

            // get root dir for code
            _root_dir = json_data[2];

            // read json data from server
            _json_data = json_data[1]; //assign value to global var json_data
            var files_count = get_json_elements_count(_json_data);

            var is_first_row = true;
            for (var xml_file in _json_data) {
                // load xml files 
                if (is_first_row) {
                    $bean_table.append("<tr><td class='file'><a href=''>" + xml_file + "</a></td><td id='bean_list' rowspan='" + files_count + "'></td></tr>");
                    $bean_list_container = $("table td#bean_list");    
                    is_first_row = false;
                } else {
                    $bean_table.append("<tr><td class='file'><a href=''>" + xml_file + "</a></td></tr>");
                }
            }
            
            $viewBtn.attr("disabled", false);
        });
    });

    // handle click on file
    $(document).on("click", "td.file a", function(e) {
        _selected_file = $(this).html();
        $nav_container.html("");
        $nav_container.append("<a href=''>" + _selected_file + "</a>");

        _cur_bean_dict = _json_data[_selected_file];
        show_results(null, true);
        e.preventDefault();
    });

    // handle click on bean
    $(document).on("click", "td#bean_list a.bean", function(e) {
        var bean_name = $(this).html();
        var elements_count = get_json_elements_count(_cur_bean_dict[bean_name][4]);
        if (elements_count > 0) {
            _cur_bean_dict = _cur_bean_dict[bean_name][4];
            $nav_container.append("<a href=''>" + bean_name + "</a>");
            show_results(bean_name, true);
        } else {
            show_results(bean_name, false);
        }
        e.preventDefault();
    });

    // click on div nav links
    $(document).on("click", "div#div_nav a", function(e) {
        var bean_name = $(this).html();
        $(this).nextAll().remove();

        _cur_bean_dict = _json_data[_selected_file];
        $.each($(this).parent().children(), function(idx, link) {
            if (idx == 0) {
                return true; //continue
            }
            _cur_bean_dict = _cur_bean_dict[$(link).html()][4]; 
        });
         
        show_results(bean_name, true); 
        e.preventDefault();
    });

    // show results 
    function show_results(bean_name, show_ref_bean) {
        if (show_ref_bean == true) {
            // show ref bean list
            $bean_list_container.html("");
            for (var bean in _cur_bean_dict) {
                $bean_list_container.append("<a class='bean' href=''>" + bean + "</a> <a class='view_src' id='" + bean + "' href=''>查看源码</a><br>");
            }
        }

        // clear contents for file name and file content
        $file_name_container.html("");
        $file_content_container.html("");

        if (bean_name != null) {
            $file_content_container.html("<font color='red'>该文件不存在!</font>");

            var java_dir = _prod_src_dir_dict[_product];
            var file_path = _root_dir + "/" + java_dir + "/" + bean_name.replace(/\./g, "/") + ".java";
            file_path = file_path.substr(3);
            $file_name_container.html("<b>class name: </b> " + bean_name + "<br><b>java file: </b>" + file_path);
            $file_content_container.load(file_path);
        }
    }

    // show source code
    $(document).on("click", "a.view_src", function(e) {
        var bean_name = $(this).attr("id");
        show_results(bean_name, false);
        e.preventDefault();
    });

});
