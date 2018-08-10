<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12.07.2018
 * Time: 10:05
 */

namespace App\Http\Controllers;
use App\Models\Background;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function getCsv($startDate, $endDate)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);
        $orders = Order::whereBetween('order_ts', [$startDate, $endDate])->get();
        $result = [];
        /**/
        $result[0] = [
            'ID заказа',
            'Дата заказа',
            'Статус заказа',
            'Название футболки',
            'Код футболки',
            'Background ID',
            'Background name',
            'Цена',
        ];
        $i = 1;
        foreach ($orders as $order)
        {
            foreach($order->cart->cartSetProducts as $cartSetProduct)
            {
                $product = $cartSetProduct->offer->product;
                if ($product->option_group_id == 9)
                {
                    $result[$i] = [
                        $order->order_id,
                        $order->order_ts,
                        OrderStatus::where(['status_id' => $order->order_status_id])->pluck('status_name')[0],
                        $product->name,
                        $product->code,
                        $product->background_id,
                        ($product->background_id) ? Background::where(['id' => $product['background_id']])->pluck('name')[0] : 'Не задан',
                        $product->price,
                    ];
                }
            }

            $i++;
        }

        $FH = fopen('file.csv', 'w');
        foreach ($result as $row) {
            fputcsv($FH, $row);
        }
        fclose($FH);

        return 'Complete';
    }

    public function getTshirt($startDate, $endDate)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->modify('midnight');
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->modify('23:59');

        $orders = Order::whereBetween('order_ts', [$startDate, $endDate])->get();
        $spreadSheet = new Spreadsheet();
        $sheetPvz = $spreadSheet->getActiveSheet();
        $sheetPvz = $this->getTableHead($sheetPvz);
        $rowNumber = 2;
        foreach ($orders as $order) {
            foreach ($order->cart->cartSetProducts as $cartSetProduct) {
                $product = $cartSetProduct->offer->product;
                if ($cartSetProduct->offer->product)
                if ($product->option_group_id == 9 && ($product->print_status_id == '75' || $product->print_status_id == '69' || $order->order_status_id == 6)) {
                    $sheetPvz->setCellValue("A$rowNumber", $product->title);
                    $sheetPvz->setCellValue("B$rowNumber", $product->code);
                    $sheetPvz->setCellValue("C$rowNumber", $order->order_ts);
                    $sheetPvz->setCellValue("D$rowNumber", OrderStatus::where(['status_id' => $order->order_status_id])->first()->status_name);
                    $rowNumber++;
                }
            }
        }
        $writterCorier = new Xlsx($spreadSheet);
        $fileNameCourier = date('Y-m-d-H-i-s', time()) . '_futbolki.xlsx';
        $fileLocationCourier = public_path() . '/tmp/import/' . $fileNameCourier;
        $writterCorier->save($fileLocationCourier);

        return 'Success. File on path ' . $fileLocationCourier;
    }

    public function getTableHead($sheet)
    {
        $sheet->setCellValue('A1', 'Название футболки');
        $sheet->setCellValue('B1', 'Артикул');
        $sheet->setCellValue('C1', 'Дата заказа');
        $sheet->setCellValue('D1', 'Статус заказа');

        return $sheet;
    }
}