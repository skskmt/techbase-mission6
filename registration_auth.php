<html>
  <head>
  <title>techbase mission 6 dev</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body>
  <div class="box">
<?php
require_once("class.php");
$register_auth = new Register(); 
if (isset($_GET["urltoken"])){
    $token_get = $_GET["urltoken"];
    $register_auth -> checkUrltoken($token_get);
}

?>
<br>
<a href="login.php">こちらからログインしてください。</a>
</div>
</body>
</html>