package demos;

import org.testng.annotations.*;

public class WebTest{
    private int m_numberOfTimes;  

    public WebTest(int numberOfTimes) {  
        m_numberOfTimes = numberOfTimes;  
    }  

    @Test  
    public void testServer() {  
        for (int i = 0; i < m_numberOfTimes; i++) {  
            // access the web page  
        }  
    }  
}
