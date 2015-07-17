package outfox.ead.click;

import outfox.ead.util.encoder.*;
import outfox.ead.data.ClickAction;
import java.io.*;
import java.util.*;

public class SyndIdExtractor {

    ClickActionEncoderImpl clickActionEncoderImpl;
    Map<String, ClickActionEncoder> encoders;

    public SyndIdExtractor(String aesKeyFile) {
        try {
            // AESCipher aesCipher = new AESCipher("/disk2/test/antifrauder/new-framework2/click_resin/webapps/clk/WEB-INF/data/ead-aes-key.1195118241296");
            AESCipher aesCipher = new AESCipher(aesKeyFile);

            clickActionEncoderImpl = new ClickActionEncoderImpl();
            clickActionEncoderImpl.setAesCipher(aesCipher);

            EadsClickActionEncoderImpl eadsClickActionEncoder = new EadsClickActionEncoderImpl();
            eadsClickActionEncoder.setAesCipher(aesCipher);

            encoders = new HashMap<String, ClickActionEncoder>();
            encoders.put("1", eadsClickActionEncoder);

        } catch(Exception e) {
            System.out.println(e);
        }
    }

    public long decode(String k, String siteTypeStr) {
        try {
            ClickActionEncoder selectedEncoder = clickActionEncoderImpl; // set default encoder,

            if (k != null) {
                if (siteTypeStr != null && encoders.containsKey(siteTypeStr)) 
                {
                    selectedEncoder = encoders.get(siteTypeStr);
                }
                k = java.net.URLDecoder.decode(k);
                ClickAction click = selectedEncoder.decode(k);

                return click.getSyndicationId();
            }
        } catch(Exception e) {
            System.out.println(k + ": " + e);
        }

        return -1;
    }
    
    public static void main(String argv[]) {
        try {
            SyndIdExtractor syndExt = new SyndIdExtractor(argv[0]);
            BufferedReader reader = new BufferedReader(new FileReader(argv[1]));
            String line;
            while ((line = reader.readLine()) != null){
                String k = null, siteTypeStr = null;
                String[] access_line_fields = line.split(" ");
                if (access_line_fields.length < 8)
                    continue;
                
                String url = access_line_fields[7];
                String[] fields = url.split("[&\\?]");
                if (fields.length < 2) continue;

                for(String field: fields) {
                    String[] key_val_pair = field.split("=");
                    if (key_val_pair.length != 2) continue;

                    String key = key_val_pair[0], value = key_val_pair[1];
                    if ("k".equals(key)) {
                        k = value;
                    } else if ("s".equals(key)) {
                        siteTypeStr = value;
                    }
                }

                if (k == null) continue;

                long syndId = syndExt.decode(k, siteTypeStr);
                if (syndId == -1) continue;

                System.out.println(syndId + ":" + url);
            } 

        } catch (Exception e) {
            System.out.println(e);
        }
    }
}
