<?php
$php_array = Array (
    "shell" => Array (
        "desc" => "shell命令",
        "parameters" => Array (
           "cmd" => "shell命令"
        ),
        "retrun" => "",
        "demo" => Array (
            "demo1" => Array (
                "cmd"  => "rm -rf \$impr_resin_local_dir\$",
                "说明" => "删除文件夹\$impr_resin_local_dir\$.(\$impr_resin_local_dir\$为已定义变量)"
            ),           
            "demo2" => Array (
                "cmd"  => "cd \$impr_resin_local_dir\$ ;  ./bin/httpd.sh stop",
                "说明" => "进入\$impr_resin_local_dir\$文件夹，执行stop. 使用;分隔多个命令，前面的命令如果执行失败，依然会执行后面的命令"
            ),
            "demo3" => Array (
                "cmd"  => "cd \$impr_resin_local_dir\$ &&  ./bin/httpd.sh stop",
                "说明" => "进入\$impr_resin_local_dir\$文件夹，执行stop. 使用&&分隔多个命令，前面的命令如果执行失败，则不会执行后面的命令"
            ),
            "demo4" => Array (
                "cmd"  => "cd \$impr_resin_local_dir\$ ||  ./bin/httpd.sh stop",
                "说明" => "进入\$impr_resin_local_dir\$文件夹，执行stop. 使用||分隔多个命令，前面的命令如果执行成功，则不会执行后面的命令"
            ),
            "demo5" => Array (
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
            "match_line" => "文件中某行(唯一)，此行匹配正则表达式(match_line)",
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
            "match_line" => "文件中某行(唯一)，此行匹配正则表达式(match_line)",
            "new_line" => "插入行的内容"
        ),
        "return" => "",
        "demo" => Array (

        ) 
    ),

    "file.add_comment_for_line" => Array (
        "desc" => "注释掉文件中某行",
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

    "file.update_line_for_lineno" => Array (
        "desc" => "根据行号替换文件中指定行",
        "parameters" => Array (
            "file" => "操作的文件",
            "lineno" => "文件中指定行，行号为lineno",
            "new_line" => "插入行的内容"
        ),
        "return" => "",
        "demo" => Array (
            "demo1" => Array(
                "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                "lineno" => "15",
                "new_line" => "new_line for test",
                "说明" => "将文件中的第15行修改为\"new_line for test\""
            )
        )
    ),

    "file.update_line_for_match_offset" => Array (
        "desc" => "根据匹配行进行偏移量替换",
        "parameters" => Array (
            "file" => "操作的文件",
            "match_line" => "文件中某行(唯一)",
            "new_line" => "插入行的内容",
            "offset" => "距离match_line的偏移量，match_line行之后为正值，match_line行之后为负值"
        ),
        "return" => "",
        "demo" => Array (
            "demo1" => Array(
                "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                "match_line" => "<http server-id=>\"\" host=>\"*\" port=>\"@hport@\"/>, <http server-id=>\"\" host=>\"*\" port=>\"28082\"/>",
                "new_line" => "new_line for test",
                "offset" => "5",
                "说明" => "将文件中某行match_line(唯一)后第5行替换为new_line"
            ),
            "demo2" => Array(
                 "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                 "match_line" => "<http server-id=>\"\" host=>\"*\" port=>\"@hport@\"/>, <http server-id=>\"\" host=>\"*\" port=>\"28082\"/>",
                 "new_line" => "new_line for test",
                 "offset" => "-5",
                 "说明" => "将文件中某行match_line(唯一)前第5行替换为new_line"
             )
        )
    ),

    "file.update_line_for_matchs_num" => Array(
        "desc" => "指定所有匹配行中替换第n个匹配行",
        "parameters" => Array (
            "file" => "操作的文件",
            "match_line" => "文件中某行(文件中可存在多行)",
            "new_line" => "替换行的内容",
            "num" => "所有匹配行中第num行,num设置为all或ALL表示替换所有匹配行"
        ),
        "return" => "",
        "demo" => Array (
            "demo1" => Array(
                "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                "match_line" => "<http server-id=>\"\" host=>\"*\" port=>\"@hport@\"/>, <http server-id=>\"\" host=>\"*\" port=>\"28082\"/>",
                "new_line" => "new_line for test",
                "num" => "2",
                "说明" => "将文件中所有内容为match_line的行中的第num行替换为new_line"
            )                         
        )
    ),

    "file.update_line_for_match_allLine" => Array(
        "desc" => "替换所有匹配行",
        "parameters" => Array (
            "file" => "操作的文件",
            "match_line" => "文件中某行(文件中可存在多行)",
            "new_line" => "插入行的内容"
        ),    
        "return" => "",
        "demo" => Array (
            "demo1" => Array(
                "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                "match_line" => "<http server-id=>\"\" host=>\"*\" port=>\"@hport@\"/>, <http server-id=>\"\" host=>\"*\" port=>\"28082\"/>",
                "new_line" => "new_line for test",
                "说明" => "将文件中所有内容为match_line的行替换为new_line"
            )
        )
    ),

    "file.update_line_for_match_allString" => Array(
        "desc" => "替换文件中所有匹配字符串",
        "parameters" => Array (
            "file" => "操作的文件",
            "old_string" => "文件中字符串old_string",
            "new_string" => "要替换成的字符串new_string"
        ),
        "return" => "",
        "demo" => Array (
            "demo1" => Array(
                "file" => "\$impr_resin_local_dir\$/conf/resin.conf",
                "old_string" => "old string",
                "new_string" => "new string",
                "说明" => "将文件中所有字符串old_string替换为new_string"
            )
        )
    )
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

