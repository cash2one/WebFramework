package demos;

import org.testng.annotations.*;

public class Demo10 {
    @Test
    public void serverStartedOk() {
        System.out.println("serverStartedOk");
    }

    @Test(dependsOnMethods = { "serverStartedOk" })
    public void method1() {
        System.out.println("method1");
    }

    @Test(groups = { "init" })
    public void serverStartedOk2() {

    }

    @Test(groups = { "init" })
    public void initEnvironment() {
        assert 1 == 2;
    }

    @Test(dependsOnGroups = { "init.*" })
    public void method2() {

    }

    @Test(dependsOnGroups = { "init.*" }, alwaysRun = true)
    public void method3() {
    }
}
