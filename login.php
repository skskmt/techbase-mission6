<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
<div class="box">
  </head>
  <body>
  <form action="" method="post">
    <div>
        <label for = "message"><b>ログインフォーム</b></label><br>
        username:<input type = "text" name = "displayname" pattern="^[0-9A-Za-z]+$" title = "半角英数字を入力してください"><br>
        password:<input type = "text" name = "password" pattern="^([a-zA-Z0-9]{3,})$" title = "3字以上の半角英数字を入力してください"/><br>
<!--    email:<input type = "text" name = "email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title = "emailアドレスを入力してください"/>  -->
    </div>
    <div class = "button">
        <button type ="submit" name = "login_submit">ログイン</button><br>
  </form>
<?php
echo "<hr>";
require_once("class.php");
$login_test = new Login();
/*-----------------------------------ボタン押したときの処理--------------------------------*/
if (isset($_POST["login_submit"])){  //ログインボタンが押されたときの処理。
    if(($_POST["displayname"])&&($_POST["password"])){ //usernameとパスワードが入力されているとき
        $displayname=$_POST["displayname"];
        $password=$_POST["password"];
        $login_test -> CheckLoginInfo($displayname, $password);
    }else{
        echo "usernameとpasswordを入力してください。";
    }
}

echo "<hr>";
?>
<a href="register.php">未登録の方はこちら</a>
<br>
<a href="https://github.com/skskmt/techbase-mission6/" target="_blank">githubのページはこちら</a>
</div>

  </body>
</html>