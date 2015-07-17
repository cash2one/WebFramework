解压后，进入目录后，运行程序：
java -XX:-UseGCOverheadLimit -Xms2680m -Xmx2680m -cp .:./finance-checker.jar org.junit.runner.JUnitCore outfox.ead.indicator.checker.IFinanceCheckerTest

----------------------

张培测试时，可能需要做些表结构和表数据修改，大概如下：

1.tb094的eadb3表AGENT_REVENUE_MONTHLY和Sponsor_revenue_monthly都增加了字段audit_time，

因为线上数据库没有修改，所以源数据不是从线上tb011读的，也是tb094的测试库

2.如果要在测试库修改accountHistory中audit_time=0的数据，语句是：
UPDATE SPONSOR_ACCOUNT_HISTORY SET AUDIT_TIME=OPERATE_TIME WHERE AUDIT_TIME=0 AND AUDIT_STATUS IN(2,3,4);

UPDATE AGENT_ACCOUNT_HISTORY SET AUDIT_TIME=OPERATE_TIME WHERE AUDIT_TIME=0 AND AUDIT_STATUS IN(2,3,4)
