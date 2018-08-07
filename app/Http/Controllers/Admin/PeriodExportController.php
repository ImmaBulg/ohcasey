<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23.07.2018
 * Time: 10:50
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Validator;
use ZipArchive;
use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeriodExportController extends Controller
{
    const GEOCODE_URL = "https://geocode-maps.yandex.ru/1.x/?format=json&results=1&geocode=";
    const UNDERGROUNDS_URL = "https://geocode-maps.yandex.ru/1.x/?kind=metro&format=json&results=1&geocode=";

    public function execute(Request $request)
    {
        $validator = Validator::make($request->except('_token'), [
            'startDate' => 'required',
        ], [
            'startDate.required' => 'Заполните поле даты',
        ]);
        if($validator->fails()){
            return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
        }

        $startDate = Carbon::createFromFormat('d-m-Y', $request['startDate'])->modify('midnight');
        $endDate = $startDate->modify('23:59');
        $endDateStr = $endDate->format('Y-m-d 23:59');


        if ($endDate < $startDate)
            $validator->getMessageBag()->add('endDate', 'Дата окончания периода указана раньше даты начала');
        if(count($validator->errors()) > 0){
            return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
        }

        $orders = Order::whereBetween('delivery_time_from', [$startDate->format('Y-m-d 00:00'), $endDateStr])->orderBy('delivery_time_from', 'asc')->get();

        if(!empty($orders) && count($orders) == 0){
            $validator->getMessageBag()->add('startDate', 'По указанным датам не найдено ни одного заказа');
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

        foreach ($orders as $order) {
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
        $sdekErrors = [];
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

    public function executeOnlyCdek(Request $request) {
        $validator = Validator::make($request->except('_token'), [
            'startDate' => 'required',
        ], [
            'startDate.required' => 'Заполните поле даты',
        ]);
        if($validator->fails()){
            return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
        }

        $startDate = Carbon::createFromFormat('d-m-Y', $request['startDate'])->modify('midnight');
        $endDate = $startDate->modify('23:59');
        $endDateStr = $endDate->format('Y-m-d 23:59');


        if ($endDate < $startDate)
            $validator->getMessageBag()->add('endDate', 'Дата окончания периода указана раньше даты начала');
        if(count($validator->errors()) > 0){
            return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
        }

        $orders = Order::whereBetween('delivery_time_from', [$startDate->format('Y-m-d 00:00'), $endDateStr])->orderBy('delivery_time_from', 'asc')->get();

        if(!empty($orders) && count($orders) == 0){
            $validator->getMessageBag()->add('startDate', 'По указанным датам не найдено ни одного заказа');
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

        foreach ($orders as $order) {
            $deliveryCdek = $order->deliveryCdek;
            if ($deliveryCdek && ($order->delivery_name === 'courier' || $order->delivery_name === 'pickpoint')) {
                if(!empty($order->deliveryCdek->cdek_pvz)){
                    $sdekPickup[] = $order;
                    continue;
                }
                $sdekCouriers[] = $order;
            }
        }
        if (count($sdekCouriers) < 1 && count($sdekPickup) < 1) {
            $validator->getMessageBag()->add('startDate', 'По указанным датам не найдено ни одного заказа');
            return redirect()->route('admin.cdekForm')->withErrors($validator)->withInput();
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
        $sdekErrors = [];
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

    public function getTableHead($sheet)
    {
        $sheet->setCellValue('A1', 'Номер заказа');
        $sheet->setCellValue('B1', 'Дата доставки');
        $sheet->setCellValue('C1', 'Время доставки (с)');
        $sheet->setCellValue('D1', 'Время доставки (по)');
        $sheet->setCellValue('E1', 'ID товаров в заказке');
        $sheet->setCellValue('F1', 'Метро');
        $sheet->setCellValue('G1', 'Адрес получателя');
        $sheet->setCellValue('H1', 'ФИО получателя');
        $sheet->setCellValue('I1', 'Телефон получателя');
        $sheet->setCellValue('J1', 'Комментарий');
        $sheet->setCellValue('K1', 'Кол-во чехлов');
        $sheet->setCellValue('L1', 'Сумма заказа');
        $sheet->setCellValue('M1', 'Статус заказа');

        return $sheet;
    }

    private function setTableRow($sheet, $order, $rowNumber, $deliveryCdek = null)
    {
        $sheet->setCellValue("A$rowNumber", $order->order_id);
        list($deliveryDate, $deliveryTimeFrom) = isset($order->delivery_time_from) ? explode(' ', $order->delivery_time_from) : ['', ''];
        $sheet->setCellValue("B$rowNumber", $deliveryDate);
        $sheet->setCellValue("C$rowNumber", $deliveryTimeFrom);
        $sheet->setCellValue("D$rowNumber", $order->delivery_time_to);
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
            $sheet->setCellValue("F$rowNumber", $metroName);
        }else{
            $sheet->setCellValue("F$rowNumber", 'Не выбрана доставка курьером');
        }
        $sheet->setCellValue("G$rowNumber", $order->delivery_address);
        $sheet->setCellValue("H$rowNumber", $order->client_name);
        $sheet->setCellValue("I$rowNumber", $order->client_phone);
        $sheet->setCellValue("J$rowNumber", $order->order_comment);
        $caseCount = 0;
        //Считаем кастомные чехлы
        if (!empty($order->cart->cartSetCase)) {
            foreach ($order->cart->cartSetCase as $case) {
                $caseCount += $case->item_count;
            }
        }
        $ids = [];
        //Считаем дефолтные чехлы
        if (!empty($order->cart->cartSetProducts)) {
            foreach ($order->cart->cartSetProducts as $case) {
                $caseCount += $case->item_count;
                $ids[] = $case->id;
            }
        }
        $sheet->setCellValue("E$rowNumber", implode(', ', $ids));
        $sheet->setCellValue("K$rowNumber", $caseCount);
        $sheet->setCellValue("L$rowNumber", $order->order_amount);
        $orderStatus = OrderStatus::find($order->order_status_id);
        $sheet->setCellValue("M$rowNumber", $orderStatus->status_name);

        return $sheet;
    }

}