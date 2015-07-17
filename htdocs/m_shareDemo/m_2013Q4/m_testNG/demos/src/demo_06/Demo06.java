package demos;

import org.testng.annotations.*;

public class Demo06 {  
  @Test(groups = { "functest", "checkintest" })  
  public void testMethod1() {  
  }  
  @Test(groups = {"functest", "checkintest", "broken"} )  
  public void testMethod2() {  
  }  
  @Test(groups = { "functest" })  
  public void testMethod3() {  
  }  
}
