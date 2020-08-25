<?php
    //接続処理
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード';
    $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
    
    //テーブルの作成
	$sql = "CREATE TABLE IF NOT EXISTS chat"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "str TEXT,"
	. "pass TEXT,"
	. "date TEXT"
	.");";
	$stmt = $pdo->query($sql);

    //編集フォーム
    if(isset($_POST["edit"])){
        if($_POST["ednum"]==""){
            $alart="編集するスレッド番号を入力してください<br>";
        }elseif($_POST["edpass"]==""){
            $alart= "パスワードを入力してください<br>";
        }else{
        $id=$_POST["ednum"];
        $edpass=$_POST["edpass"];
        //パスワードの取得
        $sql = 'SELECT * FROM chat WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach($results as $row){}
        //編集する値の取得
        if($row['pass']==$edpass){
            $num_e=$row['id'];                        
            $name_e=$row['name'];
            $str_e=$row['str'];
            $pass_e=$row['pass'];
        }else{
            $alart="パスワードが違います<br>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
    掲示板<br>
    <form action="" method="post">
        名前<input type="text" name="name" value=<?php echo $name_e; ?>><br>
        コメント<input type="text" name="str" value=<?php echo $str_e; ?>><br>
        パスワード<input type="text" name="pass" value=<?php echo $pass_e;?>><br>
        <input type="submit" name="submit" value="送信"><br>
<?php
        
    //送信ボタン後の処理
    if(isset($_POST["submit"])){
        //記入の処理
        if($_POST["name"]==""){
            echo("名前を入力してください<br>");
        }elseif($_POST["str"]==""){
            echo("コメントを入力してください<br>");
        }elseif($_POST["pass"]==""){
            echo("パスワードを入力してください<br>");
        }elseif ($_POST["bangou"]==""){
                //データベースへの入力
                	$sql =$pdo -> prepare("INSERT INTO chat
                	        (name, str, pass, date)
                	        VALUES (:name, :str, :pass, :date)");
	                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	                $sql -> bindParam(':str', $str, PDO::PARAM_STR);
	                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	                    //フォームの値を代入
                    $name=$_POST["name"];
                    $str=$_POST["str"];
                    $pass=$_POST["pass"];
                    $date=date("Y/m/d h:i:s");
	                $sql -> execute();
        }elseif($_POST["bangou"]!=""){
                //データベースへの編集
                $id=$_POST["bangou"];
	                $name=$_POST["name"];
                    $str=$_POST["str"];
                    $pass=$_POST["pass"];
                    $date=date("Y/m/d h:i:s");
                $sql = 'UPDATE chat SET
                       name=:name,str=:str,pass=:pass,date=:date WHERE id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':str', $str, PDO::PARAM_STR);
	            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
        }
    }
?>
        削除番号指定用フォーム<input type="number" name="dlnum"><br>
        パスワード<input type="text" name="dlpass"><br>
        <input type="submit" name="delete" value="削除"><br>
<?php
if(isset($_POST["delete"])){
            if($_POST["dlnum"]==""){
                echo("削除するスレッド番号を入力してください<br>");
            }elseif($_POST["dlpass"]==""){
                echo("パスワードを入力してください<br>");
            }else{
            $id=$_POST["dlnum"];
            $dlpass=$_POST["dlpass"];
            
            //パスワードの取得
            $sql = 'SELECT * FROM chat WHERE id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row){}
            if($row['pass']==$dlpass){
                //削除
	            $sql = 'delete from chat where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();    
            }else{
                echo "パスワードが違います<br>";
            }
        }
    }
?>
        編集番号指定用フォーム<input type="number" name="ednum"><br>
        パスワード<input type="text" name="edpass"><br>
        <input type="submit" name="edit" value="編集"><br>
        <?php echo $alart ?>
        <input type="hidden" name="bangou" value=<?php echo $num_e; ?>><br>
    </form>
</body>
</html>
<?php
    //掲示板を表示
	$sql = 'SELECT * FROM chat';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['str'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
?>