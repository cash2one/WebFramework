function showStatus() {
    $("div#status").load("./showStatus.php");
}

$(function() {

    $("input#addNewVersion").click(function(e) {
        var serviceName = $("select#prodList").val();
        var verName     = $("input#version").val();
        if (serviceName == "" || verName == "") {
            alert("错误：输入不能为空!");
            return false;
        }

        $.post("./addNewVersion.php", {"serviceName": serviceName, "verName": verName}, function(data) {
            data = eval(data);
            if (data[0] == 1) {
                alert(data[1]); 
            } else {
                window.location.reload();
            }
        }); 
    });

    $("select#serviceList").change(function(e) {
        var serviceName = $(this).val();
        $("select#versionList").html("").load("./loadVersions.php", {"prodName": serviceName});
    });

    $("input#startRun").click(function(e) {
        var serviceName = $("select#serviceList").val();
        var verName     = $("select#versionList").val();
        if (serviceName == "" || verName == "" || verName == null) {
            alert("错误：输入不能为空!");
            return false;
        }

        $("div#runInfo").html("").load("./runTool.php", {"prodName":serviceName, "version":verName});
    });

    $("input#stopRun").click(function(e) {
        $("div#runInfo").html("").load("./stopTool.php");
    });

    var handle = setInterval("showStatus()", 1000);
});
