<?php

namespace App\Ohcasey\Delivery\Cdek;
use Faker\Provider\zh_TW\DateTime;

/**
 * Class Cdek
 * @package App\Ohcasey\Delivery\Cdek
 */
class Cdek
{
    const COURIER = 137;
    const PICKPOINT = 136;

    /**
     * Get PVZ
     * @param $idCity
     * @param null $code
     * @return array
     * @throws \Exception
     */
    public function pvz($idCity, $code = null)
    {
		/* // fix
		$result[] = [
			'code' => "",
			'name' => "",
			'city_code' => "",
			'city' => "",
			'work_time' => "",
			'address' => "",
			'phone' => "",
			'note' => "",
			'coordx' => "",
			'coordy' => "",
		];
		return $result;  */
		
        $result = [];
        $ch = curl_init();
        if ($ch) {
            try {
                // CDEK request
                // curl_setopt($ch, CURLOPT_URL, "http://gw.edostavka.ru:11443/pvzlist.php?cityid=" . $idCity);
                // curl_setopt($ch, CURLOPT_URL, "http://integration.cdek.ru/pvzlist.php?cityid=" . $idCity);
                curl_setopt($ch, CURLOPT_URL, "https://integration.cdek.ru/pvzlist.php?cityid=" . $idCity);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $xml = curl_exec($ch);
                curl_close($ch);
				

                // Get all PVZ
                $pvzs = new \SimpleXMLElement($xml);
                foreach ($pvzs->Pvz as $pvz) {
                    if ($code == null || (string)$pvz['Code'] === $code) {
                        $result[] = [
                            'code' => (string)$pvz['Code'],
                            'name' => (string)$pvz['Name'][0],
                            'city_code' => (string)$pvz['CityCode'][0],
                            'city' => (string)$pvz['City'][0],
                            'work_time' => (string)$pvz['WorkTime'][0],
                            'address' => (string)$pvz['Address'][0],
                            'phone' => (string)$pvz['Phone'][0],
                            'note' => (string)$pvz['Note'][0],
                            'coordx' => (float)$pvz['coordX'][0],
                            'coordy' => (float)$pvz['coordY'][0],
                        ];
                    }
                }
                return $result;
            } catch (\Exception $e) {
                return [];
            }
        } else {
            throw new \Exception('CURL not found');
        }
    }

    /**
     * Cost
     * @param $idCity
     * @return array|int
     */
    function cost($idCity)
    {
		// return []; // fix
		
        $counter = 0;
        $dates = 1;
        while ($counter < 3) {
            $date = date("Y-m-d", time() + 86400 * $dates);
            if (BankDay::isWorkDay($date)) {
                $counter++;
            }
            $dates++;
        }

        date_default_timezone_set("UTC");

        // Courier and pick point
        $result = [];
        foreach ([self::COURIER, self::PICKPOINT] as $tariff) {
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
        }

        date_default_timezone_set(config('app.timezone'));

        return $result;
    }
}
