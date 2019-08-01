<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mail_send($email){
    require("mailsetupinfo.php");

    mb_language("japanese");
    mb_internal_encoding("UTF-8");

    require dirname(__FILE__).'/PHPMailer/src/PHPMailer.php';
    require dirname(__FILE__).'/PHPMailer/src/SMTP.php';
    require dirname(__FILE__).'/PHPMailer/src/POP3.php';
    require dirname(__FILE__).'/PHPMailer/src/Exception.php';
    require dirname(__FILE__).'/PHPMailer/src/OAuth.php';
    require dirname(__FILE__).'/PHPMailer/language/phpmailer.lang-ja.php';

    $mailer = new PHPMailer(); //インスタンス生成
    $mailer->IsSMTP(); //SMTPを作成
    $mailer->Host = $hostgmail; //Gmail
    $mailer->CharSet = 'utf-8';
    $mailer->SMTPAuth = TRUE; //SMTP認証
    $mailer->Username = $gmailusername; // Gmailのユーザー名
    $mailer->Password = $gmailpassword; // Gmailのパスワード
    $mailer->SMTPSecure = $smtpsecure;
    $mailer->Port = $smtpport;

    //メール
    $message="リンクをクリックしてメールを認証してください。";//メール本文
    $mailer->From     = $gmailusername; //送信元
    $mailer->FromName = mb_convert_encoding("techbase mission 6","UTF-8","AUTO");
    $mailer->Subject  = mb_convert_encoding("【メール認証のお知らせ】","UTF-8","AUTO");
    $mailer->Body     = mb_convert_encoding($message,"UTF-8","AUTO");
    $mailer->AddAddress($email); //宛先

    //送信する
    if($mailer->Send()){
        echo "送信に成功しました。";
    }
    else{
        echo "送信に失敗しました。" . $mailer->ErrorInfo;
    }
}


?>