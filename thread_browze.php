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
require_once("class.php");
$thread_test = new Thread();
//エスケープ用
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
//header
if (isset($_SESSION["username"])){
    echo "こんにちは".h($_SESSION["username"])."さん<br>";
}else{
    echo "こんにちはゲストさん<br>";
}
?>
<a href="https://tb-210191.tech-base.net/mission6/thread.php">戻る</a>
</div>

<div class="box">
<?php
//スレッドリンクふみテスト------------------
if (isset($_GET["id"])){ //
    echo "idはこれだよ：".h($_GET["id"])."<br>このidの投稿を下に示す<br>";
    //$_get["id"]に一致する投稿を取得してくるmethod
    $thread_gotten = $thread_test -> getOneThread($_GET["id"])[0];
    echo "スレッドID:<br>".$thread_gotten["threadid"]."<br>";
    echo "タイトル:<br>".$thread_gotten["threadtitle"]."<br>";
    echo "名前:<br>".$thread_gotten["displayName"]."<br>";
    echo "内容:<br>".$thread_gotten["contents"]."<br>";
    echo "コード:<br>".htmlentities($thread_gotten['codecontents'], ENT_QUOTES, 'UTF-8')."<br>";
}else{
    header("Location: thread.php");
}
?>

<?php
//
if (isset($_POST["delete_submit"])){
    $delete_number = $_GET["id"];
    $delete_password = $_POST["delete_password"];
    $delete_flag = $thread_test -> CheckdeleteThreadInbrowze($delete_number,$delete_password);
    if ($delete_flag === "aaa"){ //削除すべきものが存在しないとき
        $nodeleteobj_alert = "<script type='text/javascript'>alert('削除対象番号が入力されていないか、その投稿番号は存在しません。')</script>";
        echo $nodeleteobj_alert;
    }elseif ($delete_flag === "bbb"){ //パスワードが正しくないとき
        $wrongpass_alert = "<script type='text/javascript'>alert('正しいパスワードを入力してください。')</script>";
        echo $wrongpass_alert;
    }elseif ($delete_flag === "ccc"){ //削除が完了したとき
        $deleted_alert = "<script type='text/javascript'>alert('スレッドを削除しました');location.href = 'thread.php';</script>";
        echo $deleted_alert;
    }
}

//削除-------------------------------------------------------------------------
?>
<!--
<form action="" method="post">
    <div>
        <label for = "message"><b>スレッドを作成する</b></label><br>
        ・タイトルとパスワードは必ず入力してください。<br>
        ・内容とコードのどちらかは入力してください。<br>
        タイトル:<input type = "text" name = "threadtitle" title = "スレッドのタイトルを入力してください"><br>
        内容:<br>
        <textarea name = "contents" cols="30" rows="20"></textarea><br>
        コード:<br>
        <textarea name = "codecontents" cols="30" rows="20"></textarea><br>
        password:<input type = "text" name = "threadpassword" pattern="^([a-zA-Z0-9]{3,})$" title = "3字以上の半角英数字を入力してください"/><br>
         <input type = "hidden" name = "csrf_check">
    </div>
    <div class = "button">
        <button type ="submit" name = "thread_submit">新スレッドを作成</button><br>
  </form>
-->

</div>
<div class = "box">
<form action="" method="post">
<label for = "message"><b>このスレッドを削除する</b></label><br>
パスワード:<input type = "text" name = "delete_password"/><br>
<div class = "button"><button type ="submit" name = "delete_submit">削除</button>

<br>
</form>

</div>

</body>
</html>