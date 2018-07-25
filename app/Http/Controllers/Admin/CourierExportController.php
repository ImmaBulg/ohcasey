<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use ZipArchive;
use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CourierExportController extends Controller
{
	const GEOCODE_URL = "https://geocode-maps.yandex.ru/1.x/?format=json&results=1&geocode=";
	const UNDERGROUNDS_URL = "https://geocode-maps.yandex.ru/1.x/?kind=metro&format=json&results=1&geocode=";
	
    public function execute(Request $request)
	{
		$validator = Validator::make($request->except('_token'), [
			'ids' => 'required',
		]);
		if($validator->fails()){
			return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
		}
		$ids = explode("\r\n", $request->input('ids'));
		try{
			$orders = Order::find($ids);
		} catch(\Illuminate\Database\QueryException $e){
			$validator->getMessageBag()->add('ids', 'Неправильный формат id');
		}
		if(!empty($orders) && count($orders) == 0){
			$validator->getMessageBag()->add('ids', 'По указанным id не найдено ни одного заказа');
		}
		if(count($validator->errors()) > 0){
			return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
		}
		
		$spreadsheetPvz = new Spreadsheet();
		$sheetPvz = $spreadsheetPvz->getActiveSheet();
		$sheetPvz = $this->getTableHead($sheetPvz);
		$sdekPickup = [];
		$sdekCouriers = [];
		$sdekErrors = [];
		$badIds = [];
		$files = []; //Тут будут пути и названия экселек
		$rowNumber = 2;
		
		foreach ($ids as $id) {
			$order = Order::find($id);
			if (!$order) {
				$badIds[] = $id;
				continue;
			}
			$deliveryCdek = $order->deliveryCdek;
			if(!$deliveryCdek && $order->delivery_name != 'courier_moscow'){
				$sdekErrors[] = $order;
				continue;
			}elseif(!empty($order->deliveryCdek->cdek_pvz)){
				$sdekPickup[] = $order;
				continue;
			}
			$sdekCouriers[] = $order;
		}
		
		if(count($badIds) > 0 || count($sdekErrors) > 0){
			$spreadsheetError = new Spreadsheet();
			$sheetError = $spreadsheetError->getActiveSheet();
			$sheetError = $this->getTableHead($sheetError);
			$rowNumber = 2;
			foreach($sdekErrors as $order){
				$sheetError = $this->setTableRow($sheetError, $order, $rowNumber);
				$rowNumber++;
			}
			foreach($badIds as $badId){
				$sheetError->setCellValue("A$rowNumber", "Заказ № $badId не существует");
				$rowNumber++;
			}
			$writterError = new Xlsx($spreadsheetError);
			$fileNameError = date('Y-m-d-H-i-s', time()) . '_errors.xlsx';
			$fileLocationError = public_path() . '/tmp/import/' . $fileNameError;
			$writterError->save($fileLocationError);
			$files[] = [
				'path' => $fileLocationError,
				'name' => 'ошибка.xlsx',
			];
		}
		
		if(count($badIds) == 0 && count($sdekErrors) == 0 && count($sdekPickup) > 0){
			$spreadsheetPvz = new Spreadsheet();
			
			$sheetPvz = $spreadsheetPvz->getActiveSheet();
			$sheetPvz = $this->getTableHead($sheetPvz);
			$rowNumber = 2;
			foreach ($sdekPickup as $order) {
				$sheetPvz = $this->setTableRow($sheetPvz, $order, $rowNumber, $order->deliveryCdek);
				$rowNumber++;
			}
			$writterPvz = new Xlsx($spreadsheetPvz);
			$fileNamePvz = date('Y-m-d-H-i-s', time()) . '_pvz.xlsx';
			$fileLocationPvz = public_path() . '/tmp/import/' . $fileNamePvz;
			$writterPvz->save($fileLocationPvz);
			$files[] = [
				'path' => $fileLocationPvz,
				'name' => 'самовывоз.xlsx',
			];
		}
		
		//Заказы без пвз
		if(count($badIds) == 0 && count($sdekErrors) == 0 && count($sdekCouriers) > 0){
			$spreadsheetCourier = new Spreadsheet();
			$sheetCourier = $spreadsheetCourier->getActiveSheet();
			$sheetCourier = $this->getTableHead($sheetCourier);
			$rowNumber = 2;
			foreach($sdekCouriers as $order){
				$sheetCourier = $this->setTableRow($sheetCourier, $order, $rowNumber, $order->deliveryCdek);
				$rowNumber++;
			}
			$writterCorier = new Xlsx($spreadsheetCourier);
			$fileNameCourier = date('Y-m-d-H-i-s', time()) . '_courier.xlsx';
			$fileLocationCourier = public_path() . '/tmp/import/' . $fileNameCourier;
			$writterCorier->save($fileLocationCourier);
			$files[] = [
				'path' => $fileLocationCourier,
				'name' => 'курьер.xlsx',
			];
		}
		
		$zip = new ZipArchive();
		$archiveName =  date('Y-m-d-H-i-s', time()) . '_cdek.zip';
		$archiveLocation = public_path() . '/tmp/import/' . $archiveName;
		if($zip->open($archiveLocation, ZipArchive::CREATE) !== true){
			foreach($files as $file){
				unlink($file['path']);
			}
			die('Cant create archive');
		}
		foreach($files as $file){
			$zip->addFile($file['path'], $file['name']);
		}
		$zip->close();
		foreach($files as $file){
			unlink($file['path']);
		}
		
		return response()->download($archiveLocation, $archiveName, [
			'Content-Type: application/octet-stream',
		]);
		
	}
	
	protected function getTableHead ($sheet) 
	{
		$sheet->setCellValue('A1', 'Номер заказа');
		$sheet->setCellValue('B1', 'Метро');
		$sheet->setCellValue('C1', 'Адрес получателя');
		$sheet->setCellValue('D1', 'ФИО получателя');
		$sheet->setCellValue('E1', 'Телефон получателя');
		$sheet->setCellValue('F1', 'Комментарий');
		$sheet->setCellValue('G1', 'Кол-во чехлов');
		$sheet->setCellValue('H1', 'Сумма заказа');
		
		return $sheet;
	}
	
	private function setTableRow($sheet, $order, $rowNumber, $deliveryCdek = null)
	{
		$sheet->setCellValue("A$rowNumber", $order->order_id);
		if(($deliveryCdek && !$deliveryCdek->cdek_pvz) || $order->delivery_name == 'courier_moscow'){
			$geocodes = @file_get_contents(static::GEOCODE_URL . urlencode( $order->city->city_name . ' ' . $order->delivery_address));
			$geocodes = json_decode($geocodes, true);
			//dd($geocodes);
			$metroName = 'Не удалось определить станцию метро';
			if(isset($geocodes['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])){
				$undergrounds = @file_get_contents(static::UNDERGROUNDS_URL . str_replace(' ', ',', $geocodes['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']));
				$undergrounds = json_decode($undergrounds, true);
				if(isset($undergrounds['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'])){
					$metroName = $undergrounds['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'] . '. ' . $undergrounds['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['description'];
				}
			}
			$sheet->setCellValue("B$rowNumber", $metroName);
		}else{
			$sheet->setCellValue("B$rowNumber", 'Не выбрана доставка курьером');
		}
		$sheet->setCellValue("C$rowNumber", $order->delivery_address);
		$sheet->setCellValue("D$rowNumber", $order->client_name);
		$sheet->setCellValue("E$rowNumber", $order->client_phone);
		$sheet->setCellValue("F$rowNumber", $order->order_comment);
		$caseCount = 0;
		//Считаем кастомные чехлы
		if (!empty($order->cart->cartSetCase)) {
			foreach ($order->cart->cartSetCase as $case) {
				$caseCount += $case->item_count;
			}
		}
		//Считаем дефолтные чехлы
		if (!empty($order->cart->cartSetProducts)) {
			foreach ($order->cart->cartSetProducts as $case) {
				$caseCount += $case->item_count;
			}
		}
		$sheet->setCellValue("G$rowNumber", $caseCount);
		$sheet->setCellValue("H$rowNumber", $order->order_amount);
		
		return $sheet;
	}
}
