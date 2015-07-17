$(function() {
    //== 变量定义
    var _titleBox = $("input#inputTitle"),
        _summaryBox = $("textarea#inputSummary"), 
        _wikiBox = $("input#inputWiki"), 
        _homeBox = $("input#inputHome"), 
        _svnBox = $("input#inputSvn"), 
        _statusSelect = $("select#inputStatus"), 
        _authorBox = $("input#inputCreator"), 
        _membersBox = $("input#inputMembers"),
        _submitBtn = $("button#submitBtn"),
        _cancelBtn = $("button#cancelBtn");

    var _statusDict = {
        "项目提出":0,
        "需求草稿":1,
        "需求确定":2,
        "项目进行":3,
        "项目取消":4,
        "项目暂停":5,
        "项目完成":6,
    };

    //== 初始化 
    _titleBox.focus();


    //== 事件定义
    _submitBtn.click(function(e) {
        var title = _titleBox.val(),
            summary = _summaryBox.val(),
            wiki    = _wikiBox.val(),
            home    = _homeBox.val(),
            svn     = _svnBox.val(),
            status  = _statusSelect.val(),
            creator = _authorBox.val(),
            members = _membersBox.val();

        status2 = _statusDict[status];
        if (status2 == undefined) {
            alert("错误，无效的状态：" + status);
            return false;
        }

        if (title == "" || creator == "") {
            alert("错误，标题和提出者必填!");
            return false;
        }

        var php_file = "./save_project.php";
        $.post(php_file, {"id":_pId, "type":_pType, "title":title, "summary":summary, "wiki":wiki, "home":home, "svn":svn, "status":status2, "creator":creator, "members":members}, function(data) {
            if (data != '0') {
                alert(data);
                return false;
            }
            window.location.href = "../show_requirements.php";
        });

        e.preventDefault();
    });

    _cancelBtn.click(function(e) {
        window.location.href = "../show_requirements.php";
        e.preventDefault();
    });

    //== 函数定义
});
