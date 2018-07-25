<?php
	$login = "admin";
	$password = "123";
	if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_PW']==$password) && (strtolower($_SERVER['PHP_AUTH_USER'])==$login)){
		// echo 'Success auth!';
	} else {
		header('WWW-Authenticate: Basic realm="Backend"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Authenticate required!';
	}
	
	if($_POST["get"]){
		$dbh = new PDO("pgsql:dbname=ohcasey;host=127.0.0.1", "ohcasey", "ohcasey"); 
		$dbh->query("SET NAMES UTF8");
		// $dbh->query("SET NAMES CP1251");
		$date1 = $_POST["date1"];
		$date2 = $_POST["date2"];
		
		$stmt = $dbh->prepare('
			select "order_status".status_name, "order".*  from  "order"
				join "order_status" ON "order_status".status_id = "order".order_status_id
				where "order"."order_ts" >= \''.$date1.'\' and "order"."order_ts" < \''.$date2.'\' AND "order"."order_status_id" <> 7 AND "order"."order_status_id" <> 5
		');
		
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$str = '';
		// шапка
		foreach($rows as $row){
			foreach($row as $column => $val){			
				$str .= $column . ";";
			}
			$str .= "\r\n";
			break;
		}
		
		// данные
		foreach($rows as $row){
			foreach($row as $column => $val){			
				$str .= str_replace(array("\n", "\r", ";"), " ", $val) . ";";
			}
			$str .= "\r\n";
		}
		$str = iconv("UTF-8", "WINDOWS-1251//IGNORE",  $str);
		$r = file_put_contents('output.csv', $str);
		// var_dump($r);
		
		header('Content-Disposition: attachment; filename="output.csv"');
		header('Pragma: no-cache');
		
		readfile('output.csv');
	}else{
?>

<form action="#" method="post">
	<label><label><br>
	<input type="text" name="date1" value="<?=date("Y-m-d");?>"><br><br>
	<input type="text" name="date2" value="<?=date("Y-m-d", strtotime("+1 day"));?>"><br><br>
	<input type="submit" value="get csv" name="get"><br>
</form>
<?php } ?>