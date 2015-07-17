<html>

<head>
    <meta charset="utf-8"/>
    <script src="../../js-base/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
</head>

<body>
    <h2>产品代码阅读及注释工具(BETA)</h2> <a href="./php/tools/readme.html" target=_blank>Readme</a>

    <div id="container">
        <div id="links">
            <span><a href='#' name='search'>搜索</a></span>
            <span><a href='#' name='add'>添加</a></span>
            <span><a href='#' name='view_comment'>查看及注释</a></span>
            <span><a href='#' name='commentStat'>统计</a></span>
            <span><a href='#' name='configure'>管理配置</a></span>
        </div>

        <hr/>

        <div id="search" class="content">
            <form method="GET" action="./result_pages/search.php" target=_new_search>
                <div>
                    <input type="text" name="searchStr" placeholder="输入查询关键字, 多个以空格分隔" />
                    <input type="submit" value="搜索" />
                </div>
                <div>
                    产品名:<select name="prodName"></select>
                </div>
                <div>
                    版本号:<select name="version"></select>
                </div>
                <div>
                    注释类型: <select name="type">
                                <option value="*">所有类型</option>
                                <option value="file_desc">文件描述</option>
                                <option value="function">函数描述</option>
                                <option value="trap">代码坑</option>
                                <option value="test">测试点</option>
                                <option value="study">学习点</option>
                              </select>
                </div>
            </form>
        </div>

        <div id="add" class="content">
            <form method="POST" action="./result_pages/add.php" target=_new_add>
                <div>
                    <input type="text" name="svnUrl" placeholder="svn_url@version" />
                    <input type="submit" value="添加" />
                </div>
                <div>
                    产品名:<select name="prodName"></select>
                    <a href="#" name="add">+</a>
                </div>
                <div>
                    LDAP:<input type="text" name="ldap" />
                    PASSWD:<input type="password" name="passwd" />
                </div>
            </form>
        </div>

        <div id="view_comment" class="content">
            <form method="POST" action="./result_pages/view_comment.php" target=_new_view_comment>
                <div>
                    产品名:<select name="prodName"></select>
                    <input type="submit" value="查看/注释" />
                </div>
                <div>
                    版本号:<select name="version"></select>
                </div>
                <div>
                    用户名:<select name="userName"></select>
                    <a href="#" name="add_user">+</a>
                </div>
            </form>

            <div id="info">
            </div>
        </div>

        <div id="commentStat" class="content">
        </div>

        <div id="configure" class="content">
            <form method="POST" action="./result_pages/configure.php" target=_new_config_page>
                <div>
                    操作类型:<select name="op_type">
                                <option value='set_prod_table_name'>给产品配置表名</option>
                            </select>
                    <input type="submit" value="设定" />
                </div>

                <div id="set">
                </div>
            </form>
        </div>
        
    </div>

    <script src="./js/index.php.js"></script>
</body>
</html>
