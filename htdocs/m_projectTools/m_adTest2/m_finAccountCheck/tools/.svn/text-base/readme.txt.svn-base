解压附件，并进入jar包目录，其中有个数据库地址配置文件hibernate.properties，可以通过修改write_finance对应参数，设定报表保存的测试库
 
然后在当前目录下执行下列命令即可
java -XX:-UseGCOverheadLimit -Xms2680m -Xmx2680m -cp
.:./indicator.jar:./ant.jar:./commons-lang.jar:./mysql-connector.jar:./org.springframework.aop-3.0.0.RELEASE.jar:./servlet-api.jar:./aopalliance.jar:./org.springframework.web-3.0.0.RELEASE.jar:./log4j.jar:./cglib-nodep.jar:./commons-collections.jar:./ead-finance.jar:../bin/:./dom4j.jar:./org.springframework.jdbc-3.0.0.RELEASE.jar:./jta.jar:./hibernate3.jar:./org.springframework.transaction-3.0.0.RELEASE.jar:./org.springframework.orm-3.0.0.RELEASE.jar:./commons-pool.jar:./commons-dbcp.jar:./org.springframework.expression-3.0.0.RELEASE.jar:./org.springframework.asm-3.0.0.RELEASE.jar:./org.springframework.context-3.0.0.RELEASE.jar:./org.springframework.beans-3.0.0.RELEASE.jar:./org.springframework.core-3.0.0.RELEASE.jar:./commons-logging.jar:./junit.jar:./org.springframework.test-3.0.0.RELEASE.jar:./org.springframework.orm-3.0.0.RELEASE.jar:./antlr.jar
org.junit.runner.JUnitCore outfox.ead.indicator.checker.IFinanceCheckerTest
 
注：这个方法执行很耗cpu和内存
 
执行这个命令时，会先清空对应agent和sponsor revenue
表数据，然后插入近半年相关统计数据
 
需要手动创建data目录
在nc111上运行大概需要1.5个小时

code addr:
https://dev.corp.youdao.com/svn/outfox/products/ad/indicator/branches/20130131
