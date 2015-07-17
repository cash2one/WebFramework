$(function() {
    $("#process_btn").click(function(e){
        $("#result").text('');
        $("#status").text('');
        //调用php处理函数处理
        _host = $("#hostName").val();
        _path = $("#path").val();
        _whitelist = $("#whiteList").val();
        var php_file = "../php/filter_process.php";
        // alert('php file is:'+php_file);
        $.getJSON(php_file,{"host":_host,"path":_path,"whitelist":_whitelist},function(data){
            //将data中的数据显示出来
            data = eval(data);
            if(data.length ==0){
                $("#status").text('结果为空，原因：1.输入文件不存在或者输入文件是目录;2.无异常');
            }
            for(var i=0; i<data.length; i++) {
                rowNum = data[i].rowNum;
                content =data[i].content;
                $("#result").append(rowNum+"  ||  " +content+"\n");
            }
        });
    });
});
