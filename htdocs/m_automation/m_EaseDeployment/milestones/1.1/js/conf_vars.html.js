//show help info
$("a.vars_help").click(function(e) {
    show_vars_help();
    e.preventDefault();
});
function show_vars_help() {
    if ($("pre#var_help").css('display') == 'none'){
        $("pre#var_help").show();
    }
    else {        
        $("pre#var_help").hide();
    }
 }

// load vars from UI
function read_vars_from_ui() {
    _confObj.vars = new Array();

    $.each($("table#var_table tbody tr"), function(index, trObj) {
        var key = $(trObj).children().first().html();
        var value = $(trObj).children().first().next().html();
        _confObj.vars.push([key, value]);
    });
}

// deal with double click on td
$(document).on("dblclick", "table#var_table tbody tr td.kv_td", function(e) {
	// make other input>td lost focus
	$("table#var_table tbody tr td input").trigger("blur");
	var $temp_box = $("<input type='text'>");

	_varOldValue = $(this).html();
	$(this).html("");

	$temp_box.appendTo($(this)).css("width", "99.5%").val(_varOldValue);

    e.preventDefault();
    e.stopPropagation();
});

// deal with double-click on td>input
$(document).on("dblclick", "table#var_table tbody tr td.kv_td input", function(e) {
	e.stopPropagation();
});

// deal with blur on td>input
$(document).on("blur", "table#var_table tbody tr td.kv_td input", function(e) {
	_varNewValue = $(this).val();
	$(this).parent().html(_varNewValue);

    // update the _confObj
    if (_varOldValue != _varNewValue) {
        read_vars_from_ui();
    }

    e.preventDefault();
});
//copy a var
$(document).on("click", "a.copy_var", function(e) {
    var key = $(this).parent().parent().children().first().html();
    var value  = $(this).parent().parent().children().first().next().html();
    var new_row = get_var_row(key,value);
    $(new_row).insertAfter($(this).parent().parent());
    read_vars_from_ui();
    e.preventDefault();
});
// add a row
$(document).on("click", "a.add_var", function(e) {
    var new_row = get_var_row("", "");

    if ($(this).hasClass("var_thead")) {
        // click "添加" in head row
        var tbody = $(this).parent().parent().parent().next();
        $(new_row).prependTo($(tbody));
    } else {
        $(new_row).insertAfter($(this).parent().parent());
    }

    read_vars_from_ui();

    e.preventDefault();
});

// delete a row
$(document).on("click", "a.del_var", function(e) {
    var ret = confirm("Confirm to delete row ?");
    if (ret == false) return false;

    $(this).parent().parent().remove();
    read_vars_from_ui();

    e.preventDefault();
});
