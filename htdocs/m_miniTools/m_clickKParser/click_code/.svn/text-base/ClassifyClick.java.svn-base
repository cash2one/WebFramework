package outfox.ead.click;

import java.io.IOException;
import java.security.NoSuchAlgorithmException;
import javax.crypto.NoSuchPaddingException;
import java.security.InvalidKeyException;

import java.io.*;
import java.util.Map;
import java.util.Map.Entry;
import java.util.HashMap;
import java.util.ArrayList;
import java.util.List;
import java.net.URLDecoder;

import outfox.ead.data.ClickAction;
import outfox.ead.data.EadsClickAction;
import outfox.ead.util.encoder.ClickActionEncoder;
import outfox.ead.util.encoder.ClickActionEncoderImpl;
import outfox.ead.util.encoder.EadsClickActionEncoderImpl;
import outfox.ead.util.encoder.AESCipher;
import outfox.ead.util.encoder.InvalidClickActionException;

public class ClassifyClick {

    private ClickActionEncoderImpl clickActionEncoder;
    private EadsClickActionEncoderImpl eadsClickActionEncoder;

    public ClassifyClick() throws IOException, NoSuchAlgorithmException, NoSuchPaddingException, InvalidKeyException {
        AESCipher aesCipher = new AESCipher("data/ead-aes-key.1195118241296");

        clickActionEncoder = new ClickActionEncoderImpl();
        clickActionEncoder.setAesCipher(aesCipher);
    
        eadsClickActionEncoder = new EadsClickActionEncoderImpl();
        eadsClickActionEncoder.setAesCipher(aesCipher);
    }

    private String get_type(String k, String s) {
        try {
            k = URLDecoder.decode(k, "UTF-8");
        } catch(Exception e) {
            return null;
        }

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
                return null;
            } catch (Exception e) {
                return null;
            }
        }
            
        long syndId = click.getSyndicationId();
        if (syndId >= 5 && syndId < 11) return "mail";
        else if (syndId == 16) return "offline";
        else if (syndId >= 10 && syndId < 21) return "channel";
        else if (syndId >= 50 && syndId < 101) return "dict";
        else if (syndId >= 101 && syndId < 151) return "dsp";
        else if (syndId >= 1000) return "union";
        else return "search";
    }

    private String[] get_ks(String line) {
        String k = "";
        String s = "";
        String url = "";

        boolean url_found = false;
        char key_name = 0;
        int value_start_idx = -1;
        StringBuilder sbUrl = new StringBuilder();

        int line_len = line.length();
        for (int idx = 0; idx < line_len; idx ++) {
            char ch = line.charAt(idx);
            if (ch == ' ' && idx + 4 < line_len && line.substring(idx + 1, idx + 5).equals("/clk")) {
                url_found = true;
                continue;
            }

            if (url_found == true) {
                // end of url
                if (ch == ' ' || ch == '&') {
                    int value_end_idx = idx;
                    if (key_name == 'k') {
                        k =  line.substring(value_start_idx, value_end_idx);
                    } else if(key_name == 's') {
                        s =  line.substring(value_start_idx, value_end_idx);
                    }

                    if (ch == ' ') {
                        url = sbUrl.toString();
                        break;
                    }
                } else if (ch == '=') {
                    key_name = line.charAt(idx - 1);
                    value_start_idx = idx + 1;
                }

                sbUrl.append(ch);
            }
        }

        String k_s_url[] = {k, s, url};
        return k_s_url;
    }

    public void classify(String input_file, String outputDir) throws IOException {
        Map<String, ArrayList<String>> type_url_map = new HashMap<String, ArrayList<String>>();

        FileReader fr = new FileReader(input_file);
        BufferedReader br = new BufferedReader(fr); 
        String line = br.readLine(); 
        while(line != null) { 

            String[] ksArray = get_ks(line);
            line = br.readLine(); 

            if (ksArray[0].equals("")) continue;

            String type = get_type(ksArray[0], ksArray[1]);
            if (type == null) continue;

            if (! type_url_map.containsKey(type)) {
                type_url_map.put(type, new ArrayList<String>());
            }

            type_url_map.get(type).add(ksArray[2]);
        } 
        br.close(); 
        fr.close(); 

        // write results to file
        for(Entry<String, ArrayList<String>> entry: type_url_map.entrySet()) {
            String type = entry.getKey();
            List<String> lineList = entry.getValue();
            
            String file_path = outputDir + "/" + type;
            BufferedWriter bw=new BufferedWriter(new FileWriter(file_path));
            int max_cnt = 100;
            for (String url: lineList) {
                bw.write(url + "\n");
                max_cnt --;
                if (max_cnt == 0) break;
            }
            bw.close();
        }
    }

    public static void main(String args[]) throws IOException, NoSuchAlgorithmException, NoSuchPaddingException, InvalidKeyException {
        ClassifyClick info = new ClassifyClick();
        info.classify(args[0], args[1]);
    }
}
