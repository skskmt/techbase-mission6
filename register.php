<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body>
<div class="box">
  <form action="" method="post">
    <div>
        <label for = "message"><b>登録用フォーム</b></label><br>
        username:<input type = "text" name = "displayname" pattern="^[0-9A-Za-z]+$" title = "半角英数字を入力してください"><br>
        password:<input type = "text" name = "password" pattern="^([a-zA-Z0-9]{3,})$" title = "3字以上の半角英数字を入力してください"/><br>
        email:<input type = "text" name = "email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title = "emailアドレスを入力してください"/>
        <input type = "hidden" name = "edit_flag">
    </div>
    <div class = "button">
        <button type ="submit" name = "register_submit">仮登録メールを送信</button>
  </form>
<?php echo "<hr>" ?>

  <form action="" method="post">
        <label for = "message"><b>削除用フォーム（管理者用）</b></label><br>
        削除対象番号:<input type = "text" name = "delete_number"/><br>
        パスワード:<input type = "text" name = "delete_password"/><br>
    <div class = "button">
        <button type ="submit" name = "delete_submit">削除</button>
    <br>
    <div>
  </form>
</div>
<?php
//エスケープ用
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
/*-----------------------------------DATABASE--------------------------------*/
//echo "<hr>";
require_once("class.php");
$register_test = new Register();
//database上に作成したtableを表示。後で消す。
//$register_test -> showTable();


/*-----------------------------------ボタン押したときの処理--------------------------------*/
echo "<hr>";
echo "message:<br>";
if (isset($_POST["register_submit"])){  //登録ボタンが押されたときの処理。
    if(($_POST["displayname"])&&($_POST["password"])&&($_POST["email"])){ //usernameとパスワードが入力されているとき
        $displayname=h($_POST["displayname"]); //xss
        $password=h($_POST["password"]); //xss
        $email=h($_POST["email"]); //xss
        $register_test -> CheckRegisterData($displayname, $password, $email);
    }else{
        echo "userIDとpasswordを入力してください。";
    }
}elseif (isset($_POST["delete_submit"])){ //削除ボタンが押されたときの処理。
    $delete_number = h($_POST["delete_number"]); //xss
    $delete_password = h($_POST["delete_password"]); //xss
    $register_test -> CheckdeleteRegisteredInfo($delete_number, $delete_password);
}
/*-----------------------------------表示--------------------------------*/
echo "<hr>";
$register_test -> showRegistered();
echo "<hr>";
?>
<a href="login.php">すでに登録済みの方はこちら</a>
<br>
<a href="https://github.com/skskmt/techbase-mission6/" target="_blank">githubのページはこちら</a>
<?php
echo "<hr>";
//$register_test -> showRegisteredvardump();
?>
  </body>
</html>

