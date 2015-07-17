#!/bin/bash

# deploy step: http://jforum.net/install_no_wizard.jsp
# http://jforum.net/install.jsp
# http://canbeatle.iteye.com/blog/128626
# features: http://jforum.net/features.jsp

cd resin-3.0.21
./bin/httpd.sh stop
cd ..

rm -rf resin-3.0.21
scp nb093:/disk2/zhangpei/dev/qa/testAutomation/auto-deploy2/packages/resin-3.0.21.tar.gz .
tar -zxvf resin-3.0.21.tar.gz
cp resin.conf.template resin-3.0.21/conf/resin.conf

cd resin-3.0.21/webapps
rm -rf *
cp ../../jforum-2.1.9.zip .
unzip jforum-2.1.9.zip
mv jforum-2.1.9 jforum

cd ..
./bin/httpd.sh start
