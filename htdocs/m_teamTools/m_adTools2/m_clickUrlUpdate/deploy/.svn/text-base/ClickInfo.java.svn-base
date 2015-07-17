package outfox.ead.click;

import java.io.IOException;
import java.security.NoSuchAlgorithmException;
import javax.crypto.NoSuchPaddingException;
import java.security.InvalidKeyException;

import outfox.ead.data.ClickAction;
import outfox.ead.data.EadsClickAction;
import outfox.ead.data.AdType;
import outfox.ead.data.WritableMap;
import outfox.ead.data.EadsClickAction;
import outfox.ead.util.encoder.ClickActionEncoder;
import outfox.ead.util.encoder.ClickActionEncoderImpl;
import outfox.ead.util.encoder.EadsClickActionEncoderImpl;
import outfox.ead.util.encoder.AESCipher;
import outfox.ead.util.encoder.InvalidClickActionException;

import java.util.*;
import java.util.Map.Entry;
import java.text.*;
import java.net.URLDecoder;
import java.net.URLEncoder;

public class ClickInfo {

    private ClickActionEncoderImpl clickActionEncoder;
    private EadsClickActionEncoderImpl eadsClickActionEncoder;

    public ClickInfo() throws Exception {
        AESCipher aesCipher = new AESCipher("data/ead-aes-key.1195118241296");

        clickActionEncoder = new ClickActionEncoderImpl();
        clickActionEncoder.setAesCipher(aesCipher);
    
        eadsClickActionEncoder = new EadsClickActionEncoderImpl();
        eadsClickActionEncoder.setAesCipher(aesCipher);
    }

    // 解析出点击串中的参数及对应的值到HashMap中
    private HashMap<String, String> splitClickUrl(String url) {
        HashMap<String, String> map = new HashMap<String, String>();
        if (url == null) return null;

        String head_str  = "";
        String param_str = ""; 
        String[] fields = url.split("\\?", 2);
        if (fields.length == 1) { 
            param_str = fields[0];
        } else {
            head_str  = fields[0];
            param_str = fields[1];
        }       
        map.put("click-head", head_str);

        fields = param_str.split("&");
        for(String field: fields) {
            String[] kv_pair = field.split("=", 2);
            map.put(kv_pair[0], kv_pair[1]);
        }       

        return map;
    }

    private ClickActionEncoder getEncoder(String s) {
        // s is siteType from url
        ClickActionEncoder selectedEncoder = clickActionEncoder; // set default encoder,
        if (s != null && "1".equals(s)) {
            selectedEncoder = eadsClickActionEncoder;
        }

        return selectedEncoder;
    }
        
    // 根据k和s得到ClickAction对象
    private ClickAction getClickObj(String k, String s) {
        try {
            k = URLDecoder.decode(k, "UTF-8");
        } catch(Exception e) {
            return null;
        }

        ClickAction click = null;
        if (k != null) {
            ClickActionEncoder selectedEncoder = getEncoder(s);
            try {
                click = selectedEncoder.decode(k);
            } catch (InvalidClickActionException e) {
                click = null;
            } catch (Exception e) {
                click = null;
            }
        }
        
        return click;
    }

    private String getTextMapStr(HashMap<String, String> map) {
        String ret_str = "";
        for (Entry<String, String> entry: map.entrySet()) {
            if (ret_str != "") ret_str += ""; 
            ret_str += entry.getKey() + "" + entry.getValue();
        }

        return ret_str;
    }

    // 上层函数，根据url返回详细信息
    private String getDetail(String url) {
        HashMap<String, String> map = splitClickUrl(url);
        ClickAction clkObj = getClickObj(map.get("k"), map.get("s"));
        if (clkObj == null) {
            return "";
        }

        String output_str = "";
        for (Entry<String, String> entry: map.entrySet()) {
            if ("k".equals(entry.getKey())) continue;

            if (output_str != "") output_str += "";
            output_str += entry.getKey() + "" + entry.getValue();
        }

        output_str += "k";
        // from AdItem
        output_str += "adType" + clkObj.getAdType().ordinal();
        output_str += "sponsorId" + clkObj.getSponsorId();
        output_str += "campaignId" + clkObj.getCampaignId();
        output_str += "adGroupId" + clkObj.getAdGroupId();
        output_str += "adVariationId" + clkObj.getAdVariationId();
        output_str += "keywordId" + clkObj.getKeywordId();
        output_str += "superKeyword" + clkObj.getSuperKeyword();
        output_str += "syndicationId" + clkObj.getSyndicationId();
        output_str += "siteId" + clkObj.getSiteId();
        output_str += "codeId" + clkObj.getCodeId();
        output_str += "origCost" + clkObj.getOrigCost();
        output_str += "actuCost" + clkObj.getActuCost();
        output_str += "imprPos" + clkObj.getImprPos();
        output_str += "qualityScore" + clkObj.getQualityScore();
        output_str += "rank" + clkObj.getRank();
        output_str += "imprIp" + clkObj.getImprIp();
        output_str += "imprRequest" + clkObj.getImprRequest();
        output_str += "imprTime" + clkObj.getImprTime();
        // output_str += "adVariation" + clkObj.getAdVariation();
        // output_str += "keyword" + clkObj.getKeyword();
        output_str += "nominatorId" + clkObj.getNominatorId();
        output_str += "comments" + clkObj.getComments();
        output_str += "textMap" + getTextMapStr(clkObj.getTextMap());

        // from ClickAction
        // output_str += "id" + clkObj.getId();
        output_str += "referer" + clkObj.getReferer();
        output_str += "clickerIp" + clkObj.getClickerIp();
        output_str += "clickerId" + clkObj.getClickerId();
        output_str += "clickTime" + clkObj.getClickTime();
        output_str += "commitTime" + clkObj.getCommitTime();
        output_str += "signature" + clkObj.getSignature();
        output_str += "memberId" + clkObj.getMemberId();

        return output_str;
    }

    private ClickAction parseFormatStr(String param_val, String s) {
        ClickAction clkObj = new ClickAction(0);

        String[] p_kv_list = param_val.split("");
        for (String p_kv_str: p_kv_list) {
            String[] p_kv_pair = p_kv_str.split("");
            if (p_kv_pair.length != 2) {
                continue;
            }
            String p_k = p_kv_pair[0];
            String p_v = p_kv_pair[1];

            if ("sponsorId".equals(p_k)) {
                clkObj.setSponsorId(Long.parseLong(p_v));

            } else if ("adType".equals(p_k)) {
                clkObj.setAdType(AdType.getAdType(Integer.parseInt(p_v)));
                    
            } else if ("campaignId".equals(p_k)) {
                clkObj.setCampaignId(Long.parseLong(p_v));

            } else if ("adGroupId".equals(p_k)) {
                clkObj.setAdGroupId(Long.parseLong(p_v));

            } else if ("adVariationId".equals(p_k)) {
                clkObj.setAdVariationId(Long.parseLong(p_v));

            } else if ("keywordId".equals(p_k)) {
                clkObj.setKeywordId(Long.parseLong(p_v));

            } else if ("superKeyword".equals(p_k)) {
                clkObj.setSuperKeyword(p_v);

            } else if ("syndicationId".equals(p_k)) {
                clkObj.setSyndicationId(Long.parseLong(p_v));

            } else if ("siteId".equals(p_k)) {
                clkObj.setSiteId(Long.parseLong(p_v));

            } else if ("codeId".equals(p_k)) {
                clkObj.setCodeId(Long.parseLong(p_v));

            } else if ("origCost".equals(p_k)) {
                clkObj.setOrigCost(Integer.parseInt(p_v));

            } else if ("actuCost".equals(p_k)) {
                clkObj.setActuCost(Integer.parseInt(p_v));

            } else if ("imprPos".equals(p_k)) {
                clkObj.setImprPos(Integer.parseInt(p_v));

            } else if ("qualityScore".equals(p_k)) {
                clkObj.setQualityScore(Float.parseFloat(p_v));

            } else if ("rank".equals(p_k)) {
                clkObj.setRank(Float.parseFloat(p_v));

            } else if ("imprIp".equals(p_k)) {
                clkObj.setImprIp(p_v);

            } else if ("imprRequest".equals(p_k)) {
                clkObj.setImprRequest(p_v);

            } else if ("imprTime".equals(p_k)) {
                clkObj.setImprTime(Long.parseLong(p_v));

            } else if ("nominatorId".equals(p_k)) {
                clkObj.setNominatorId(Integer.parseInt(p_v));

            } else if ("referer".equals(p_k)) {
                clkObj.setReferer(p_v);

            } else if ("clickerIp".equals(p_k)) {
                clkObj.setClickerIp(p_v);

            } else if ("clickerId".equals(p_k)) {
                clkObj.setClickerId(Long.parseLong(p_v));

            } else if ("clickTime".equals(p_k)) {
                clkObj.setClickTime(Long.parseLong(p_v));

            } else if ("signature".equals(p_k)) {
                clkObj.setSignature(Long.parseLong(p_v));

            } else if ("memberId".equals(p_k)) {
                clkObj.setMemberId(Long.parseLong(p_v));

            } else if ("textMap".equals(p_k)) {
                WritableMap txtMap =  new WritableMap();
                
                String[] fields = p_v.split("");
                for (String field: fields) {
                    String[] kv_pair = field.split("");
                    txtMap.put(kv_pair[0], kv_pair[1]);
                }
    
                clkObj.setTextMap(txtMap);
            }
        }

        if ("1".equals(s)) {
            return new EadsClickAction(clkObj);
        } 

        return clkObj;
    }

    private String getClickUrl(String composed_str) throws Exception {
        HashMap<String, String> map = new HashMap<String, String>();

        String[] fields = composed_str.split("");
        for(String field: fields) {
            String[] kv_pair = field.split("");
            String param_name = kv_pair[0];
            String param_val  = kv_pair[1];
            map.put(param_name, param_val);
        }

        String click_url = "";
        String click_url_head = map.get("click-head");
        String k = map.get("k");
        String d = map.get("d");
        String s = map.get("s");
        String cac_all = map.get("cac_all");

        if (click_url_head != null) {
            click_url = click_url_head + "?";
        }

        if (k != null) {
            ClickActionEncoder selectedEncoder = getEncoder( map.get("s") );
            ClickAction clkObj = parseFormatStr(k, map.get("s"));
            String click_str = selectedEncoder.encode(clkObj);
            click_url += "k=" + URLEncoder.encode(click_str, "UTF-8");
        }

        if (d != null) {
            click_url += "&d=" + d;
        }

        if (s != null) {
            click_url += "&s=" + s;
        }

        if (cac_all != null) {
            click_url += "&cac_all=" + cac_all;
        }
        
        return click_url;
    }

    private void test() throws Exception {
        String clickUrl = "http://nc107x.corp.youdao.com:18382/clk/request.s?k=KXgKfo%2FGOZxD2dLq1US4Z1quVblNJ4qFajIYJ3Oct9G1%2BZXhVH84UNkjTBuAlOLe7djTm%2BH7bJh2l3VfQco7Uy3O1LC5U%2BzPJN%2BRhEaCrSg07f67VfNY6UzO%2BiJMh8JSIjuhQquCDCU09FNd2IjjgmnG9csExH2d5fQcfOS0QIvYdx%2F5gFbTKiNxHObJpN3YxheYU3rglnU%2B0AR0iyiI5ph7dxcRS%2FUJqkQ8O3c0RkBtx3Frm5VXG0NMLO2%2FimaKWmzBHSku42W2%2FsHOLLilm8WcZS%2BVyEAmgsaOh3qTtdxC9ZsIByWfOx%2F3F9Y3Y6exEVmd%2BIWeDOtJUhdcIWO9Qo8hhnWd8YWUJLJjoskkN3wa4V19YxlcO%2FDTfHrE5L3jqfl1nqc%2BL0y%2Bcg5JO33g2kKtd2Ee6Mg6JIYi5ZOxntZkL%2BcVgtiwONk9qe%2BZL8uKFsd4fJEkfwWx5DAWHdM7TEpmzTJn2nit62E2RugsAw3Xxo%2BoRxcJpjjAgKuViCqv18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq9gdZRL6f68GChkxXRnwD1h&d=http%3A%2F%2Fhk-jigsaw.com%2Fcn%2Flisting_page.php%3Fcategory%3Dbusiness%26keyword%3D%25E5%25B9%25BF%25E5%2591%258A%25E7%25A4%25BC%25E5%2593%2581&s=4&cac_all=cac_a-1__cac_b-20657__cac_c-38.32.38.32.38.32__cac_d-110.0__cac_e-0__cac_f-11";
        clickUrl = "http://nc107x.corp.youdao.com:18382/clk/request.s?k=hxYPakDeEZOSMi8V%2BJC21OA4Mx15Avt6BMQtlpvsiEEXC7c79fo8muXIkpqkijJanOZTdV8m7jyrHBNUHdE5OKR8z5CzdeSfj6yo%2F0oaghrWCtGU7OUozoqyAbqzzwDbZJBrITgHZtDVzR2hxBbXIPzVnJCi7EfFjT1G7HPoWX%2BCz85TF%2FB4mIbBPLjrGKeBMGC9xH59kIfTurmvhou6tcpit0opvI3v%2F7kwLVB%2BxPgiEvFRkdh3mgYZWo4Sqd2mmulkkuOKxdI%2F9Do3N%2B2L2O2YJrVkcmNAMEV8oxwXHMLHTamlaqoUoqMgGw4b262yE1GUT68710PjLimrlnje%2FBo5Td%2FdIlrndrUCgOznRwS%2FiInaus0VzUoreFhq0HRf6jrbumsI9K4cVqdZu9WWc3LT0jHP6O2Zgq4da6dCF5%2BNOpcBkMZ3wYrNmbm66Fsc18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq%2FBMpJuye5JOSiDMHBWBmuVlpdUX7FUs8fLVOkL%2FcDnzYyC4p3x3qzMFbJFUDIPFXYNchHbwAMSLVsxQpwPRv%2B6f%2B9x4akUv0yqJtfVjrap2A%3D%3D&d=http%3A%2F%2Fwww.5i591.net%2F%3Ftg%3Dyd&s=1";
        System.out.println(clickUrl);

        String click_info = getDetail(clickUrl);
        System.out.println(click_info);

        System.out.println(getClickUrl(click_info));
    }

    private void test2() throws Exception {
        String detail_str = "click-headhttp://d.clkservice.youdao.com/clk/request.sdhttp%3A%2F%2Fwww.szy.com.cn%2Fs4kadType0sponsorId232409campaignId100964adGroupId587464adVariationId1649120keywordId34167808superKeywordsyndicationId1002siteId17010codeId1434959438966752930origCost10actuCost10imprPos1qualityScore0.0rank0.0imprIp112.254.76.214imprRequesthttp://www.zgspark.com/junshiretu/2012/1027/1499.htmlimprTime1375459027920nominatorId0commentsnullrefererclickerIpclickerId0clickTime0commitTime0signature-8106394435047782531memberId0textMapdhttp://www.szy.com.cn/s4adtypeTEXTreqidqt142_9091_181_1375459027920_409427597";
        System.out.println(detail_str);

        System.out.println(getClickUrl(detail_str));
    }

    public static void main(String args[]) throws Exception {
        ClickInfo info = new ClickInfo();
        
        if ("detail".equals(args[0])) {
            System.out.println(info.getDetail(args[1]));

        } else if ("click".equals(args[0])) {
            System.out.println(info.getClickUrl(args[1]));
        }
    }
}
