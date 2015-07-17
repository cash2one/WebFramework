package outfox.ead.click;

import java.io.IOException;
import java.security.NoSuchAlgorithmException;
import javax.crypto.NoSuchPaddingException;
import java.security.InvalidKeyException;

import outfox.ead.data.ClickAction;
import outfox.ead.data.EadsClickAction;
import outfox.ead.util.encoder.ClickActionEncoder;
import outfox.ead.util.encoder.ClickActionEncoderImpl;
import outfox.ead.util.encoder.EadsClickActionEncoderImpl;
import outfox.ead.util.encoder.AESCipher;
import outfox.ead.util.encoder.InvalidClickActionException;

public class ShowClickInfo {

    private ClickActionEncoderImpl clickActionEncoder;
    private EadsClickActionEncoderImpl eadsClickActionEncoder;

    public ShowClickInfo() throws IOException, NoSuchAlgorithmException, NoSuchPaddingException, InvalidKeyException {
        AESCipher aesCipher = new AESCipher("data/ead-aes-key.1195118241296");

        clickActionEncoder = new ClickActionEncoderImpl();
        clickActionEncoder.setAesCipher(aesCipher);
    
        eadsClickActionEncoder = new EadsClickActionEncoderImpl();
        eadsClickActionEncoder.setAesCipher(aesCipher);
    }

    public void showInfo(String k, String s) {
        // s is siteType from url
        ClickAction click = null;
        ClickActionEncoder selectedEncoder = clickActionEncoder; // set default encoder,

        if (k != null) {
            try {
                if (s != null && "1".equals(s)) {
                    selectedEncoder = eadsClickActionEncoder;
                }
                click = selectedEncoder.decode(k);
            } catch (InvalidClickActionException e) {
                click = null;
            } catch (Exception e) {
                click = null;
            }
        }
        
        if (click == null) {
            System.out.println("Can't parse clickAction");

        } else {
            System.out.println(click);
        }
    }

    public static void main(String args[]) throws IOException, NoSuchAlgorithmException, NoSuchPaddingException, InvalidKeyException {
        // String k = "uoUDmtFzs8eNcLhmGdY/TGLphd2JhCQhHJ5VJhnERbwW1ATzIpmKpG+dJV5AFja1oXpPzt/BBnoRappTs8Riy0VWKNgzi1vaOGH0f3Fw+f2uNIMk7bAy3Yef81CrXmDfRGQOqVammt+BVvBhlqR5uvEuVk1Pm6UlpXwLsmvjXyIM37V7lHW7Qqm1F9Q813Ia2i96TbCXP6WSAEO0cyMR8gJ+D9oC4MWyiy8/C+T1l40E6/WMhtyaZirjpfeYE+RoMT/kFHgEQMfIbhLsC0a52VR7RmDnaxeAeptUyiYQwiN2a8guUNxsIK0xP3aElYMx18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq/Xxo+oRxcJpjjAgKuViCqvZEFbYcRrGvnpGcHLKCsGcw==";
        // String s = null;

        ShowClickInfo info = new ShowClickInfo();
        info.showInfo(args[0], args[1]);
    }
}
