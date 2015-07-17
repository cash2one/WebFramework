package demos;

import org.testng.annotations.*;

@Test  
public class Demo05 {  
  @Test(groups = { "windows.123" })   
  public void testWindowsOnly() {  
  }  

  @Test(groups = {"linux.456"} )  
  public void testLinuxOnly() {  
  }  

  @Test(groups = { "windows.789"} )  
  public void testWindowsToo() {  
  }  
} 
