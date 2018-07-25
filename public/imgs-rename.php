<?php	
	
	// $arrayText = preg_split('//u', "Unicornфывы", -1, PREG_SPLIT_NO_EMPTY);
	// var_dump($arrayText);
	// exit;
	
	$array_type = array(
		"", // 0
		"чехол ", // 1
		"чехол ", // 2
		"Временная ", // 3
		"Защитное стелко ", // 4
		"Кабель для делефона ", // 5
		"Зарядки ", // 6
		"Свитшоты " // 7
	);
	
	$dbh = new PDO("pgsql:dbname=ohcasey;host=localhost", "ohcasey", "ohcasey");
	// $stmt = $dbh->query('SELECT * FROM products WHERE option_group_id = 1 OR option_group_id = 2');
	$stmt = $dbh->query('SELECT * FROM products ');
	// while ($row = $stmt->fetch()){
	$prods = $stmt->fetchAll();
	$num_img = 0;
	foreach($prods as $row){		
		// var_dump($row);
		// $c++;
		// continue;
		$option_group_id = (int)$row["option_group_id"];
		$dop_name = $array_type[$option_group_id];
		// var_dump($dop_name);//exit;
		
		$id = (int)$row['id'];
		$name = $row['name'];		
		$photos = $dbh->query('SELECT * FROM photos WHERE deleted_at IS NULL AND photoable_id ='.$id);
		// $num_img = 0;
		// while ($img = $photos->fetch()){
		$images = $photos->fetchAll();
		foreach($images as $img){	
			// var_dump($img["name"]);continue;
			$id_img = (int)$img["id"];
			// $exp = explode(".", $img["name"]);
			// $format = $exp[1];
			$num_img++;
			// $new_name = getTranslitForUrl("чехол ".$name)."_".$num_img.".".$format;
			
			// $new_name = getTranslitForUrl("чехол ".$name)."_".$num_img;
			$new_name = getTranslitForUrl($dop_name.$name)."-0".$num_img;
			echo "old: " . $img["name"] . " new: " .$new_name. "<br>";	
			if(file_exists("images/product/".$img["name"])) {
				$new_name .= ".jpg";
				$sql = "UPDATE \"public\".\"photos\" SET \"name\"='".$new_name."' WHERE  \"id\"=".$id_img;
				var_dump($sql);
				try {
					$img_up = $dbh->query($sql);
					$img_up->execute();
				} catch (Exception $e) {
					echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
				}
				
				setImageCompressionQuality("images/product/".$img["name"], "images/product/".$new_name);
				echo "new file: http://ohcasey.ru/images/product/{$new_name} <br>";
			}else{
				echo "file not exists <br>";
			}
		}
		echo "<hr>";
	}
	
	echo $c;

	
	function getTranslitForUrl($text){
		// массив для транслита
		$arrayConvert = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',   'г' => 'g',   
			'д' => 'd',   'е' => 'e',   'ё' => 'e',   'ж' => 'zh',
			'з' => 'z',   'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',   'о' => 'o',   
			'п' => 'p',   'р' => 'r',   'с' => 's',   'т' => 't',   
			'у' => 'u',   'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch', 'ы' => 'y',   
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',  ' ' => '-'
		);
			 
		// массив для хранения валидных символов
		$arraySuccessLiteral = array();
		foreach($arrayConvert as $key => $value){
			$arraySuccessLiteral[] = $key;
		}
		// добавляем числа в массив с валидными символами
		$arrayInt = array(
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
			'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p',
			'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z',
			'x', 'c', 'v', 'b', 'n', 'm',
		);
		$arraySuccessLiteral = array_merge($arraySuccessLiteral, $arrayInt);
		 
		// разбиваем строку по символу и упаковываем в массив 
		$arrayText = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
		// выбираем из всего массива только валидные символы, остальные отбрасываем
		foreach($arrayText as $lit){
			$literal = mb_strtolower($lit, 'utf-8'); // переводим символы в нижний регистр
			// если символ валидный, то берем его
			if(in_array($literal, $arraySuccessLiteral)){
				if( in_array($literal, array_keys($arrayConvert)) ){
					$newText .= $arrayConvert[$literal];
				}else{
					$newText .= $literal;
				}
				
			}
		} 
		return $newText;
		
		/* // удаляем дублирующиеся пробелы
		$validText =  preg_replace('/ {2,}/',' ', $newText); 
	 
		// меняем кириллицу на соответствующие латинские символы
		$result = strtr($validText, $arrayConvert);
		return $result; */
	}
	
	
	function setImageCompressionQuality($imagePath, $newPath, $quality = 100) {
		$imagick = new \Imagick(realpath($imagePath));
		$imagick->setImageCompressionQuality($quality);
		$imagick->writeImage($newPath);
	}