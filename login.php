<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
<div class="box">
  </head>
  <body>
  <?php
  //csrf対策
  session_start();
  $csrf_token = hash('sha256',uniqid(rand(),1));
  $_SESSION["csrf_token"] = $csrf_token;

  ?>
  <form action="/mission6/toppage.php" method="post">
    <div>
        <label for = "message"><b>ログインフォーム</b></label><br>
        username:<input type = "text" name = "displayname" pattern="^[0-9A-Za-z]+$" title = "半角英数字を入力してください"><br>
        password:<input type = "text" name = "password" pattern="^([a-zA-Z0-9]{3,})$" title = "3字以上の半角英数字を入力してください"/><br>
        <input type = "hidden" name = "csrf_check" value = <?php echo $csrf_token; ?>>
<!--    email:<input type = "text" name = "email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title = "emailアドレスを入力してください"/>  -->
    </div>
    <div class = "button">
        <button type ="submit" name = "login_submit">ログイン</button><br>
  </form>
<?php
//エスケープ用
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if (isset($_SESSION["username"])){
  header("Location: toppage.php");
}else{
  echo "<hr>";
  require_once("class.php");
  $login_test = new Login();
  /*-----------------------------------ボタン押したときの処理--------------------------------*/
  if (isset($_POST["login_submit"])){  //ログインボタンが押されたときの処理。
    header("Location: toppage.php");
      /*if(($_POST["displayname"])&&($_POST["password"])){ //usernameとパスワードが入力されているとき
          $displayname=h($_POST["displayname"]); //xss
          $password=h($_POST["password"]); //xss

          $login_test -> CheckLoginInfo($displayname, $password);
      }else{
          echo "usernameとpasswordを入力してください。";
      }*/
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