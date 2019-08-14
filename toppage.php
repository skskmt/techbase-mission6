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

//エスケープ用
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
/*echo $_POST["csrf_check"]."<br>";
echo $_POST["displayname"]."<br>";
echo $_POST["password"]."<br>";
echo "<hr>";*/
require_once("class.php");
$login_test = new Login();
if (isset($_SESSION["username"])){
  if (isset($_SESSION["csrf_token"])&&isset($_POST["csrf_check"])){
    if ($_SESSION["csrf_token"] == $_POST["csrf_check"]){
      echo "こんにちは".h($_SESSION["username"])."さん";//xss
      echo "<hr>";
      function make_html_a() {
        ?>
        <form method="post" name="form1" action="secondpagetest.php">
        <input type="hidden" name="csrf_check" value=<?php echo h($_SESSION["csrf_token"]); ?>>
        <a href="javascript:form1.submit()">マイページテスト</a>
        </form>
        <?php
      }
      make_html_a();
    }else{
      echo "不正なリクエストです。";
    }
  }else{
    echo "不正なリクエストです。";
  }
}else{ //ログイン状態にないとき
  if (isset($_SESSION["csrf_token"])&&isset($_POST["csrf_check"])){
    if ($_SESSION["csrf_token"] == $_POST["csrf_check"]){ //csrf対策
      if(($_POST["displayname"])&&($_POST["password"])){ //usernameとパスワードが入力されているとき
        $displayname=h($_POST["displayname"]); //xss
        $password=h($_POST["password"]); //xss
        if($login_test -> CheckLoginInfo($displayname, $password) == true){
          echo "こんにちは".h($_SESSION["username"])."さん";//xss
          function make_html_b() {
            ?>
            <form method="post" name="form1" action="secondpagetest.php">
            <input type="hidden" name="csrf_check" value=<?php echo h($_SESSION["csrf_token"]); ?>>
            <a href="javascript:form1.submit()">マイページテスト</a>
            </form>
            <?php
          }
          make_html_b();
        }else{
          echo "ログイン失敗。"; //(ログイン失敗の処理はCheckLoginInfo内で行っている)
        }
      }else{
        echo "usernameとpasswordを入力してください。";
      }
    }else{
      echo "不正なリクエストです。<br>";
    }
  }else{
    echo "不正なリクエストです。";
  }
  echo "<hr>";
}
?>
<a href="thread.php">スレッド一覧</a><br>
<a href="session_delete.php">ログアウト</a>
</div>

</body>
</html>