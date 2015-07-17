<?php

$treeIndex = Array(
    "title" => "点击服务相关工具",

    "buildClickReq.py" => Array(
        "name"   => "点击url提取工具",
        "title"  => "从点击服务的access日志文件中提取出点击url并输出到文件中",
        "params" => Array(
            "log_files"   => "本地access文件路径列表",
            "max_lines"   => "最大生成的点击url行数，-1表示读取所有文件的所有行",
            "output_file" => "输出结果文件路径",
            "save_type"   => "0表示重写输出文件，1表示追加到输出文件",
        ),
        "usages" => Array(
            "使用时，视情况修改脚本中的input_param_dict",
            "运行方式: ./buildClickReq.py",
            "如果运行失败了，工具会抛出异常",
        ),
        "author" => Array(
            "email" => "zhangpei@rd.netease.com",
            "phone" => "9723",
        ),
        "backlog" => Array(
            "2013-11-12 created by zhangpei",
        ),
    ),

    "clickAccessDownload.sh" => Array(
        "name"   => "点击服务的access日志获取",
        "title"  => "从线上点击服务拷贝access日志到本地目录",
        "params" => Array(
            "access_log_path_list"  => "线上access日志目录路径列表",
            "log_date_list"   => "日期列表，用于设定获取哪些日期的日志",
            "output_dir" => "保存文件的路径",
            "remove_exists"   => "如果本地已经存在给文件，是否删除(true VS false)"
        ),
        "usages" => Array(
            "使用时，视情况修改脚本中的变量",
            "运行方式: ./clickAccessDownload.sh",
            "remove_exists设定为true的话，本地如果存在该access文件，则不拷贝",
            "本地access日志的命名方式：access.log.\$date.\$hostname(如access.log.2013-11-10.nc072)",
        ),
        "author" => Array(
            "email" => "zhangpei@rd.netease.com",
            "phone" => "9723",
        ),
        "backlog" => Array(
            "2013-11-13 created by zhangpei",
        ),
    ),
);
