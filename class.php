<?php
class Register {
    //serverに接続する
    protected function connectServer(){ 
        require("databaseinfo.php"); //databese設定
        $pdo = new PDO($dsn, $user, $password_server, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        return $pdo;
    }

    //データベース内にテーブルを作成する
    public function __construct(){
        $pdo = $this -> connectServer();
        $sql = "CREATE TABLE IF NOT EXISTS tbl_user_test6" 
        ." ("
        .  "userId INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        .  "password CHAR(255) NOT NULL DEFAULT '',"
        .  "displayName VARCHAR(64) NOT NULL DEFAULT '' UNIQUE KEY,"
        .  "email VARCHAR(128) NOT NULL DEFAULT '' UNIQUE KEY,"
        .  "urltoken VARCHAR(256) NOT NULL DEFAULT '',"
        //.  "loginFailureCount TINYINT(1) NOT NULL DEFAULT '0',"
        //.  "loginFailureDatetime DATETIME DEFAULT NULL,"
        .  "authflag TINYINT(1) NOT NULL DEFAULT '0'"
        .  ");"; 
        $stmt = $pdo->query($sql);
    }

    //入力されたusernameが既に存在するか確認
    protected function CheckRegisterDataDesplayName($displayname){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test6 where displayName = :displayName';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':displayName', $displayname, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt -> fetchAll();
        return $result;
    }

    //入力されたemailが既に存在するか確認
    protected function CheckRegisterDataEmail($email){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test6 where email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt -> fetchAll();
        return $result;
    }

    //$displaynameか$emailが一致する投稿があったらはじく。
    public function CheckRegisterData($displayname, $password, $email){
        $result_displayname = $this -> CheckRegisterDataDesplayName($displayname);
        $result_email = $this -> CheckRegisterDataEmail($email);
        if ((count($result_displayname)===0)&&(count($result_email)===0)){ //一致する投稿がない
            $this -> RegisterData($displayname, $password, $email);
        }elseif ((count($result_displayname)!==0)&&(count($result_email)!==0)){ //両方一致するたわけ
            echo "usernameもemailアドレスもすでに使用されています。";
        }elseif (count($result_displayname)!==0){ //displaynameに重複がある
            echo "そのusernameは既に使用されています。別のusernameを使用してください。";
        }elseif (count($result_email)!==0){
            echo "そのemailアドレスはすでに使用されています。別のemailアドレスを使用してください。";
        }else{
            echo "error";
        }
    }

    //トークンの生成
    protected function GenerateToken(){
        return hash('sha256',uniqid(rand(),1));
    }

    //username、password、emailの仮登録を行う
    protected function RegisterData($displayname, $password, $email){
        $pdo = $this -> connectServer();
        $token = $this -> GenerateToken();
        $urltoken = "https://tb-210191.tech-base.net/mission6/registration_auth.php"."?urltoken=".$token;
        $sql = $pdo -> prepare("INSERT INTO tbl_user_test6 (displayName, password, email, urltoken) VALUES (:displayName, :password, :email, :urltoken)");  
        $sql -> bindParam(':displayName', $displayname, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> bindParam(':email', $email, PDO::PARAM_STR);
        $sql -> bindParam(':urltoken', $token, PDO::PARAM_STR);
        $sql -> execute();
        echo $displayname."さん、仮登録を受け付けました。<br>";
        echo "メール認証を行ってください。<br>";
        $this -> RegisterSendMail($email,$urltoken);
    }

    //仮登録メール送信
    public function RegisterSendMail($email,$urltoken){
        require("mailsend.php");
        mail_send($email, $urltoken);
    }

    
    
    //database上のテーブルを表示　後で消す
    public function showTable(){
        $pdo = $this -> connectServer();
        $sql ='SHOW TABLES';
	    $result = $pdo -> query($sql);
	    foreach ($result as $row){
		    echo $row[0];
		    echo '<br>';
	    }
    }

    //登録済みのユーザーデータを表示　後で消す
    public function showRegistered(){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test6';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['userId'].' ';
            echo $row['displayName'].' ';
            echo $row['password'].' ';
            echo $row['email'].' ';
            echo "authF:".$row['authflag']." ";
            echo $row['urltoken']."<br>";
        }
    }

    //登録済みのユーザデータ、vardump 後で消す
    public function showRegisteredvardump(){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test6';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            var_dump($row);
        }
    }

    //削除すべきものが存在するとき（＋パスワードが一致するとき）、しないときで条件分岐させる
    public function CheckdeleteRegisteredInfo($delete_number, $delete_password){ 
        $pdo = $this -> connectServer();
        $id = $delete_number;
        $sql_pre = "SELECT * FROM tbl_user_test6 where userId = :id";
        $stmt_pre = $pdo->prepare($sql_pre);
        $stmt_pre->bindParam(':id', $id, PDO::PARAM_INT); //:idに$idを代入する。
        $stmt_pre->execute();
        $result = $stmt_pre -> fetchAll();
        if(!$result){ //削除すべきものが存在しないとき
            echo "削除対象番号が入力されていないか、その投稿番号は存在しません。";
        }else{ //削除すべきものが存在するとき
            if($result[0]['password'] === $delete_password){ //パスワードが正しいとき
                $this -> deleteRegisteredInfo($delete_number); //削除メソッドへ
            }else{
                echo "正しいパスワードを入力してください。";
            }
        }
    }

    //削除実行メソッド    
    protected function deleteRegisteredInfo($delete_number){
        $pdo = $this -> connectServer();
        $id = $delete_number;
        $sql = 'delete from tbl_user_test6 where userId=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = 'ALTER TABLE tbl_user_test6 auto_increment = 1';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "投稿番号".$id."を削除しました。";
    }

    public function checkUrltoken($urltoken){
        $pdo = $this -> connectServer();
        $sql_pre = "SELECT * FROM tbl_user_test6 where urltoken = :urltoken AND authflag = '0'"; //authflagが0の中の物から、urltokenが等しいものを検索
        $stmt_pre = $pdo->prepare($sql_pre);
        $stmt_pre->bindParam(':urltoken', $urltoken, PDO::PARAM_STR);
        $stmt_pre->execute();
        $result = $stmt_pre -> fetchAll();
        if(!$result){
            echo "不正なtokenです。登録をやり直してください。";
        }else{
            $this -> authFlagConverter($urltoken);
        }
    }

    protected function authFlagConverter($urltoken){
        $pdo = $this -> connectServer();
        $sql = "update tbl_user_test6 set authflag='1' where urltoken=:urltoken AND authflag = '0'"; //authflagが0の中の物から、urltokenが等しいものをupdate
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':urltoken', $urltoken, PDO::PARAM_STR);
        $stmt->execute();
        echo "メール認証が完了しました。";
    }
}

class Login extends Register{

    //serverに接続する
    //protected function connectServer(){ 
    //    require("databaseinfo.php"); //databese設定
    //    $pdo = new PDO($dsn, $user, $password_server, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //    return $pdo;
    //}

    //入力されたusernameが存在するとき（＋パスワードが一致するとき）、しないときで条件分岐させる
    public function CheckLoginInfo($displayname, $password){ 
        $pdo = $this -> connectServer();
        $sql_pre = "SELECT * FROM tbl_user_test6 where displayName = :displayname";
        $stmt_pre = $pdo->prepare($sql_pre);
        $stmt_pre->bindParam(':displayname', $displayname, PDO::PARAM_STR);
        $stmt_pre->execute();
        $result = $stmt_pre -> fetchAll();
        if(!$result){ //displaynameで検索した結果が存在しないとき
            echo "そのIDは存在しません。";
        }else{ //存在するとき
            if($result[0]['password'] === $password){ //パスワードが正しいとき
                $_result = $result[0]; //当該usernameのデータを格納
                $this -> LoginInfo($_result); //ログインメソッドへ
            }else{
                echo "正しいパスワードを入力してください。";
            }
        }
    }
    
    //ログインメソッド
    protected function LoginInfo($_result){
        if ($_result['authflag'] === '0'){
            echo "メール認証が終わっていません。メールを再送します。";
            $this -> reSendAuthmail($_result['displayName']);
        }elseif ($_result['authflag'] === '1'){
            header("Location: toppage.php");
        }

    }

    //メール再送メソッド(引数usernameに対してトークンをアップデート)
    protected function reSendAuthmail($username){
        $newtoken = $this -> GenerateToken();
        $urltoken = "https://tb-210191.tech-base.net/mission6/registration_auth.php"."?urltoken=".$newtoken; //新しいトークンを生成
        //データベース上のトークンを更新
        $pdo = $this -> connectServer();
        $sql = "update tbl_user_test6 set urltoken=:urltoken where displayName=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':urltoken', $newtoken, PDO::PARAM_STR);
        $stmt->execute();
        //emailの取得
        $sql_pre = "SELECT * FROM tbl_user_test6 where displayName = :username";
        $stmt_pre = $pdo->prepare($sql_pre);
        $stmt_pre->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt_pre->execute();
        $result = $stmt_pre -> fetchAll();
        $email = $result[0]["email"];
        $this -> RegisterSendMail($email,$urltoken);
    }

}
?>
