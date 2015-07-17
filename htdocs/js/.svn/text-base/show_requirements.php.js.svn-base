// == 初始化部分
$(function() {

    // == 变量定义区
    var _title     = "";
    var _select_id = -1;

    // == 初始化
    $("#dg").datagrid({
        view: detailview,

        toolbar: [{
                text:'Add',
                iconCls:'icon-add',
                handler:function(){addClick();}
            },{
                text:'Remove',
                iconCls:'icon-remove',
                handler:function(){rmClick();}
            },{ 
                text:'Reload',
                iconCls:'icon-reload',
                handler:function(){reloadClick();}
            },{ 
                text:'Edit',
                iconCls:'icon-edit',
                handler:function(){editClick();}
        }],

        detailFormatter:function(index,row){
            return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
        },

        onExpandRow: function(index,row){
            $('#ddv-'+index).panel({
                height:200,
                border:false,
                cache:false,
                href:'./php/load_summary.php?id='+row.id,
                onLoad:function(){
                    $('#dg').datagrid('fixDetailRowHeight',index);
                }
            });
            $('#dg').datagrid('fixDetailRowHeight',index);
        },

        onClickRow: function(rowIndex, rowData) {
            _title = rowData.title;
            _select_id = rowData.id;
        },

        onBeforeLoad: function(param) {
            _title     = "";
            _select_id = -1;
        },
    });

    // == 函数定义区
    function addClick() {
        window.location.href = "./php/newOrEdit.php?type=new";
    }

    function rmClick() {
        if (_select_id == -1) {
            alert("错误，你还没有选中任何行来删除");
            return false;
        }

        var ret = confirm("确定删除(" + _title + ")?");
        if (!ret) {
            return false;
        }        

        $.get("./php/remove_project.php", {"id": _select_id}, function(e) {
            $("#dg").datagrid("reload");
        });
    }

    function reloadClick() {
        $("#dg").datagrid("reload");
    }

    function editClick() {
        if (_select_id == -1) {
            alert("错误，你还没有选中任何行来编辑");
            return false;
        }
        window.location.href = "./php/newOrEdit.php?type=edit&id=" + _select_id;
    }
});
