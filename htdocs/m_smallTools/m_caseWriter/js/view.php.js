$(function() {

    var id = $("table#case_items_table").attr("class");
    var php_file = "./php/load_cases.php";
    $("tbody#case_items_tbody").load(php_file, {"id": id});


    $("a#new_cases").click(function(e) {
        $("div#view").hide();
        $("div#new_case").show();
        $("div#new_case").load("./php/new_case.php", {"cate": "", "case_title": "", "comment": "", "status": ""});

        e.preventDefault();
    });

    $(document).on("click", "a#case_add", function(e) {
        var $tr = $(this).parent().parent();
        var case_cate = $tr.children().eq(0).html();
        var case_title = $tr.children().eq(1).html();
        var comment = $tr.children().eq(2).html();
        var status = $tr.children().eq(3).html();

        $("div#view").hide();
        $("div#new_case").show();
        $("div#new_case").load("./php/new_case.php", {"cate": case_cate, "case_title": case_title, "comment": comment, "status": status});

        e.preventDefault();
    });

});

var case_list = [];
function show_cases() {
    var $case_container = $("tbody#case_items_tbody");
    $case_container.html("");
    $.each(case_list, function(idx, row) {
        var tr = "<tr><td>" + row[0] + "</td><td>" + row[1] + "</td><td>" + row[2] + "</td><td>" + row[3] + "</td><td><a href='' id='case_del'>删除</a> <a href='' id='case_cp'>拷贝</a> <a href='' id='case_edit'>编辑</a></td></tr>";
        $case_container.append(tr);
    });
    
}

