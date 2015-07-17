package demos;

import org.testng.annotations.*;

public class Demo08 {  

    @DataProvider(name = "test1")  
    public Object[][] createData1() {  
        return new Object[][] {  
            { "Cedric", new Integer(36) },  
            { "Anne", new Integer(37)},  
        };  
    }  

    @Test(dataProvider = "test1")  
    public void verifyData1(String n1, Integer n2) {  
        System.out.println(n1 + " " + n2);  
    }  

    @Test(dataProvider = "create", dataProviderClass = StaticProvider.class)
    public void verifyData2(String n1, Integer n2) {  
        System.out.println(n1 + " " + n2);  
    }  
}
