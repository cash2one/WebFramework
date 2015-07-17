#!/usr/bin/python
#encoding=utf-8
import re
import os
import sys
import log
import subprocess

class PARSE:
	variable_list = {}
	check_list = {}
	step_list = {}
	collect_list = {}
	module_list = []
	step_list_by_order = []

	def	__init__(self, conf_file):
		self.conf_file = conf_file
		self.get_variable_list()
		self.get_check_step_collect_list()

	def	__del__(self):
		pass

	def exit(self):
		print "部署失败!详情请查看log"
		sys.exit()

	'''
	得到变量值
	'''
	def	get_variable_list(self):
		for	line in open(self.conf_file).read().splitlines():
			if not line:	continue
			if line[0] == "#":	continue
			if "=>" not in line:	continue
				
			key, value = re.split("\s*=>\s*", line.strip(), 1)
			#deal with variable definitions
			if key[0] == "$" and key[-1] == "$":
				for	s_key, s_value in self.variable_list.items():
					if key == s_key:
						log.error("PARSE", "已经定义过"+key)					
						self.exit()	

				#deal with var reference(refer is part of value)
				if "$" in value:
					for s_key, s_val in self.variable_list.items():
						value = value.replace(s_key, s_val)
					if "$" in value:
						log.error("PARSE", key + "不能被定义")
						self.exit()

				#deal with value which is shell cmd
				if value[0] == "[" and value[-1] == "]":
					mode, cmd = re.split("\s*:\s*", value[1:-1], 1)
					try:
						if mode == "shell":
							(value,err) = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, stdin = subprocess.PIPE).communicate()
							value = value.strip()
							if err != "" and value == "":
								log.error("PARSE", "Invalid command(%s)" % cmd + "  %s" + err)
								self.exit()
							log.info("PARSE", "the result of the shell cmd: " + cmd + " is " + value)
						elif mode == "python":
							value = eval(cmd);	
					except StandardError, e:
						log.error("PARSE", "%s" % e)
						self.exit()

			#put the key in the variable list
			self.variable_list[key] = value

		return	self.variable_list

	'''
	解析出配置文件中每个{}间的内容，每个{}内容作为一个module放入list中
	'''
	def	get_module_list(self):
		f = open(self.conf_file,"r+")
		line = f.readline()
		while line:
			line = line.strip()
			if not line:	
				line = f.readline()
				continue
			if line[0] == "#":
				line = f.readline()
				continue
			elif line == "{":
				step = []
				line = f.readline().strip()
				while line != "}":
					if line and line[0] != "#":
						step.append(line)
						if(line.find("step.") == 0 or line.find("collect.") == 0):
							key, value = re.split("\s*:\s*", line, 1)
						if key == "step.name":
							self.step_list_by_order.append(value)
					line = f.readline().strip()
				self.module_list.append(step)
				line = f.readline()
			else:
				line = f.readline()
				continue

	'''
	将一个模块放入list(check_list or step_list or collect_list)中
	'''
	def get_list_by_name(self, module, name):
		name_type, suffix = re.split("\.", name, 1)
		tmp_list = {}
		for index in range(len(module)):
			if(module[index].find("step.") == 0 or module[index].find("collect.") == 0):
				key, value = re.split("\s*:\s*", module[index], 1)			
			else:
				value = value + "\n" + module[index]
			tmp_list[key] = value
		list = eval("self.%s_list" % name_type)
		for s_key, s_value in list.items():
			if tmp_list[name] == s_key:
				log.error("PARSE", "已经定义"+s_key)					
				self.exit()
		cmd = "self.%s_list[tmp_list[name]] = tmp_list" % name_type
		exec(cmd)

	'''
	将所有模块区分放入check_list or step_list or collect_list
	'''
	def get_check_step_collect_list(self):
		self.get_module_list()
		name = ""
		for index in range(len(self.module_list)):
			if "check." in self.module_list[index][0]:
				name = "check.name"
			elif "step." in self.module_list[index][0]:
				name = "step.name"
			elif "collect." in self.module_list[index][0]:
				name = "collect.name"
			else:
				log.error("PARSE", "配置文件错误")
				self.exit()
			self.get_list_by_name(self.module_list[index], name)

