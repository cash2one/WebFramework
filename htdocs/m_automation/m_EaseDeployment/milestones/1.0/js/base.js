// Global info
var _ldap = null;
var _passwd = null;
var _key = null;
var _currentConfFilename = null;
var _selectedEditConfFilename = null;
var _selectedUser = null;

var _confObj = {
    "vars": {},
    "steps": {},
    "collects": []
};
var _stepAPIList = {};
var _selectedStepName = null;
var _deployHost = null;

var _subPageDir = "./html";
var _confDir = "./conf";
var _phpDir = "./php";
var _logDir = "../log";
var _confDirForDeploy = "../conf";
var _tempConfDir = _confDirForDeploy + "/temp";
// php file list
var _login_php_file           = _phpDir + "/ldap.php";
var _create_new_user_php_file = _phpDir + "/create_new_conf.php";
var _delete_conf_php_file     = _phpDir + "/delete_selected_conf.php";
var _rename_conf_php_file     = _phpDir + "/rename_selected_conf.php";
var _copy_conf_php_file       = _phpDir + "/copy_selected_conf.php";
var _read_conf_list_php_file  = _phpDir + "/read_conf_list.php";
var _read_conf_php_file       = _phpDir + "/read_selected_conf.php";
var _read_user_list_php_file  = _phpDir + "/read_user_list.php";
var _create_user_dir_php_file = _phpDir + "/create_user_dir.php";
var _save_conf_php_file       = _phpDir + "/save_selected_conf.php";
var _web_deploy_php_file      = _phpDir + "/deploy_entry.php";
var _stop_deploy_php_file     = _phpDir + "/stop_deploy.php";
var _encrypt_php              = _phpDir + "/encrypt.php";
var _log_php                  = _phpDir + "/log.php";
var _step_help_php            = _phpDir + "/step_help.php";

// html file list
var _home_html_file            = _subPageDir + "/home.html";
var _var_section_html_file     = _subPageDir + "/conf_vars.html";
var _step_section_html_file    = _subPageDir + "/conf_steps.html";
var _collect_section_html_file = _subPageDir + "/conf_collects.html";
var _log_html_file             = _subPageDir + "/log.html";

// about the var table
var _varOldValue = null;
var _varNewValue = null;

// about the step table
var _opStepIndex = null;
var _opStepType = null;
var _opDragStepStartIndex = null;
var _opDragStepEndIndex = null;

// about the collect table
var _opCollectIndex = null;
var _opCollectType=null;
var _opDragCollectStartIndex = null;
var _opDragCollectEndIndex = null;
var _opDragCollectStepStartIndex = null;
var _opDragCollectStepEndIndex = null;

// about time interval
var _mytimer =null;
var _lognumber = 0;
