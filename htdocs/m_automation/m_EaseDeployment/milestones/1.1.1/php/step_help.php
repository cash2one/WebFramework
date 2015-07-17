<?php
$php_array = Array (
    "shell" => Array (
        "desc" => "shell命令",
        "parameters" => Array (
           "cmd" => "shell命令",
        ),
        "retrun" => "",
        "demo" => Array (
            "demo1" => Array (
                "cmd"  => "rm -rf \$impr_resin_local_dir\$",
                "说明" => "删除文件夹\$impr_resin_local_dir\$.(\$impr_resin_local_dir\$为已定义变量)"
            ),           
            "demo2" => Array (
                "cmd"  => "cd \$impr_resin_local_dir\$; ./bin/httpd.sh stop",
                "说明" => "进入\$impr_resin_local_dir\$文件夹，执行stop.(\$impr_resin_local_dir\$为已定义变量)"
            ),
            "demo3" => Array (
                "cmd"  => "cp -r \$resin_code\$ /tmp; cd /tmp; tar -zxvf resin-3.0.21.tar.gz; mv resin-3.0.21 \$impr_resin_local_dr\$; rm -rf /tmp/resin-3.0.21.tar.gz",
                "说明" => "执行多步操作，完成拷贝resin到指定目录.(\$resin_code\$,\$impr_resin_local_dir\$为已定义变量)"
            ) 
        )
    ),

    "file.update_line_for_equal" => Array (
        "desc" => "替换文件中某行(old_line)为新内容(new_line)",
        "parameters" => Array (
            "file" => "操作的文件",
            "old_line" => "文件中要替换的行(唯一)",
            "new_line" => "替换后该行内容"               
        ),
        "retrun" => "none",
        "demo" => Array (
            "demo1" => Array (
                "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                "old_line" => "<http server-id=>\"\" host=>\"*\" port=>\"@hport@\"/>, <http server-id=>\"\" host=>\"*\" port=>\"28082\"/>",
                "new_line" => "<http server-id=>\"\" host=>\"*\" port=>\"28082\"/>",
                "说明" => "将文件\$impr_resin_local_dir\$/conf/resin.conf中的此行： <http server-id=>\"\" host=>\"*\" port=>\"@hport@\"/>替换为：<http server-id=>\"\" host=>\"*\" port=>\"28082\"/>.(\$impr_resin_local_dir\$为已定义变量)"
            )
        )
    ),

    "file.insert_after_line" => Array (
        "desc" => "在文件中某行(匹配正则表达式match_line)后抽入一行新内容(new_line)",
        "parameters" => Array (
            "file" => "操作的文件",
            "match_line" => "文件中某行(唯一)，此行匹配正则表达式(match_string)",
            "new_line" => "插入行的内容"
         ),
        "retrun" => "",
        "demo" => Array (
            
        )
    ),

    "file.insert_before_line" => Array (
       "desc" => "在文件中某行(match_line)前抽入一行新内容(new_line)",
       "parameters" => Array (
            "file" => "操作的文件",
            "match_line" => "文件中某行(唯一)，此行匹配正则表达式(match_string)",
            "new_line" => "插入行的内容"
        ),
        "return" => "",
        "demo" => Array (

        ) 
    ),

    "file.add_comment_for_line" => Array (
        "desc" => "为文件中某行添加注释",
        "parameters" => Array (
            "file" => "操作的文件",
            "line" => "要注释的行"
        ),       
        "retrun" => "",
        "demo" => Array (

        ) 
    ),

    "file.update_line_for_contain_substr" => Array (
        "desc" => "替换文件中某行中字符串match_string为新字符串new_string",
        "parameters" => Array (
            "file" => "操作的文件",
            "match_string" => "文件中某行的子串match_string(唯一)",
            "new_string" => "替换后字符串"
        ),
        "return" => "",
        "demo" => Array (
            
        )        
    ),

    "file.update_line_for_match" => Array (
        "desc" => "替换文件中某行(匹配正则表达式match_string)为新行(new_line)",
        "parameters" => Array (
            "file" => "操作的文件",
            "match_string" => "文件中某行(唯一),此行匹配正则表达式match_string",
            "new_line" => "插入行的内容"        
         ),
        "return" => "",
        "demo" => Array (
            
        )        
    ),

/*
    "checkenv.display_cpu" => Array (
        "desc" => "检查所在机器cpu使用情况",
        "parameters" => Array (
            
        ),
        "retrun" => "",
        "demo" => Array(
            
        )
    ),

    "checkenv.display_load" => Array (
        "desc" => "检查所在机器load情况",    
        "parameters" => Array (
      
        ),
        "retrun" => "",
        "demo" => Array(
      
        )
    ),
    "checkenv.display_mem" => Array (
        "desc" => "检查所在机器内存使用情况",
        "parameters" => Array (
      
        ),
        "retrun" => "",
        "demo" => Array(
     
        )
    ), 

    "checkenv.display_io" => Array (
        "desc" => "检查所在机器io使用情况",       
        "parameters" => Array (
      
        ),
        "retrun" => "",
        "demo" => Array(
      
        )
    ),

    "checkenv.display_disk" => Array (
        "desc" => "检查所在机器path所在disk使用情况",
        "parameters" => Array (
            "path" => "待检查的磁盘"
        ),
        "retrun" => "",
        "demo" => Array(
       
        )
    ),

     "checkenv.check_port_isused" => Array (
        "desc" => "检查所在机器上给定端口是否被占用",
        "parameters" => Array (
            "port" => "待检查的端口号"   
        ),
        "retrun" => "返回1表示已占用，返回0表示未占用",
        "demo" => Array(
#            [checkenv.check_port_isused]
        )
    )
*/
);
echo json_encode($php_array);

