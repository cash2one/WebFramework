hi zhangpei：
 
更新了对账程序代码，主要解决与财务系统统计结算服务之间的时序性问题，在本程序运行完后，会分别向Agent_Revenue_monthly
和 Sponsor_Revenue_Monthly增加一条特殊数据，以表明所有记录已生成完
 
其中：Agent_Revenue_monthly增加的记录特点：id为表中最大，revenue_month值为当前年月（如本月为201306），agent_id=0
  Sponsor_Revenue_monthly增加的记录特点：id为表中最大，revenue_month值为当前年月（如本月为201306），agent_id=0，sponsor_id=0
 
该程序运行方式：
 
解压附件，并进入jar包目录，其中有个数据库地址配置文件hibernate.properties，可以通过修改write_finance对应参数，设定报表保存的测试库
然后在当前目录下执行下列命令
java -XX:-UseGCOverheadLimit -Xms2680m -Xmx2680m -cp .:./finance-checker.jar
org.junit.runner.JUnitCore outfox.ead.indicator.checker.IFinanceCheckerTest
 
@卢坤
在财务系统中在统计结算时，需要先判断这两个表中是否已经存在标志当月数据生成完毕的特殊记录，如果没找到，则不进行统计；否则正常统计

