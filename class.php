<?php
class mysql {
    //serverに接続する
    private function connectServer(){ 
        require("databaseinfo.php"); //databese設定
        $pdo = new PDO($dsn, $user, $password_server, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        return $pdo;
    }

    //データベース内にテーブルを作成する
    public function createTable(){
        $this -> connectServer();
        $sql = "CREATE TABLE IF NOT EXISTS tbl_user_test4" 
        ." ("
        .  "userId INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
        .  "password CHAR(60) NOT NULL DEFAULT '',"
        .  "displayName VARCHAR(64) NOT NULL DEFAULT '' UNIQUE KEY,"
        .  "email VARCHAR(128) NOT NULL DEFAULT '' UNIQUE KEY"
        //.  "token CHAR(60) NOT NULL DEFAULT '',"
        //.  "loginFailureCount TINYINT(1) NOT NULL DEFAULT '0',"
        //.  "loginFailureDatetime DATETIME DEFAULT NULL,"
        //.  "deleteFlag TINYINT(1) NOT NULL DEFAULT '0'"
        .  ");"; 
        $stmt = $pdo->query($sql);
    }

    private function CheckRegisterDataDesplayName($displayname){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test4 where displayName = :displayName';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':displayName', $displayname, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt -> fetchAll();
        return $result;
    }

    private function CheckRegisterDataEmail($email){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test4 where email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt -> fetchAll();
        return $result;
    }

    public function CheckRegisterData($displayname, $password, $email){
        //sqlを使って$displaynameか$emailが一致する投稿があったらはじく。
        $result_displayname = $this -> CheckRegisterDataDesplayName($displayname);
        $result_email = $this -> CheckRegisterDataEmail($email);
        if ((count($result_displayname)===0)&&(count($result_email)===0)){ //一致する投稿がない
            $this -> RegisterData($displayname, $password, $email);
        }elseif ((count($result_displayname)!==0)&&(count($result_email)!==0)){ //両方一致するたわけ
            echo "useridもemailアドレスもすでに使用されています。";
        }elseif (count($result_displayname)!==0){ //displaynameに重複がある
            echo "そのuseridは既に使用されています。別のidを使用してください。";
        }elseif (count($result_email)!==0){
            echo "そのemailアドレスはすでに使用されています。別のemailアドレスを使用してください。";
        }else{
            echo "error";
        }
    }

    private function RegisterData($displayname, $password, $email){
        $pdo = $this -> connectServer();
        $sql = $pdo -> prepare("INSERT INTO tbl_user_test4 (displayName, password, email) VALUES (:displayName, :password, :email)");  
        $sql -> bindParam(':displayName', $displayname, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> bindParam(':email', $email, PDO::PARAM_STR);
        $sql -> execute();
        echo $displayname."さん、仮登録を受け付けました。<br>";
        echo "メール認証を行ってください。(メール認証機能は未実装。)";
    }
    
    public function showTable(){
        $pdo = $this -> connectServer();
        $sql ='SHOW TABLES';
	    $result = $pdo -> query($sql);
	    foreach ($result as $row){
		    echo $row[0];
		    echo '<br>';
	    }
    }

    public function showRegistered(){
        $pdo = $this -> connectServer();
        $sql = 'SELECT * FROM tbl_user_test4';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['userId'].' ';
            echo $row['displayName'].' ';
            echo $row['password'].' ';
            echo $row['email'].'<br>';
        }
    }

    public function CheckdeleteRegisteredInfo($delete_number, $delete_password){ //削除すべきものが存在するとき（＋パスワードが一致するとき）、しないときで条件分岐させる
        $pdo = $this -> connectServer();
        $id = $delete_number;
        $sql_pre = "SELECT * FROM tbl_user_test4 where userId = :id";
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
    private function deleteRegisteredInfo($delete_number){
        $pdo = $this -> connectServer();
        $id = $delete_number;
        $sql = 'delete from tbl_user_test4 where userId=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = 'ALTER TABLE tbl_user_test4 auto_increment = 1';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "投稿番号".$id."を削除しました。";
    }
}
?>
