#!/usr/bin/python2.4 -u
#encoding=utf-8

#import getopt
import os
import re
import sys
import commands
import log

class UpdateEnum:
	Equal  = 0
	Match  = 1
	Substr = 2
	InsertBefore = 3
	InsertAfter	 = 4
	Comment = 5
	SubstrAfterLine = 6
	CommentAfterLine = 7

def exit(): 
	print "FAILED! Please go to log for detail"
	sys.exit()      

'''
   检查匹配string的line在文件中是否唯一
'''	
def if_the_line_is_unique(file, string, match_rule):
	f=open(file,"r+")
	num=0
	if match_rule in (UpdateEnum.Equal, UpdateEnum.InsertBefore, UpdateEnum.InsertAfter, UpdateEnum.Comment):
		for line in f.readlines():
			if line.strip() == string:
				num += 1
	elif match_rule == UpdateEnum.Substr:
		for line in f.readlines():
			if line.find(string) >= 0:	
				num += 1
	elif match_rule == UpdateEnum.Match:
		for line in f.readlines():
			if re.match(string, line.strip()) or string == line.strip():
				num += 1
	else:
		log.error("FILE","Unrecognized match_rule : %s" % match_rule)
		exit()
	if num==1:
		return True
	elif num==0:
		log.error("FILE","no line (%s) found in file: %s " % (string, file))
		return False
	else:
		log.error("FILE","more than one line "+string+" found in file: "+file)
		return False

'''
   在文件中找到匹配string的行，返回其所在行号
'''
def get_line_no(file, string, match_rule):
	if if_the_line_is_unique(file, string, match_rule)==False:
		exit()	
	f=open(file,"r+")
	lineno = 1
	if match_rule in (UpdateEnum.Equal, UpdateEnum.InsertBefore, UpdateEnum.InsertAfter, UpdateEnum.Comment):
		for line in f.readlines():
			if line.strip() == string:
				log.debug("FILE","find "+string+" in line: "+line)
				return lineno
			else:
				lineno += 1
	elif match_rule == UpdateEnum.Substr:
		for line in f.readlines():
			if line.find(string) >= 0:
				log.debug("FILE","find "+string+" in line: "+line)
				return lineno
			else:
				lineno += 1
	elif match_rule == UpdateEnum.Match:
		for line in f.readlines():
			if re.match(string, line) or string == line.strip():
				log.debug("FILE","find "+string+" in line: "+line)
				return lineno
			else:
				lineno += 1
	f.close()
	log.error("FILE","no match line found")
	return False	

'''
    在文件中找到匹配string的所有行
'''
def get_line_nos(file, string, match_rule):
    f = open(file, "r+")
    lineno_list = []
    lineno = 1
    if match_rule in (UpdateEnum.Equal, UpdateEnum.InsertBefore, UpdateEnum.InsertAfter, UpdateEnum.Comment):
        for line in f.readlines():
            if line.strip() == string:
                log.debug("FILE","find "+string+" in line: "+line)
                lineno_list.append(lineno)
            lineno += 1
    elif match_rule == UpdateEnum.Substr:
        for line in f.readlines():
            if line.find(string) >= 0:
                log.debug("FILE","find "+string+" in line: "+line)
                lineno_list.append(lineno)
            lineno += 1
    elif match_rule == UpdateEnum.Match:
        for line in f.readlines():
            if re.match(string, line) or string == line.strip():
                log.debug("FILE","find "+string+" in line: "+line)
                lineno_list.append(lineno)
            lineno += 1
    f.close()
    return lineno_list



'''
	将文件中的指定行替换为指定字符串newline	
'''
def update_line_for_lineno(file, line_num, new_line):
	lines = open(file,"r").readlines()
	if line_num > len(lines) or line_num < 1:
		log.error("FILE","the line_num "+line_num+" is not exit in this file"+ file)
		exit()	
	log.info("FILE","Update ("+lines[line_num - 1].replace("\n", "")+") to ("+new_line+")")
	lines[line_num - 1] = new_line + "\n"
	open(file, "w").writelines(lines)

'''
	对文件中指定行进行替换
	match_string 需要匹配的行
	new_line 替换成的字符串
	update_rule 替换规则 值为UpdateEnum类型
'''
def update_file(file, match_string, new_line, update_rule):
	if not os.path.isfile(file):
		log.error("FILE", file + " is not exists")
		exit()
	lineno = get_line_no(file, match_string, update_rule)
	lines = open(file,"r").readlines()
	tmpline = lines[lineno-1]
	if update_rule in (UpdateEnum.Equal, UpdateEnum.Match):
		tmpline = new_line
	elif update_rule == UpdateEnum.Substr:
		tmpline = lines[lineno-1].replace(match_string, new_line)
	elif update_rule == UpdateEnum.InsertBefore:
		tmpline = new_line+"\n"+lines[lineno-1].replace("\n", "")
	elif update_rule == UpdateEnum.InsertAfter:
		tmpline = lines[lineno-1]+new_line
	elif update_rule == UpdateEnum.Comment:
		if file.endswith(".java"):
			tmpline = "//" + lines[lineno-1].replace("\n", "")
		elif ".xml" in os.path.split(file)[1]:
			tmpline = "<!--	" + lines[lineno-1].replace("\n", "") + "	-->"
		elif os.path.split(file)[1].startswith("servers.properties") or file.endswith(".py"):
			tmpline = "#" + lines[lineno-1].replace("\n", "")
		else:
			log.error("FILE", "Unkown file format for comment")
	update_line_for_lineno(file, lineno, tmpline)

	return True

'''
	replace the line equal old_line with new_line
'''
def update_line_for_equal(file, old_line, new_line):
	update_file(file, old_line, new_line, UpdateEnum.Equal)

'''
	insert new_line after line which machth the string match_string
'''
def insert_after_line(file, match_string, new_line):	
	update_file(file, match_string, new_line, UpdateEnum.InsertAfter)

'''
	insert new_line before line which machth the string match_string
'''
def insert_before_line(file, match_string, new_line):		
	update_file(file, match_string, new_line, UpdateEnum.InsertBefore)	
	
'''
	comment out the line match_string
'''
def add_comment_for_line(file, line):			
	update_file(file, line, ""	, UpdateEnum.Comment)

'''
	replace match_string with new_line
'''
def update_line_for_contain_substr(file, match_string, new_line):
	update_file(file, match_string, new_line, UpdateEnum.Substr)

'''
	replace the line which like match_string with new_line
'''
def update_line_for_match(file, match_string, new_line):
	update_file(file, match_string, new_line, UpdateEnum.Match)

'''
    指定行偏移量替换
'''
def update_line_for_match_offset(file, match_string, new_line, offset):
    lineno = get_line_no(file, match_string, UpdateEnum.Equal)
    lineno = lineno+int(offset)
    update_line_for_lineno(file, lineno, new_line)

'''
    指定所有匹配行中替换第n个匹配行
'''
def update_line_for_matchs_num(file, match_string, new_line, num):
    linenos = get_line_nos(file, match_string, UpdateEnum.Equal)
    if num=="all" or num=="ALL":
        for lineno in linenos:
            update_line_for_lineno(file, lineno, new_line)
    else:
        num = int(num)
        update_line_for_lineno(file, linenos[num-1], new_line)
    
if ( __name__ == "__main__"):
    #update_line_for_match_offset("1", "ok", "mimiha", "-1")
    #get_line_nos("1","ok",UpdateEnum.Equal)
    update_line_for_match_num("1", "ok", "testok", "2")

