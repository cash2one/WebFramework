$(function() {
    $("div#tabs").tabs();

    $("a#add_cate").click(function(e) {
        var cateName = prompt("Please Input new Cate Name:");
        if (cateName == null || cateName == "") {
            if (cateName == "")
                alert("Invalid cateName!");
            return false;
        }

        if (cateName.length < 2 || cateName.length > 10) {
            alert("错误：分类名长度不能小于2或者大于10");
            return false;
        }

        $.getJSON("./php/addCate.php", {"cateName": cateName}, function(data) {
            $("select#category").html("").load("./php/loadAllCate.php");
        });
        e.preventDefault();
    });

    $("input#submit").click(function(e) {
        var user = $("select#users").val();
        var title = $("input#title").val();
        var desc  = $("textarea#content").val();
        var cateName = $("select#category").val();
        var corePoint = $("input#corePoints").val();
        $.post("./php/addNewBug.php", {"user":user, "title":title, "desc":desc, "cateName":cateName, "corePoint":corePoint}, function(data) {
            var data = eval(data);
            if (data[0] == 1)
                alert(data[1]);
            else
                window.location.href = "./index.php";
        });
    });

    $("input#format2").click(function(e) {
        $("textarea").val("[平台]\n\n\n[问题描述]\n\n\n[问题确认过程]\n\n\n\n[相关bug]\n"); 
    });

    $("input#format3").click(function(e) {
        $("textarea").val("[平台]\n\n\n[问题描述]\n\n\n[问题确认过程]\n\n\n\n[相关bug]\n"); 
    });

    $("input#format4").click(function(e) {
        $("textarea").val("[平台]\n\n\n[问题描述]\n\n\n[问题确认过程]\n\n\n\n[相关bug]\n"); 
    });
});
