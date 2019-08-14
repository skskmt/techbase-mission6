<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body>
  <div class="box">
<?php
session_start();
unset($_SESSION["username"]);

echo "ログアウトしました。";
//echo $_SESSION["username"]; 確認用。あとで削除。

?>
<br>
<a href="login.php">こちらからログインしてください。</a>
</div>
</body>
</html>