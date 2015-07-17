// judge whether an element in array
function in_array(array, element) {
    for (var idx = 0; idx < array.length; idx ++) {
        var element_in_array = array[idx];
        if (element == element_in_array) {
            return true;
        }
    }
    return false;
}

// remove element from array
function array_remove(array, element) {
    for (var i = 0; i < array.length; i++) {
        var element_in_array = array[i];
        if (element_in_array == element) {
            array.splice(i, 1);
            return true;
        }
    }
    return false;
}

// format time
function format_time(dateObj, delta_mins) {
    var year = dateObj.getFullYear();
    var mon = dateObj.getMonth();
    var day = dateObj.getDate();
    var hour = dateObj.getHours();
    var min = dateObj.getMinutes();

    var newDateObj = new Date(year, mon, day, hour, min + delta_mins);
    return get_time_str(newDateObj);
}

function get_time_str(dateObj) {
    var year = dateObj.getFullYear();
    var mon = dateObj.getMonth() + 1;
    if (mon < 10) mon = "0" + mon;

    var day = dateObj.getDate();
    if (day < 10) day = "0" + day;

    var hour = dateObj.getHours();
    if (hour < 10) hour = "0" + hour;

    var min = dateObj.getMinutes();
    if (min < 10) min = "0" + min;

    return year + "-" + mon + "-" + day + " " + hour + ":" + min;
}

function get_timestamp(str) {
    // input format: 2012-12-20 11:20
    var year = str.substr(0, 4);
    var mon = str.substr(5, 2);
    var day = str.substr(8, 2);
    var hour = str.substr(11, 2);
    var min = str.substr(14, 2);

    var date = new Date();
    date.setFullYear(parseInt(year, 10));
    date.setMonth(parseInt(mon, 10) - 1);
    date.setDate(parseInt(day, 10));
    date.setHours(parseInt(hour, 10));
    date.setMinutes(parseInt(min, 10));
    date.setSeconds(0);

    return parseInt(date.getTime() / 1000);
}
