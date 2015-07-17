$(function() {
    $("#view_content").click(function(e){
        $("#contents").text("");
        _op = "view_content";
        _whitelistName = $.trim($("#listName_view").val());
        if(_whitelistName == ""){
            alert("请输入白名单名称");
        }else {
            var php_file = "./controller.php";
            $.getJSON(php_file,{"op":_op,"whitelistName":_whitelistName,"content":""},function(data){
                $.each(data,function(i,n) {
                    $("#contents").append(data[i]+"<br/>");
                });
            });
        }
    });

    $("#new_whitelistName").click(function(e){
        _op = "new_name";
        _whitelistName = $.trim($("#listName_view").val());
        if(_whitelistName == ""){ 
            alert("请输入白名单名称"); 
        }else { 
            var php_file = "./controller.php";
            alert("phpfile is "+php_file+" add name is "+_whitelistName+" op is "+_op);
            $.getJSON(php_file,{"op":_op,"whitelistName":_whitelistName,"content":""},function(data){
                $.each(data,function(i,n) {
                    alert(data[i]);
                    window.location.reload();  
                });
            });     
        }

    });

    $("#add_content").click(function(e){
        _op = "add_content";
        //alert("1");
        _whitelistName = $.trim($("#whitelistNames_edit").val());
        _content = $.trim($("#whitelistContent").val());
        if(_whitelistName == "" || _content == ""){
            alert("请输入需添加的内容");
        }else {
            var php_file = "./controller.php";
            $.getJSON(php_file,{"op":_op,"whitelistName":_whitelistName,"content":_content},function(data){
                alert(data[1]);
            });
        }        
    });

    $("#delete_content").click(function(e){
        _op = "delete_content";
        _whitelistName = $.trim($("#whitelistNames_edit").val());
        _content = $.trim($("#whitelistContent").val());
        if(_whitelistName == "" || _content == ""){
            alert("请输入需添加的内容");
        }else {
            var php_file = "./controller.php";
            $.getJSON(php_file,{"op":_op,"whitelistName":_whitelistName,"content":_content},function(data){
                alert(data[1]);
            });
        }
        
    });
});
