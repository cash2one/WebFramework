<?php

$treeIndex = Array(
    "title" => "小工具系列",

    "viewbean.sh" => Array(
        "name"   => "bean源码查看",
        "title"  => "在项目工程目录下直接通过bean名称查看源码",
        "params" => Array(
            "bean的全名称" => "如outfox.ead.antifrauder.redis.RedisDaoImpl"
        ),
        "usages" => Array(
            "运行方式: ./viewbean.sh bean全名称",
            "可在项目任何路径下执行，样例:./viewbean.sh outfox.ead.antifrauder.redis.RedisDaoImpl",
            "最终会转化成vim /\$proj_path/src/java/outfox/ead/antifrauder/redis.RedisDaoImpl.java"
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
