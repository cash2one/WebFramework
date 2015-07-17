#!/usr/bin/python
# coding=utf-8
# filename: testProcessor.py
##################################
# @author yinxj
# @date 2012-06-18
##################################
# 性能测试工具收集
# log数据处理 测试
##################################
import processor

def testBasicFunction():
  print 'basic function test:\n';
  print 'exec: p=processor.Pro(10);\n'
  p=processor.Pro(['cluster.time','cluster,state'],10);
  print 'exec: p.numberLogs:\n';
  print 'p.numberLogs=%s\n' % p.numberLogs;
  print 'exec: p.strLogs:\n';
  print 'p.strLogs=%s\n' % p.strLogs;

  print '''exec: p.pushData(['120607 143711 @@ANALYSIS@@ cluster.time=1','120607 143711 @@ANALYSIS@@ cluster.time=3','120607 143711 @@ANALYSIS@@ cluster.time=15','120607 143711 @@ANALYSIS@@ cluster.time=35']);\n'''
  p.pushData(['120607 143711 @@ANALYSIS@@ cluster.time=1','120607 143711 @@ANALYSIS@@ cluster.time=3','120607 143711 @@ANALYSIS@@ cluster.time=15','120607 143711 @@ANALYSIS@@ cluster.time=35']);
  print 'exec: p.numberLogs:\n';
  print 'p.numberLogs=%s\n' % p.numberLogs;

  print '''exec: p.pushData(['120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143711 @@ANALYSIS@@ cluster.state=fail','120607 143711 @@ANALYSIS@@ cluster.state=success']);\n'''
  p.pushData(['120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143711 @@ANALYSIS@@ cluster.state=fail','120607 143711 @@ANALYSIS@@ cluster.state=success']);
  print 'exec: p.strLogs:\n';
  print 'p.strLogs=%s\n' % p.strLogs;

  print '''exec: p.pushData(['120607 143711 @@ANALYSIS@@ cluster.time=1','120607 143721 @@ANALYSIS@@ cluster.time=3','120607 143722 @@ANALYSIS@@ cluster.time=15','120607 143725 @@ANALYSIS@@ cluster.time=35']);\n''';
  p.pushData(['120607 143711 @@ANALYSIS@@ cluster.time=1','120607 143721 @@ANALYSIS@@ cluster.time=3','120607 143722 @@ANALYSIS@@ cluster.time=15','120607 143725 @@ANALYSIS@@ cluster.time=35']);
  print 'exec: p.numberLogs:\n';
  print 'p.numberLogs=%s\n' % p.numberLogs;

  print '''exec: p.pushData(['120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143721 @@ANALYSIS@@ cluster.state=fail','120607 143721 @@ANALYSIS@@ cluster.state=success']);\n''';
  p.pushData(['120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143711 @@ANALYSIS@@ cluster.state=timeout','120607 143721 @@ANALYSIS@@ cluster.state=fail','120607 143721 @@ANALYSIS@@ cluster.state=success']);
  print 'exec: p.strLogs:\n';
  print 'p.strLogs=%s\n' % p.strLogs;

  print '''exec: p.popData()\n'''
  print 'p.popData()=%s' % p.popData();

def testNearByZeroClock():
  print 'nearby zero clock function test:\n';
  print 'exec: q=processor.Pro(5);\n';
  q=processor.Pro(['cluster.time'],5);
  print '''exec: q.pushData(['120607 235940 @@ANALYSIS@@ cluster.time=1','120607 235940 @@ANALYSIS@@ cluster.time=3','120607 235940 @@ANALYSIS@@ cluster.time=15','120618 235940 @@ANALYSIS@@ cluster.time=35']);\n''';
  q.pushData(['120607 235940 @@ANALYSIS@@ cluster.time=1','120607 235940 @@ANALYSIS@@ cluster.time=3','120607 235940 @@ANALYSIS@@ cluster.time=15','120618 235940 @@ANALYSIS@@ cluster.time=35']);
  print '''exec: q.pushData(['120607 235940 @@ANALYSIS@@ cluster.time=1','120607 000005 @@ANALYSIS@@ cluster.time=3','120607 000002 @@ANALYSIS@@ cluster.time=15','120618 000002 @@ANALYSIS@@ cluster.time=35']);\n''';
  q.pushData(['120607 235940 @@ANALYSIS@@ cluster.time=1','120607 000005 @@ANALYSIS@@ cluster.time=3','120607 000002 @@ANALYSIS@@ cluster.time=15','120618 000002 @@ANALYSIS@@ cluster.time=35']);
  print '''exec: q.pushData(['120607 000008 @@ANALYSIS@@ cluster.time=1','120607 000016 @@ANALYSIS@@ cluster.time=3','120607 000020 @@ANALYSIS@@ cluster.time=15','120618 000022 @@ANALYSIS@@ cluster.time=35']);\n''';
  q.pushData(['120607 000008 @@ANALYSIS@@ cluster.time=1','120607 000016 @@ANALYSIS@@ cluster.time=3','120607 000020 @@ANALYSIS@@ cluster.time=15','120618 000022 @@ANALYSIS@@ cluster.time=35']);

  print 'exec: q.numberLogs:\n';
  print 'q.numberLogs=%s\n' % q.numberLogs;
  print 'exec: q.popData():\n';
  print 'q.popData()=%s\n' % q.popData();

def testWhiteList():
  print 'white list test'
  print 'exec: w=processor.Pro(10)'
  w=processor.Pro(['segment_time','normal_summary_time'],10)
  print '''exec: w.pushData(['0612000006|4310d940|INFO|@@ANALYSIS@@[req=2948878] segment_time=3 normal_ls_time=4 normal_merge_time=4 normal_summary_time=0 dupsum_remove_time=0 reply_time=0 rd_idx_cache=0 rd_sum_cache=0 normal_total_time=22 '])'''
  w.pushData(['0612000006|4310d940|INFO|@@ANALYSIS@@[req=2948878] segment_time=3 normal_ls_time=4 normal_merge_time=4 normal_summary_time=0 dupsum_remove_time=0 reply_time=0 rd_idx_cache=0 rd_sum_cache=0 normal_total_time=22 '])

  print 'exec: w.numberLogs:\n';
  print 'w.numberLogs=%s\n' % w.numberLogs;
  print 'exec: w.popData():\n';
  print 'w.popData()=%s\n' % w.popData();

print 'import processor;\n';
testBasicFunction();
print '\n\n\n';
testNearByZeroClock();
print '\n\n\n'
testWhiteList()
