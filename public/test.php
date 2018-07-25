<?php
	

	
	$result = [];
	
	$tariff = 136;
	$date = date("Y-m-d", time() + 86400 * 1);
	$idCity = 220;
	
	$json = json_encode([
		"version" => "1.0",
		"dateExecute" => $date,
		"authLogin" => "70706c7a6fdf9cdb2b4208348cbee331",
		"secure" => md5($date . '&' . "47d9f60e3b8827fbe28b1babc54ecdce"),
		"senderCityId" => "44",
		"receiverCityId" => $idCity,
		"tariffId" => $tariff,
		"goods" => [
			[
				"weight" => "0.1",
				"length" => "20",
				"width" => "10",
				"height" => "1"
			]
		]
	]);

	if ($ch = curl_init()) {
		try {
			curl_setopt($ch, CURLOPT_URL, "http://api.edostavka.ru/calculator/calculate_price_by_json.php");
			// curl_setopt($ch, CURLOPT_URL, "http://api.cdek.ru/calculator/calculate_price_by_json.php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			$output = curl_exec($ch);
			$outputArray = json_decode($output, TRUE);
			curl_close($ch);
			
			var_dump($output);exit;
			var_dump($outputArray);exit;

			if (isset($outputArray["error"])) {
				$result[$tariff] = null;
			} else {
				$from = \DateTime::createFromFormat('Y-m-d', $outputArray["result"]["deliveryDateMax"]);
				$to = clone $from;
				$to->add(new \DateInterval('P14D'));
				$result[$tariff] = [
					"price" => (int)$outputArray["result"]["price"],
					"dateFrom" => $from->format('Y-m-d'),
					"dateTo" => $to->format('Y-m-d'),
					"deliveryDateMin" => $outputArray["result"]["deliveryDateMin"],
					"deliveryDateMax" => $outputArray["result"]["deliveryDateMax"],
					"idreceiver" => (int)$idCity
				];
			}
		} catch (\Exception $e) {
			$result[$tariff] = null;
		}
	}
	
	
	