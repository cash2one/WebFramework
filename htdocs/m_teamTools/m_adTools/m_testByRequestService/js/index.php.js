// =================== Variables =============================
var _service_type = null;
var _service_host = null;
var _service_port = null;

var _url_type = null;
// 机群的机器和文件
var _url_host = null;
var _url_file = null;

// 手动输入url
var _url_manual_lists = [];

var _req_type = null;
// 自动发送请求间隔
var _req_interval = null;

var _ldap = null;
var _password = null;

// save the loaded urls
var _url_list = [];
var _url_idx = 0;
var _url_cnt = 0;

// =================== Functions =============================
function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

function show_result() {
    if (_url_cnt > 0) {
        if (_url_idx < 1) {
            _url_idx = 1;
        } else if (_url_idx > _url_cnt) {
            _url_idx = _url_cnt;
        }
        
        $("span#num1").html(_url_idx);
        $("span#num2").html(_url_cnt);

        var url = "http://" + _service_host + ":" + _service_port + _url_list[_url_idx - 1];
        $("div#url_content pre").html("<a target='_blank' href='" + url + "'>" + url + "</a>");
        $("iframe#web_show").attr("src", url);

    } else {
        _url_idx = 0;

        if ($("iframe#web_show").attr("src") != "") {
            $("span#num1").html(_url_idx);
            $("span#num2").html(_url_cnt);

            $("div#url_content pre").html("");
            $("iframe#web_show").attr("src", "");
        }
    }
}

function input_check() {
    _service_type = $("input[name=service_type]:checked").attr("id");
    _service_host = $("select#machine_name").val();

    _service_port = $("input#service_port").val();
    if (_service_port == ""){
        alert("Error: 服务的端口没有设定!");
        return false;
    }

    _url_type = $("input[name=url_src]:checked").attr("id");

    _url_host = $("input#url_src_host").val();
    _url_file = $("input#url_src_path").val();

    if (_url_type == "src_host") {
        if (_url_host == "") {
            alert("Error: 集群文件机器名为空!");
            return false;
        }
        if (_url_file == "") {
            alert("Error: 集群文件路径为空!");
            return false;
        }

    } else if (_url_type == "src_manual") {
        var $trs = $("table#manual_url_table tr");
        if ($trs.length == 0) {
            alert("Error: 手动输入Url串个数为0！");
            return false;
        }

        _url_manual_lists = [];
        $.each($trs, function(idx, tr) {
            _url_manual_lists.push($(tr).data("url"));
        });
    }

    _req_type = $("input[name=req_style]:checked").attr("id");
    _req_interval = parseInt($("input#req_interval").val()) + 0;
    if (_req_type == "req_auto" || _req_type == "req_rand") {
        if (_req_interval < 1) {
            alert("Error: 请设置正确的时间间隔!");
            return false;
        }
    }

    _ldap = $("input#username").val();
    _password = $("input#password").val();
    if (_url_type == "src_online") {
        if (_ldap == "" || _password == "") {
            alert("Error: 使用线上AccessLog需要提供ldap和密码!");
            return false;
        }
    }
}

function write_log(content) {
    var php_file = "./php/write_user_log.php";
    $.post(php_file, {"username": _ldap, "machine_info": _service_host + ":" + _service_port, "content": content});
}

// =================== Main Logic =============================
$(function() {
    // ==== init period =====
    $("div#result_div").hide();

    $("div#src_host_div").hide();
    $("div#src_manaul_div").hide();
    $("div#src_upload_div").hide();
    $("tr#src_detail_tr").hide();

    $("span#req_auto_interval").hide();

    $("a#show_conf_div").addClass("link_disabled");
    $("a#show_result_div").addClass("link_enabled");

    // read user log
    $("tbody#log_tbody").load("./php/read_user_log.php");

    // ===== handlers for clicks on nav links
    $("a#show_conf_div").click(function(e) {
        if ($(this).hasClass("link_disabled")) {
            return false;
        }       
        $(this).removeClass("link_enabled").addClass("link_disabled");
        $("a#show_result_div").removeClass("link_disabled").addClass("link_enabled");
        $("div#conf_div").show();
        $("div#result_div").hide();
        e.preventDefault();
    });

    $("a#show_result_div").click(function(e) {
        if ($(this).hasClass("link_disabled")) {
            return false;
        }       
        $(this).removeClass("link_enabled").addClass("link_disabled");
        $("a#show_conf_div").removeClass("link_disabled").addClass("link_enabled");
        $("div#result_div").show();
        $("div#conf_div").hide();
        e.preventDefault();
    });

    // ===== handlers on radio buttons of 请求URL来源
    $("input#src_online").click(function(e) {
        $("tr#user_input_tr").show();
        $("tr.access_type_tr").show();
        $("tr#src_detail_tr").hide();
    });

    $("input#src_host").click(function(e) {
        $("tr#user_input_tr").hide();
        $("tr.access_type_tr").hide();
        $("tr#src_detail_tr").show();
        $("div#src_host_div").show().siblings().hide();
    });

    $("input#src_manual").click(function(e) {
        $("tr#user_input_tr").hide();
        $("tr.access_type_tr").hide();
        $("tr#src_detail_tr").show();
        $("div#src_manaul_div").show().siblings().hide();
    });

    $("input#src_upload").click(function(e) {
        $("tr#src_detail_tr").show();
        $("div#src_upload_div").show().siblings().hide();
    });

    $("a#add_url").click(function(e) {
        var url = $("input#url_src_input").val();
        if (url == "") {
            alert("Url为空!");
            return false;
        }
        $("input#url_src_input").val("");
        var $tr = $("<tr><td class='manual_url'><pre>" + url + "</pre></td><td><a href='' class='url_del'>删除</a></td></tr>");
        $tr.data('url', url);
        $("table#manual_url_table").append($tr);

        e.preventDefault();
    });

    $(document).on("click", "a.url_del", function(e) {
        $(this).parent().parent().remove();
        e.preventDefault();
    });

    // ===== handlers on radio buttons of 请求方式
    $("input.auto").click(function(e) {
        $("span#req_auto_interval").show();
    });

    $("input#req_manu").click(function(e) {
        $("span#req_auto_interval").hide();
    });
    
    // ===== handlers on click for doWork
    $("input#doWork").click(function(e) {
        var ret = input_check();
        if (ret == false) {
            return ret;
        }

        _url_idx = 0;
        _url_cnt = 0;
        show_result();

        var $currentBtn = $(this);
        $currentBtn.attr('disabled', true);
    
        // clear url
        _url_list = [];
        $("a#show_result_div").click();
        $("iframe#web_show").attr("src", "wait.html");

        // 判断请求URL来源
        if (_url_type == "src_online") {
            write_log(_service_type);
            var php_file = "./php/query_online_log.php";
            $.getJSON(php_file, {"service_type": _service_type, "username": _ldap, "password": _password}, function(data) {
                var status = data.status;
                if (status != 0) {
                    alert("Error: " + data.message);
                    $currentBtn.attr('disabled', false);
                    return false;
                }

                _url_list = data.lines;
                _url_cnt = _url_list.length;
                _url_idx = 1;
                show_result();
        
                $currentBtn.attr('disabled', false);
            });

        } else if (_url_type == "src_host") {
            write_log(_url_host + ":" + _url_file);

            var php_file = "./php/read_remote_file.php";
            $.getJSON(php_file, {"host": _url_host, "path": _url_file}, function(data) {
                var status = data.status;
                if (status != 0) {
                    alert("Error: " + data.message);
                    $currentBtn.attr('disabled', false);
                    return false;
                }

                _url_list = data.lines;
                _url_cnt = _url_list.length;
                _url_idx = 1;
                show_result();
        
                $currentBtn.attr('disabled', false);
            });

        } else if (_url_type == "src_manual") {
            write_log(_url_manual_lists.join("<br>"));
            _url_list = _url_manual_lists;
            _url_cnt = _url_list.length;
            _url_idx = 1;
            show_result();
            $currentBtn.attr('disabled', false);
        }
    });

    // ===== handlers on click for url links
    $("a#pre_url").click(function(e) {
        _url_idx -= 1;
        show_result();
    
        e.preventDefault();
    });

    $("a#next_url").click(function(e) {
        _url_idx += 1;
        show_result();
        e.preventDefault();
    });

    $("a#cur_url").click(function(e) {
        show_result();
        e.preventDefault();
    });

    $("a#first_url").click(function(e) {
        _url_idx = 1;
        show_result();
        e.preventDefault();
    });

    $("a#last_url").click(function(e) {
        _url_idx = _url_cnt;
        show_result();
        e.preventDefault();
    });

    $("a#rand_url").click(function(e) {
        _url_idx = parseInt(Math.random() * _url_cnt) + 1;
        show_result();
        e.preventDefault();
    });

    // click on url show
    $("input#url_show").click(function(e) {
        if ($(this).attr("checked")) {
            $("div#url_content").show();
        } else {
            $("div#url_content").hide();
        }
    });
});
