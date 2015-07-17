package demos;

import org.testng.annotations.*;

public class StaticProvider {

    @DataProvider(name = "create")  
    public static Object[][] createData() {  
        return new Object[][] {  
            new Object[] {"Michael", new Integer(0)}
        };
    }  
}
