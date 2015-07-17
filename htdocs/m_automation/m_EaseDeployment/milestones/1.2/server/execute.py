#!/usr/bin/python
#coding=utf-8
import os
import re
import sys
import log
import file
import parse
import checkenv
import subprocess

class EXECUTE:
	def __init__(self, conf_file):
		self.conf_file = conf_file
		self.parser = parse.PARSE(self.conf_file)

#	def __init__(self, parser):
#		self.parser = parser

	def __del__(self):
		pass

	def exit(self):
		print "失败! 详情请查看log"
		sys.exit()		

	'''
	check变量var在map_name中是否已存在
	'''
	def if_var_in_the_map(self, map_name, var):
		for	s_key, s_val in map_name.items():
			if var  ==  s_key:
				return True
		return False

	'''
	解析模块中命令并执行
	'''
	def exec_basic_module(self, module, type):
		# get cmd
		cmd = module["%s.cmd" % type]
		if "$" in cmd:
			for s_key, s_val in self.parser.variable_list.items():
				cmd = cmd.replace(s_key, s_val)

		# get sep   
		sep = ""
		if module.has_key("%s.cmd.sep" % type):
			sep = module["%s.cmd.sep" % type]
		if sep == "":
			sep = ","

		# get ignore_fail
		ignore_fail = ""
		if module.has_key("%s.ignore_fail" % type):
			ignore_fail = module["%s.ignore_fail" % type]

		# analytic cmd and execute
		function, parameters = re.split("\s*:\s*", cmd, 1)
		parameters = parameters.replace("\"", "\\\"")
		if function[0] == "["  and function[-1] == "]":
			function = function[1:-1]
		else :
			log.error("EXECUTE", function + " is not defined")
		if function == "shell":
			log.info("EXECUTE","开始执行shell命令: "+parameters);
			try:
				#(output,err) = subprocess.Popen(parameters+";echo $?", shell=True, stdout=subprocess.PIPE).communicate()
				(output,err) = subprocess.Popen(parameters+";echo status$?done", shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, stdin = subprocess.PIPE).communicate()
				m = re.match(r'((.*\n?)*?)((.*?echo )?)(status)(.*)(done)(.*\n?)',output)
				status = m.group(6)
				output = (m.group(1)+m.group(8)).replace('\n','\n\t')
				if status == '0':
					log.info("EXECUTE", "命令(%s)的执行结果为 %s 通过" % (parameters,output))
				elif ignore_fail == "1":
					log.info("EXECUTE", "[注意]:即使命令(%s)执行失败，部署仍将继续" % parameters)
					if  err != "":
						log.info("EXECUTE", "命令(%s)的执行报错为%s" % (parameters,err))
				else:
					log.error("EXECUTE", "非法的命令(%s) 报错为 %s" % (parameters, err))
					self.exit()
			except Exception, e:
				if ignore_fail != "1":
					log.error("EXECUTE","执行命令(%s)抛异常: %s" % (parameters, e))
					self.exit()
				else:
					log.info("EXECUTE", "[注意]:即使命令(%s)执行失败，部署仍将继续" % parameters)
		else:
			try:
				class_name, function_name = function.split(".")
				param_list = [x.strip() for x in parameters.split(sep)]
				param_list_str = ",".join(["\"%s\"" % x for x in param_list])
				file_name = parameters.split(sep)[0].strip()
				if class_name == "file":
					func_exec_str = "%s.%s(%s)" % ("file", function_name, param_list_str)
				elif class_name == "checkenv":
					if param_list_str == "\"\"":
						func_exec_str = "%s.%s()" % ("checkenv.checkenv()", function_name)
					else:
						func_exec_str = "%s.%s(%s)" % ("checkenv.checkenv()", function_name, param_list_str)
				else:
					log.error("EXECUTE",class_name+"没有定义")
					self.exit()
				ret = eval(func_exec_str)
				if ret == False:
					if ignore_fail != 1:
						log.error("EXECUTE", "命令(%s)执行失败，停止部署" % func_exec_str)
						self.exit()
					else:
						log.info("EXECUTE", "[注意]: 即使命令(%s)执行失败，部署仍将继续" % func_exec_str)
				else:
					log.info("EXECUTE", "执行命令(%s) 通过" % cmd);
			except Exception, e:
				log.error("EXECUTE", "执行命令(%s)抛异常：%s" % (cmd,e))
				self.exit()

	'''
	exec each check_step in check_list
	'''
	def exec_checksteps(self, check_list):
		for check_step in check_list:
			if self.if_var_in_the_map(self.parser.check_list, check_step):
				self.exec_check(self.parser.check_list[check_step])
			elif check_step  ==  "":
				log.info("EXECUTE","check_list is null")
			else:
				log.error("EXECUTE",check_step+" is not defined")
				self.exit()		

	'''
	执行check模块
	'''
	def exec_check(self, check_module):
		self.exec_basic_module(check_module, "check")

	'''
	执行step模块
	'''
	def exec_step(self, step_module):
		# check_before_run
		if step_module.has_key("step.check_before_run"):
			check_before_run = step_module["step.check_before_run"]	
			check_before_run_list = [x for x in check_before_run.split(",")]
			self.exec_checksteps(check_before_run_list)		
		
		# exec cmd
		self.exec_basic_module(step_module, "step")
	
		#check_after_run
		if step_module.has_key("step.check_after_run"):
			check_after_run = step_module["step.check_after_run"]
			check_after_run_list = [x for x in check_after_run.split(",")]
			self.exec_checksteps(check_after_run_list)

	'''
	执行collect模块
	'''
	def exec_collect(self, collect_module):
		# check_before_run
		if collect_module.has_key("collect.check_before_run"):
			check_before_run = collect_module["collect.check_before_run"]
			check_before_run_list = [x.strip() for x in check_before_run.split(",")]
			self.exec_checksteps(check_before_run_list)

		# execute each step
		cmd = collect_module["collect.cmd"]
		cmd_list = [x.strip() for x in cmd.split(",")]
		exclude_list = ""
		if collect_module.has_key("collect.exclude"):
			exclude = collect_module["collect.exclude"]
			exclude_list = [x.strip() for x in exclude.split(",")]

		for step in cmd_list:
			if self.if_var_in_the_map(self.parser.step_list, step) and step not in exclude_list:
				log.info("EXECUTE", "开始执行： " + step)
				self.exec_step(self.parser.step_list[step])

			# match *.*; 
			elif re.match("(.*)(\.)(\*)", step):
				m = re.match("(.*)(\.)(\*)", step)
				for	s in self.parser.step_list_by_order:
					if s not in exclude_list and re.match(m.group(1)+"\.",s):
						log.info("EXECUTE", "开始执行： " + s)
						self.exec_step(self.parser.step_list[s])
	
			else:
				log.error("EXECUTE", "没有定义该步骤："+step)
				self.exit()

		# check_after_run
		if collect_module.has_key("collect.check_after_run"):
			check_after_run = collect_module["collect.check_after_run"]
			check_after_run_list = [x.strip() for x in check_after_run.split(",")]
			self.exec_checksteps(check_after_run_list)
