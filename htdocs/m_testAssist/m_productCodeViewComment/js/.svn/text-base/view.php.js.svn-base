$(function() {
    // == build a files tree
    $("#tt").tree({
        onClick: function(node) {
            var file = node.attributes["file_path"];
            if (file == '') {
                return false;
            }

            $("div#file_path").html("文件路径: " + file.substr(84));
            $.getJSON("./tools/get_file_content.php", {"file_path":file}, function(data) {
                $("div#file_content").html(data[0]);
            });
        },
    });

    // == hide and show interaction
    $("div#file_content").mouseover(function(e) {
        $("div#file_tree").hide("slide", {}, 100);
        $("div#file_note").hide("slide", {direction:"right"}, 100);
    });

    $("td#src_tree_td").mouseover(function(e) {
        $("div#file_tree").show("slide", {}, 100);
    });

    $("td#note_td").mouseover(function(e) {
        $("div#file_note").show("slide", {direction:"right"}, 100);
    });

    // == interaction with note div
    function show_div() {
        $("div.comment").hide();
        var select = $("select#note_type")[0];
        var type_index = select.selectedIndex;
        var id = $(select.options[type_index]).data("container");
        $("div#" + id).show();
    }
    show_div();

    $("div#file_note select#note_type").change(function(e) {
        show_div();
    });
});
