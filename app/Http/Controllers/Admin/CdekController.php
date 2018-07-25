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

class CdekController extends Controller
{
    public function showForm()
	{
		return view('admin.cdekForm');
	}
	
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
		/*Формируем шапку таблицы*/
		$sheetPvz = $this->getTableHead($sheetPvz);
		/*Конец шапки*/
		$sdekErrors = [];
		$sdekCouriers = [];
		$badIds = [];
		$files = []; //Тут будут пути и названия экселек
		$rowNumber = 2;
		//foreach($orders as $key => $order){
		foreach($ids as $id){
			$order = Order::find($id);
			if(!$order) {
				$badIds[] = $id;
				continue;
			}
			$deliveryCdek = $order->deliveryCdek;
			if(!$deliveryCdek){
				$sdekErrors[] = $order;
				continue;
			}elseif(empty($order->deliveryCdek->cdek_pvz)){
				$sdekCouriers[] = $order;
				continue;
			}
			$sheetPvz = $this->setTableRow($sheetPvz, $order, $rowNumber, $deliveryCdek);
			$rowNumber++;
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
		//Заказы с ошибкой
		if(count($sdekErrors) > 0 || count($badIds) > 0){
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
		
		if(count($badIds) == 0 && count($sdekErrors) == 0 && count($sdekCouriers) < count($orders)){
			$writterPvz = new Xlsx($spreadsheetPvz);
			$fileNamePvz = date('Y-m-d-H-i-s', time()) . '_pvz.xlsx';
			$fileLocationPvz = public_path() . '/tmp/import/' . $fileNamePvz;
			$writterPvz->save($fileLocationPvz);
			$files[] = [
				'path' => $fileLocationPvz,
				'name' => 'самовывоз.xlsx',
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
			/*'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',*/
			'Content-Type: application/octet-stream',
		]);
	}
	
	private function setTableRow($sheet, $order, $rowNumber, $deliveryCdek = null)
	{
		$sheet->setCellValue("A$rowNumber", $order->order_id);
		$sheet->setCellValue("B$rowNumber", $order->city ? $order->city->city_name : '');
		$sheet->setCellValue("C$rowNumber", (!$deliveryCdek ? 'Не выбрана доставка сдэк ' : '') . $order->client_name);
		$sheet->setCellValue("D$rowNumber", $order->client_name);
		$sheet->setCellValue("E$rowNumber", $order->delivery_address);
		$sheet->setCellValue("F$rowNumber", $deliveryCdek ? $deliveryCdek->cdek_pvz : '');
		$sheet->setCellValue("G$rowNumber", $order->client_phone);
		$sheet->setCellValue("H$rowNumber", '0');
		$sheet->setCellValue("I$rowNumber", '0');
		$sheet->setCellValue("J$rowNumber", '0,00');
		$sheet->setCellValue("K$rowNumber", 'Ohcasey');
		$sheet->setCellValue("L$rowNumber", $order->order_comment); 
		$sheet->setCellValue("M$rowNumber", '1');
		$sheet->setCellValue("N$rowNumber", '0,2');
		$sheet->setCellValue("O$rowNumber", '20');
		$sheet->setCellValue("P$rowNumber", '13');
		$sheet->setCellValue("Q$rowNumber", '5');
		$sheet->setCellValue("R$rowNumber", 'Чехол');
		$sheet->setCellValue("S$rowNumber", '0');
		$sheet->setCellValue("U$rowNumber", '1'); 
		$sheet->setCellValue("V$rowNumber", '0');
		$sheet->setCellValue("W$rowNumber", '0,2');
		$sheet->setCellValue("X$rowNumber", '1');
		$sheet->setCellValue("Y$rowNumber", '18');
		$sheet->setCellValue("Z$rowNumber", '0,00');
		
		return $sheet;
	}
	
	private function getTableHead($sheet)
	{
		$sheet->setCellValue('A1', 'Номер отправления');
		$sheet->setCellValue('B1', 'Город получателя');
		$sheet->setCellValue('C1', 'Получатель');
		$sheet->setCellValue('D1', 'ФИО получателя');
		$sheet->setCellValue('E1', 'Адрес получателя');
		$sheet->setCellValue('F1', 'Код ПВЗ');
		$sheet->setCellValue('G1', 'Телефон получателя');
		$sheet->setCellValue('H1', 'Доп сбор за доставку с получателя в т.ч. НДС');
		$sheet->setCellValue('I1', 'Ставка НДС с доп.сбора за доставку');
		$sheet->setCellValue('J1', 'Сумма НДС с доп.сбора за доставку');
		$sheet->setCellValue('K1', 'Истинный продавец');
		$sheet->setCellValue('L1', 'Комментарий');
		$sheet->setCellValue('M1', 'Порядковый номер места');
		$sheet->setCellValue('N1', 'Вес места, кг');
		$sheet->setCellValue('O1', 'Длина места, см');
		$sheet->setCellValue('P1', 'Ширина места, см');
		$sheet->setCellValue('Q1', 'Высота места, см');
		$sheet->setCellValue('R1', 'Описание места');
		$sheet->setCellValue('S1', 'Код товара/артикул');
		$sheet->setCellValue('T1', 'Наименование товара');
		$sheet->setCellValue('U1', 'Стоимость единицы товара');
		$sheet->setCellValue('V1', 'Оплата с получателя за ед товара в т.ч. НДС');
		$sheet->setCellValue('W1', 'Вес товара, кг');
		$sheet->setCellValue('X1', 'Количество, шт');
		$sheet->setCellValue('Y1', 'Ставка НДС, %');
		$sheet->setCellValue('Z1', 'Сумма НДС за ед.');
		
		return $sheet;
	}
}