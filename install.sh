#!/bin/bash

# ===================== Variables ===================================
# src file for installations
appache_src_file=/disk2/zhangpei/lamp/httpd/httpd-2.2.21.tar.gz
php_src_file=/disk2/zhangpei/lamp/php/php-5.3.8.tar.gz
mysql_src_file=/disk2/zhangpei/lamp/mysql/mysql-5.5.18-linux2.6-i686.tar.gz

# temp dir where config, make, make install locates
temp_dir=/disk2/qatest/temp
mkdir $temp_dir

# root dir where appache and php locates
root_dir=/disk2/qatest/lamp
mkdir $root_dir

# appache installation dir
appache_inst_dir=$root_dir/appache
# port for appache
port=28081

# php installation dir
php_inst_dir=$root_dir/php

# mysql installation dir
mysql_inst_dir=$root_dir/mysql

# ===================== Functions ===================================
function install_httpd() {
	echo "Install httpd"
	./bin/httpd -k stop

	rm -rf $temp_dir/httpd-2.2.21
	rm -rf $appache_inst_dir

	cd $temp_dir
	tar -zxvf $appache_src_file
	cd httpd-2.2.21
	./configure --prefix=$appache_inst_dir --enable-so
	make && make install

	cd $appache_inst_dir
	sed -i "s/Listen 80/Listen $port/" conf/httpd.conf
}

function install_php() {
	echo "Install php"
	rm -rf $temp_dir/php-5.3.8
	rm -rf $php_inst_dir

	cd $temp_dir
	tar -zxvf $php_src_file
	cd php-5.3.8
	./configure --enable-sockets --with-openssl --with-ldap --with-mysql --enable-mbstring --prefix=$php_inst_dir --with-apxs2=$appache_inst_dir/bin/apxs --with-gd --with-zlib --with-freetype-dir
	make && make install
	cp php.ini-development $php_inst_dir/lib/php.ini

	sed -i -e 's#   \#AddType application/x-gzip .tgz#&\n    AddType application/x-httpd-php .php\n    AddType application/x-httpd-php-source .phps#' $appache_inst_dir/conf/httpd.conf

	cd $appache_inst_dir
	./bin/httpd -k restart
}

function install_mysql()
{
	### Deprecated
	echo "Install mysql"
	rm -rf $temp_dir/mysql-5.5.18-linux2.6-i686
	rm -rf $mysql_inst_dir
    
    cd $temp_dir
    tar -zxvf $mysql_src_file
    cd mysql-5.5.18-linux2.6-i686
    
    # to be finished
	rm -rf mysql-5.5.18-linux2.6-i686
	tar -zxvf mysql-5.5.18-linux2.6-i686.tar.gz
	rm -rf /home/zhangpei/installations/mysql-5.5.18-linux2.6-i686
	cp -r mysql-5.5.18-linux2.6-i686 /home/zhangpei/installations/
	cd /home/zhangpei/installations/mysql-5.5.18-linux2.6-i686/
	./scripts/mysql_install_db --user=zhangpei --basedir=/home/zhangpei/installations/mysql-5.5.18-linux2.6-i686 --datadir=/home/zhangpei/installations/mysql-5.5.18-linux2.6-i686/data
}

# ===================== Main Logic ===================================
#install_httpd
install_php
