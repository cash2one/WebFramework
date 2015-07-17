var _checkedList = [];

// 定义在jquery主函数里面，主要是为了避免页面没有及时加载
var result_page1, result_page2, left_page, right_page;

function load_rows() {
    if (_checkedList.length == 0) return false;

    if (_checkedList.length == 1) {
        $.post("./load_comparable_row.php", {"path1": _checkedList[0]}, function(data) {
            $(left_page).html(data);
        });

    } else if (_checkedList.length == 2) {
        $.post("./load_comparable_rows.php", {"path1": _checkedList[0], "path2": _checkedList[1]}, function(data) {
            var data = eval(data);
            $(left_page).html(data[0]);
            $(right_page).html(data[1]);
        });
    }
}

$(function() {

    result_page1 = window.parent.frames["dresult1"].document;
    result_page2 = window.parent.frames["dresult2"].document;

    left_page = $(result_page1).find("div");
    right_page = $(result_page2).find("div");

    $("#tt").tree({
        checkbox: true,
        onlyLeafCheck: true,
        onBeforeCheck: function(node, checked) {
            if (node.attributes["type"] == 1) {
                var diff_path = node.attributes["diff_path"];
                if (_checkedList.length == 2 && checked == true) {
                    alert("你不能再多选了,最多选两个!");
                    return false;
                } else if (checked == false) {
                    var path = _checkedList.pop();
                    if (path != diff_path) {
                        _checkedList = [path];
                    } 
                    $(left_page).html("");
                    $(right_page).html("");
                    load_rows();
                } else {
                    _checkedList.push(diff_path);
                    load_rows();
                }
            }
        },
    });

    // 为了解决frame不能及时载入，定义两次
    result_page1 = window.parent.frames["dresult1"].document;
    result_page2 = window.parent.frames["dresult2"].document;

    // 每60秒刷新一下页面
    setInterval("load_rows();", 60000);
});
