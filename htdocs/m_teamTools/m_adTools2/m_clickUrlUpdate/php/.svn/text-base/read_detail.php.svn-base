<?php

$click_url = $_POST["click_url"];
// $click_url = "http://nc107x.corp.youdao.com:18382/clk/request.s?k=KXgKfo%2FGOZxD2dLq1US4Z1quVblNJ4qFajIYJ3Oct9G1%2BZXhVH84UNkjTBuAlOLe7djTm%2BH7bJh2l3VfQco7Uy3O1LC5U%2BzPJN%2BRhEaCrSg07f67VfNY6UzO%2BiJMh8JSIjuhQquCDCU09FNd2IjjgmnG9csExH2d5fQcfOS0QIvYdx%2F5gFbTKiNxHObJpN3YxheYU3rglnU%2B0AR0iyiI5ph7dxcRS%2FUJqkQ8O3c0RkBtx3Frm5VXG0NMLO2%2FimaKWmzBHSku42W2%2FsHOLLilm8WcZS%2BVyEAmgsaOh3qTtdxC9ZsIByWfOx%2F3F9Y3Y6exEVmd%2BIWeDOtJUhdcIWO9Qo8hhnWd8YWUJLJjoskkN3wa4V19YxlcO%2FDTfHrE5L3jqfl1nqc%2BL0y%2Bcg5JO33g2kKtd2Ee6Mg6JIYi5ZOxntZkL%2BcVgtiwONk9qe%2BZL8uKFsd4fJEkfwWx5DAWHdM7TEpmzTJn2nit62E2RugsAw3Xxo%2BoRxcJpjjAgKuViCqv18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq9gdZRL6f68GChkxXRnwD1h&d=http%3A%2F%2Fhk-jigsaw.com%2Fcn%2Flisting_page.php%3Fcategory%3Dbusiness%26keyword%3D%25E5%25B9%25BF%25E5%2591%258A%25E7%25A4%25BC%25E5%2593%2581&s=4&cac_all=cac_a-1__cac_b-20657__cac_c-38.32.38.32.38.32__cac_d-110.0__cac_e-0__cac_f-11";

$cmd = "cd ../deploy; ./run.sh detail '$click_url'";
exec($cmd, $lines);

$ret_str = $lines[0];
$param_list = explode("", $ret_str);
$k_val = "";
foreach($param_list as $kv_field) {
    list($p_name, $p_val) = explode("", $kv_field);
    
    if ($p_name == "click-head") {
        echo "<tr class='pre_url'><th class='head'>pre_url</th><td><input type=text value='$p_val'/></td></tr>\n";

    } elseif ($p_name == "d" || $p_name == "s" || $p_name == "cac_all") {
        echo "<tr class='$p_name'><th class='head'>$p_name</th><td><input type=text value='$p_val'/></td></tr>\n";

    } elseif ($p_name == "k") {
        $k_val = $p_val;
    }
}

echo "<tr style='background:yellow'><th colspan='2'>content for k</th></tr>\n";
$k_param_list = explode("", $k_val);
$t_val = "";
foreach($k_param_list as $k_param) {
    list($kp_name, $kp_val) = explode("", $k_param);
    if ($kp_name == "textMap") {
        $t_val = $kp_val;

    } else {
        echo "<tr class='k'><th class='head'>$kp_name</th><td><input type=text value='$kp_val'/></td></tr>\n";
    }
}

echo "<tr style='background:yellow'><th colspan='2'>content for textMap</th></tr>\n";
$t_param_list = explode("", $t_val);
foreach($t_param_list as $t_param) {
    list($tp_name, $tp_val) = explode("", $t_param);
    echo "<tr class='t'><th class='head'>$tp_name</th><td><input type=text value='$tp_val'/></td></tr>\n";
}
