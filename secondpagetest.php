<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body>
<div class="box">
    現在作成中です。session確認用。
<?php
session_start();

//エスケープ用
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

echo "<hr>";

if (!isset($_SESSION["username"])){ 
  function make_html() {
    ?>
      ログインしていません。<br>
      <div><a href="login.php">こちらからログインしてください。</a></div>
    <?php
    }
    make_html();
  
}else{
  //$username_logined = $_SESSION["username"];
  echo h($_SESSION["username"])."さんのページです。"; //xss
}
echo "<hr>"


?>
<form method="post" name="form1" action="toppage.php">
  <input type="hidden" name="csrf_check" value=<?php echo h($_SESSION["csrf_token"]); ?>>
  <a href="javascript:form1.submit()">トップページ</a>
</form>
<a href="session_delete.php">ログアウト</a><br>
</div>
</body>
</html>