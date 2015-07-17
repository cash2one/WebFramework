var _type        = "";
var _delete_name = "";

$(function() {
    var _username = "";
    var _user_info;

    // save the issue same as http://stackoverflow.com/questions/16845991/plupload-in-jquery-ui-dialog-cant-click-to-add-files
    $("#tt").css({"z-index": 99999});

    $('#tt').datagrid({
        "onClickCell": function(rowIndex, field, value) {
            if (_type == "delete") {
                $.get("./php/book_delete.php", {"tname": _delete_name}, function(e) {
                    $('#tt').datagrid('reload');
                });
            }
            _type = "";
        }, 
        "onClickRow": function(rowIndex, rowData) {
            $("label#book_name").html(rowData.book_name);
        }, 

        queryParams: {
            "filter_name": "",
        },
    });

    $("input#filter_name").keyup(function(e) {
        var filter_name = $(this).val();
        if (filter_name.length < 2) 
            filter_name = "";

        $('#tt').datagrid('loadData',[]);
        $('#tt').datagrid({
            queryParams: {
                "filter_name": filter_name,
            }
        });
    }); 

    $("select#pagination").change(function(e) {
        var pos = $(this).val();
        $('#tt').datagrid('loadData',[]);
        $('#tt').datagrid({pagePosition:pos});
    });

    $( "#tabs" ).tabs({
        activate: function(event, ui) {
            var active = $( "#tabs" ).tabs( "option", "active" );

            if (active == 0) {
                $('#tt').datagrid('reload');
            } else if (active == 2) {
                read_user_info()
            }
        },
    });

    $("#uploader").pluploadQueue({
        // General settings
        runtimes : 'gears,flash,silverlight,browserplus,html5',
        url: './js/plupload-2.1.1/examples/upload.php',
        max_file_size : '180mb',
        chunk_size : '1mb',
        unique_names : true,
        multi_selection: true,
        dragdrop: true,
 
        // Resize images on clientside if we can
        resize : {width : 320, height : 240, quality : 90},
 
        // Specify what files to browse for
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"},
            {title : "Zip files", extensions : "zip"},
            {title : "Tar files", extensions : "tar.gz"},
            {title : "Rar files", extensions : "rar"},
            {title : "Pdf files", extensions : "pdf"},
            {title : "Doc files", extensions : "doc,txt"},
            {title : "Docx files", extensions : "docx"},
            {title : "Chm files", extensions : "chm"},
            {title : "Ppt files", extensions : "ppt"},
            {title : "Pptx files", extensions : "pptx"},
            {title : "Aac files", extensions : "aac"},
            {title : "Excel files", extensions : "xls,xlsx"}
        ],
 
        // Flash settings
        flash_swf_url : 'js/plupload-2.1.1/js/Moxie.swf',
 
        // Silverlight settings
        silverlight_xap_url : 'js/plupload-2.1.1/js/Moxie.xap',
    
        init: {
            FilesAdded: function(up, files) {
                if (_username == "") {
                    _username = prompt("请输入你的大名(否则以匿名用户上传):");
                    if (_username == "" || _username == null)
                        _username = "Anonymous";
                }
            },

            FileUploaded: function(up, file, info) {
                if (info.status == "200") {
                    var php_file = "./php/save_name.php";
                    $.post(php_file, {"target_name": file.target_name, "raw_name": file.name, "user": _username});
                }
            },

            StateChanged: function(up) {
                // Called when the state of the queue is changed
                // log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
            },
        },
    });

    function read_user_info() {
        var php_file = "./php/read_user_info.php";
        $.getJSON(php_file, function(data) {
            $.each(data, function(idx, sub_arr) {
                data[idx][0] = parseInt(data[idx][0]);
            });
            _user_info = data;
            $("div#rank-chart").css("min-height", "150");
            $("div#rank-chart").css("height", "" + (_user_info.length * 5) + "%");
            draw_chart();
        });
    }

    function draw_chart() {
        // draw the chart
        $("#rank-chart").html("");
        var plot1b = $.jqplot('rank-chart', [_user_info], {
            title: '上传排行榜',
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

});

function onDelete(obj) {
    var ret = confirm("确定删除?");
    if (!ret) return false;
    _type = "delete";
    _delete_name = obj.getAttribute("data-tname");
}
