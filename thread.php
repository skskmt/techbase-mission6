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
//エスケープ用関数
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//ヘッダー的なやつ----------------------------------------------//
if (isset($_SESSION["username"])){
    echo "こんにちは".h($_SESSION["username"])."さん<br>";
}else{
    echo "こんにちはゲストさん<br>";
}
?>
<form method="post" name="form1" action="toppage.php">
<input type="hidden" name="csrf_check" value=<?php echo h($_SESSION["csrf_token"]); ?>><a href="javascript:form1.submit()">トップページ</a>
<a href="session_delete.php">ログアウト</a>
</div></form>

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
}

/*function make_html_b() {
    ?>
    <div><a href="https://tb-210191.tech-base.net/mission6/thread.php?id=1">おしらせ</a></div>
    <?php
    }
make_html_b();*/
?>

<?php
//
if (isset($_POST["thread_submit"])){ //新スレッド作成ボタンが押されたとき
    //データベースにpost送信された値を登録
    if (isset($_SESSION["username"])){
        if ($_POST["threadtitle"]&&$_POST["threadpassword"]){ //タイトルとパスワードが入力されているとき
                if (($_POST["contents"]) || ($_POST["codecontents"])){ //内容とコードのどちらかが入力されているとき
                    $threadpassword = $_POST["threadpassword"];
                    $displayname = $_SESSION["username"];
                    $threadtitle = $_POST["threadtitle"];
                    $contents = $_POST["contents"];
                    $codecontents = $_POST["codecontents"];
                    $thread_test -> makeNewThread($threadpassword,$displayname,$threadtitle,$contents,$codecontents);
                    $makenewthread_alert = "<script type='text/javascript'>alert('".$displayname."さんの新規スレッド作成を受け付けました。')</script>";
                    echo $makenewthread_alert;
                }else{
                    $nocontents_alert = "<script type='text/javascript'>alert('内容とコードのどちらかは入力してください。')</script>";
                    echo $nocontents_alert;
                }
            
        }else{
            $notitleorpassword_alert = "<script type='text/javascript'>alert('タイトルとパスワードを両方入力してください。')</script>";
            echo $notitleorpassword_alert;
        }
    }else{
        $login_alert = "<script type='text/javascript'>alert('スレッド作成にはログインが必要です。こちらからログインしてください。');location.href = 'login.php';</script>";
        echo $login_alert;
    }
}elseif (isset($_POST["delete_submit"])){
    if ($_POST["delete_number"]){
        $delete_number = $_POST["delete_number"];
        $delete_password = $_POST["delete_password"];
        $delete_flag = $thread_test -> CheckdeleteThread($delete_number,$delete_password);
        if ($delete_flag === "aaa"){ //削除すべきものが存在しないとき
            $nodeleteobj_alert = "<script type='text/javascript'>alert('削除対象番号が入力されていないか、その投稿番号は存在しません。')</script>";
            echo $nodeleteobj_alert;
        }elseif ($delete_flag === "bbb"){ //パスワードが正しくないとき
            $wrongpass_alert = "<script type='text/javascript'>alert('正しいパスワードを入力してください。')</script>";
            echo $wrongpass_alert;
        }elseif ($delete_flag === "ccc"){ //削除が完了したとき
            $deleted_alert = "<script type='text/javascript'>alert('スレッドを削除しました')</script>";
            echo $deleted_alert;
        }
    }else{
        $test_alert = "<script type='text/javascript'>alert('削除番号を入力してください')</script>";
        echo $test_alert;
    }
}
//スレッド一覧---------------------------------------------------------------------------
echo "<b>スレッド一覧</b><br>";
//for文使ってタイトルとリンクだけ出す
$thread_data = $thread_test -> getThreadLink();
?>
<?php foreach ($thread_data as $data): ?>
    <a href="https://tb-210191.tech-base.net/mission6/thread_browze.php?id=<?php echo $data["threadid"];?>"><?php echo $data["threadtitle"]."<br>";?></a>
<?php endforeach ?>
</div>
<div class="box">
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
        <!-- <input type = "hidden" name = "csrf_check"> -->
    </div>
    <div class = "button">
        <button type ="submit" name = "thread_submit">新スレッドを作成</button><br>
  </form>
<?php
//echo "<hr>";
?>
<!--
<form action="" method="post">
<label for = "message"><b>スレッド削除用フォーム</b></label><br>
削除対象番号:<input type = "text" name = "delete_number"/><br>
パスワード:<input type = "text" name = "delete_password"/><br>
<div class = "button"><button type ="submit" name = "delete_submit">削除</button>
-->
</form>

</div>

</body>
</html>