package demos;

import org.testng.annotations.*;
 
public class Demo01 {
    @BeforeClass
    public void setUp() {
        // code that will be invoked when this test is instantiated
        System.out.println("I am invoked when test class begins");
    }
 
    @Test(groups = { "fast" })
    public void aFastTest() {
        System.out.println("Fast test");
    }
 
    @Test(groups = { "slow" })
    public void aSlowTest() {
        System.out.println("Slow test");
    }
}
