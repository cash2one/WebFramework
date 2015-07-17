#!/bin/bash

# below command for data deletion NOT needed for tool will delete data in table before do work
# ./truncate_tables.py

echo "生成对账用的输入数据，放到sponsor_revenue_monthly & agent_revenue_monthly"
echo "数据库配置文件在jar/hibernate.properties, 请勿配置到线上库!!!"

cp ver3/finance-checker.jar jar/
cd jar

java -XX:-UseGCOverheadLimit -Xms2680m -Xmx2680m -cp .:./finance-checker.jar  org.junit.runner.JUnitCore outfox.ead.indicator.checker.IFinanceCheckerTest
