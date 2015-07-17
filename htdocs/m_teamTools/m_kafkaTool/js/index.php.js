$(function() {
    $(".btn").live("click",function(){
        // 下面这行代码就是你要的ID属性
        var id = $(this).attr("id");
        var idList = id.split("|");
        var cmd = idList[0];
        var topic = idList[1];
        var partition = idList[2];
        var leader = idList[3];
       // var zkAddr = $("#zkAddr").val();
        var zkAddr = $("#zk").text();

        if (leader == '-'){
            alert("leader is invalid.");
            return;
        }
        if (cmd == 'getOffsetMsg' && leader != '-') {
            var merge = '#setOffset-'+topic+'-'+partition+'-'+leader;
            var offset = $(merge).val();
            if(offset == ""){
                alert("offset is empty.");
            }else {
                //将topic partition leader 和 offset传到php中，返回mgs的内容
                $.post("./php/process2.php",{"zkAddr":zkAddr,"topic":topic,"partition":partition,"leader":leader,"offset":offset,"cmd":"getOffsetMsg"},function(data){
                    var strStart = data.indexOf("bin/kafka-tool");
                   // var strStart = data.indexOf("RESULT message");
                    
                    var str = data.substr(strStart);
                    $('#msg-'+topic+'-'+partition+'-'+leader).text(str);
                });
            }
        } 
        if(cmd == 'getLastOffset') {
            //将topic partition leader传到php中，返回last offset 
            $.post("./php/process2.php",{"zkAddr":zkAddr,"topic":topic,"partition":partition,"leader":leader,"cmd":"getLastOffset"},function(data){
                var strStart = data.lastIndexOf("LAST offset");
                var str = data.substr(strStart);
                $('#OffsetLabel-'+topic+'-'+partition+'-'+leader).text(str);
            });
        }
    });

});
