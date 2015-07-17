package demos;

import org.testng.annotations.*;
import java.lang.reflect.Method;

public class Demo09 {  
    @DataProvider(name = "dp")  
    public Object[][] createData(Method m) {  
        System.out.println(m.getName());  // print test method name  
        return new Object[][] { new Object[] { "Cedric" }};  
    }  

    @Test(dataProvider = "dp")  
    public void test1(String s) {  

    }  

    @Test(dataProvider = "dp")  
    public void test2(String s) {  

    }  
}
