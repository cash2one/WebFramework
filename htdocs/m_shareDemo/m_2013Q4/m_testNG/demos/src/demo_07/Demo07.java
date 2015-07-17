package demos;

import org.testng.annotations.*;

public class Demo07 {  
  @Parameters({ "words" }) 
  @BeforeClass()
  public void setup(String words) {
    System.out.println(words);
  }

  @Parameters({ "user-name" }) 
  @Test
  public void testMethod1(String userName) {  
    assert "zhangpei".equals(userName);
  }  
}
