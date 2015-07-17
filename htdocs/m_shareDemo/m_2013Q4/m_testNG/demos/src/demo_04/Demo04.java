package demos;

import org.testng.annotations.*;

@Test  
public class Demo04 {  
  public void enabledTestMethod_zhangpei() {  
    System.out.println("Your should see me 1");
  }  

  public void enabledTestMethod_luqy() {  
    System.out.println("Your should see me 2");
  }  

  public void enabledTestMethod_kuyan() {  
    System.out.println("Your should see me 3");
  }  

  public void brokenTestMethod_kuyan() {  
    System.out.println("Your should NOT see me 3");
  }  

  public void enabledTestMethodbrokenTestMethod_kuyan() {  
    System.out.println("Your should NOT see me 4");
  }  

  public void broken2TestMethod_kuyan() {  
    System.out.println("Maybe or Maybe NOT");
  }  
} 
