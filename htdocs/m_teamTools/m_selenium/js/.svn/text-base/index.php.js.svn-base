$(function() {

    // === 变量定义区
    var _user_info;
    var _content_tr = $("<tr id='content_tr'><td colspan='4'></td></tr>");
    var _submit_type = "";
    var _submit_postid = "-1";
    var _obj_for_edit = {};
    var _historyid = -1;
    var _email = $("#email");

    var _title = $("#title"),
        _content = $("#content"),
        _version = $("#versions"),
        _author = $("#author"),
        _tip = $(".validateTips");

    // === 初始化区
    $( "#tabs" ).tabs({
        activate: function(event, ui) {
            var active = $( "#tabs" ).tabs( "option", "active" );
            if (active == 1) {
                read_user_info();
            } else if (active == 2) { 
                get_trashlist_info();
            }
        },
    });

    // show user titles table
    $('#example').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "./php/read_user_titles.php"
    });

    $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 600,
        width: 700,
        modal: true,
        buttons: {
            "提交": function() {
                var php_file = "./php/update_post_info.php";

                var title = _title.val();
                var content = _content.val();
                var author = _author.val();
                var send_mail = (_email.attr("checked") == "checked") ? 1 : 0;
                if (title == "" || content == "" || author == "") {
                    _tip.addClass("tip-error");
                    return false;
                }

                if (_submit_type == "edit") {
                    var edited = false;
                    $.each(_obj_for_edit, function(idx, row) {
                        if (row["historyid"] == _historyid) {
                            var title2 = row["title"]; 
                            var content2 = row["content"];
                            var author2 = row["author"];
                            if (title != title2 || content != content2 || author != author2) {
                                edited = true;
                                return false;
                            }
                        }
                    });

                    if (edited == false) {
                        php_file = "./php/rollback_post_info.php";
                        $.get(php_file, {"postid":_submit_postid, "historyid":_historyid}, function(ret) {
                            location.reload();
                        });
                        return false;
                    }
                }

                $.get(php_file, {"title": title, "author":author, "content":content, "postid":_submit_postid, "mail_send": send_mail}, function(ret) {
                    location.reload();
                });
            },
            "取消": function() {
                $(this).dialog( "close" );
            }
        },
        close: function() {
            $("#dialog-form input").val("");
            $("#dialog-form textarea").val("");
            _tip.removeClass("tip-error");
        }
    });

    // === 函数定义区
    function read_user_info() {
        var php_file = "./php/read_user_info.php";
        $.getJSON(php_file, function(data) {
            _user_info = data;
            $("div#rank-chart").css("height", "" + (_user_info.length * 5) + "%");
            draw_chart();
        });
    }

    function draw_chart() {
        // draw the chart
        $("#rank-chart").html("");
        var plot1b = $.jqplot('rank-chart', [_user_info], {
            title: '分享排行榜',
            axesDefaults: {
                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                tickOptions: {
                    fontFamily: 'Georgia',
                    fontSize: '10pt',
                    angle: -30
                },
            },
            seriesDefaults: {
                renderer:$.jqplot.BarRenderer,
                rendererOptions: {
                    barDirection: 'horizontal'
                }
            },
            axes: {
                yaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                }
            }
        });
    }
    
    // == 获取回收站文章数据
    function get_trashlist_info() {
        $("#recycleTable tr:gt(2)").remove();
        var php_file = "./php/get_post_list.php";
        $.getJSON(php_file,{"deleted":"true"} ,function(data) {
               $.each(data,function(i,n) {
                var trTitle = $("#recycleTable tr").eq(1).clone();
                trTitle.css('display','');
                var trContent = $("#recycleTable tr").eq(2).clone();
                trTitle.children("td:eq(0)").text(data[i].postid);
                trTitle.children("td:eq(1)").text(data[i].title);
                trTitle.children("td:eq(2)").text(data[i].author);
                trTitle.children("td:eq(3)").text(data[i].createTime);
                trContent.children("td:eq(0)").text(data[i].content);
                trTitle.insertAfter("#recycleTable tr:last");
                trContent.insertAfter("#recycleTable tr:last");
            });     

        });     
    }

    // === 事件处理区
    $(document).on("click", "table#example tr td a[name='delete']", function(e) {
        var ret = confirm("提示:确定删除该分享?");
        if (ret != true) {
            return false;
        }

        _submit_postid = $("input[type='hidden']", $(this).parent()).data('id');
        var php_file = "./php/remove_post.php";
        $.get(php_file, {"postid": _submit_postid}, function(data) {
            location.reload();
        });

        e.preventDefault();
    });

    $(document).on("click", "table#example tr td a[name='new']", function(e) {
        _submit_type = "new";
        _submit_postid = "-1";
        $("div#dialog-form [name='for_edit']").hide();
        $("div#dialog-form [name='for_new']").show();
        $("div#dialog-form").dialog("option", "title", "添加分享");
        $( "#dialog-form" ).dialog("open");
        e.preventDefault();
    });

    $(document).on("click", "table#example tr td a[name='edit']", function(e) {
        _submit_type = "edit";
        _submit_postid = $("input[type='hidden']", $(this).parent()).data('id');
        _historyid = $("input[type='hidden']", $(this).parent()).data('histid');

        var php_file = "./php/get_history_posts.php";
        $.getJSON(php_file, {"postid": _submit_postid}, function(data) {
            _version.html("");
            _obj_for_edit = data;
            $.each(_obj_for_edit, function(idx, row){
                if (row['historyid'] == _historyid) {
                    _title.val(row["title"]); 
                    _content.val(row["content"]);
                    _author.val(row["author"]);
                    _version.append("<option selected data-historyid='" + row["historyid"]  + "'>" + row["createTime"] + "</option>");
                } else {
                    _version.append("<option data-historyid='" + row["historyid"] + "'>" + row["createTime"] + "</option>");
                }
            });

            $("div#dialog-form [name='for_edit']").show();
            $("div#dialog-form [name='for_new']").hide();
            $("div#dialog-form").dialog("option", "title", "编辑分享");
            $( "#dialog-form" ).dialog("open");
        });
        e.preventDefault();
    });

    _version.change(function(e) {
        _historyid = $("option:eq(" + this.selectedIndex + ")", this).data("historyid");
        $.each(_obj_for_edit, function(idx, row){
            if (row['historyid'] == _historyid) {
                _title.val(row["title"]); 
                _content.val(row["content"]);
                _author.val(row["author"]);
            }
        });
    });

    $(document).on("click", "table#example tr td:first-child", function(e) {
        if ($(this).parent().next().attr("id") == "content_tr") {
            $(this).parent().next().remove();
            return false;
        } else if ($(this).parent().attr("id") == "content_tr") {
            return false;
        }

        var postid = $("input[type='hidden']", $(this).parent()).data('id');
        var php_file = "./php/get_post_info.php";
        $.getJSON(php_file, {"postid": postid}, function(data) {
            $("td", _content_tr).html("<pre>" + data["content"] + "</pre>");
        });
        $(this).parent().after(_content_tr);

        e.preventDefault();
    });
  
    $(document).on("click",".titleRow",function(e){
        $(this).next("tr").toggle();
    });

    $(document).on("click",".restore",function(e){
        var tr=$(this).parent().parent();
        var postid=tr.children("td:eq(0)").html();
        var php_file = "./php/recover_post.php";
        // 调用sqlite接口，更新状态，并且更新回收站数据
        $.get(php_file, {"postid": postid}, function(data) {
            if(data=="1"){
                tr.next().remove();
                tr.remove();
                alert("操作成功");
               // 刷新页面
               // get_trashlist_info();
            }
        });
        e.preventDefault();
    });
});
