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

//get object property count
function object_propertycount(obj){
    var count = -1;
    if( "object" == (typeof obj).toLowerCase() ){
        if(undefined === obj.length){
            count = 0;
            for(var key in obj){
                ++count
            }
        } else {
            count = obj.length;
        }
    }
    return count;
}

function object_clone(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}

function escapeHtml(unsafe) {
    if(undefined == unsafe){
        return "";
    }
    return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/ /g,"&nbsp;");
}

function getQueryString(name)
{
    // 如果链接没有参数，或者链接中不存在我们要获取的参数，直接返回空
    if(location.href.indexOf("?")==-1 || location.href.indexOf(name+'=')==-1)
    {
        return '';
    }
 
    // 获取链接中参数部分
    var queryString = location.href.substring(location.href.indexOf("?")+1);
 
    // 分离参数对 ?key=value&key2=value2
    var parameters = queryString.split("&");
 
    var pos, paraName, paraValue;
    for(var i=0; i<parameters.length; i++)
    {
        // 获取等号位置
        pos = parameters[i].indexOf('=');
        if(pos == -1) { continue; }
 
        // 获取name 和 value
        paraName = parameters[i].substring(0, pos);
        paraValue = parameters[i].substring(pos + 1);
 
        // 如果查询的name等于当前name，就返回当前值，同时，将链接中的+号还原成空格
        if(paraName == name)
        {
            return unescape(paraValue.replace(/\+/g, " "));
        }
    }
    return '';
};

function getSortedKeys(datas){
   var outarray = [];
   for( var key in datas){
      outarray.push(key);
   }
   outarray.sort();
   return outarray;
}
