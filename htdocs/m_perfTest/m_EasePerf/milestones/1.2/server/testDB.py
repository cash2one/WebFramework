#!/usr/bin/python
# coding=utf-8
# filename: testDB.py
##################################
# @author yinxj
# @date 2012-05-30
##################################
# 性能测试工具收集
# mysql操作测试模块
##################################
import db
dbop=db.OP('yinxj','armani','search','nb293');
values=[];
values.append(('2012-05-31 14:35:25', 'search.process.time.avg', 89));
values.append(('2012-05-31 14:35:25', 'search.process.time.90', 134));
values.append(('2012-05-31 14:35:25', 'search.process.time.99', 175));
values.append(('2012-05-31 14:35:25', 'search.process.time.qps', 50));
values.append(('2012-05-31 14:35:25', 'search.process.state.qps', 50));
values.append(('2012-05-31 14:35:25', 'search.process.state.success.qps', 43));
values.append(('2012-05-31 14:35:25', 'search.process.state.fail.qps', 5));
values.append(('2012-05-31 14:35:25', 'search.process.state.timeout.qps', 2));
dbop.insert(values);

