<?php
//插入或更新一个帖子，输入postid参数则更新帖子，不输入postid参数则插入一个新的帖子
require_once("Post.class.php");
require_once("User.class.php");
require_once './PHPMailer/class.phpmailer.php';

$postid = $_GET["postid"];
$title = $_GET["title"];
$author = $_GET["author"];
$type = "1";
$tag = "1";
$content = $_GET["content"];
$mail_send = $_GET["mail_send"];

function send_mail($user_list, $subject, $body, $user) {
    $time = date('Y-m-d H:i:s', time());

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->CharSet  = "UTF-8";
    $mail->Host     = '220.181.8.38';                     // Specify main and backup server
    $mail->Username = 'youdao_adtest02@163.com';          // SMTP username
    $mail->From     = 'youdao_adtest02@163.com';
    $mail->FromName = 'Selenium分享';
    foreach ($user_list as $ldap_name => $name) {
        $mail->addAddress($ldap_name . "@rd.netease.com", $name);  // Add a recipient
    }

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = "[Selenium有新分享]: " . $subject;
    $mail->Body    = "<b>${user}于${time}在<a href='http://selenium.iyoudao.net'>论坛</a>分享了他的心得:</b><br><pre>" . $body . "</pre>";

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }

    return true;
}

$post = new Post();
if($postid == "-1"){
    $result = $post->insert($title,$author,$type,$tag,$content);
    if ($mail_send == "1") {
        send_mail($user_list, $title, $content, $author);
    }
    echo "1";
}else{
    $post->update($postid,$title,$author,$type,$tag,$content);
    echo "1";
}

$post->closeDB();

?>
