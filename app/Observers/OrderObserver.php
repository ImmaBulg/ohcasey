<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderStatus;
use App\Models\SmsTemplate;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Support\OrderTemplateSmsSender;
use App\vendor\Google\Analytics\Client;
use App\vendor\Google\Analytics\Protocol;
use App\Models\Shop\Product;

/**
 * Class OrderObserver
 * @package App\Observers
 */
class OrderObserver
{
    
    /**
     * @param Order $order
     * @throws \Exception
     */
    public function creating(Order $order)
    {
        $client = new Client;
        $order->google_client_id = $client->gaParseCookie();
    }
    
    /**
     * @param Order $order
     * @throws \Exception
     */
    public function created(Order $order)
    {
        $this->trySendSms($order);
    }

    /**
     * @param Order $order
     */
    public function saved(Order $order)
    {
        $this->makeLog($order);

        if ($order->isDirty('order_status_id') && $order->order_status_id == OrderStatus::STATUS_ID_FINISHED) {
            $this->makeTransaction($order);
            $this->hitGoogleAnalytics($order);
        }
    }

    protected function makeTransaction(Order $order)
    {
        $amount = ($order->getTotalSum() - $order->transactions->sum(function (Transaction $transaction) {
                return $transaction->amount;
            }));

        if ($amount > 0) {
            try {
                Transaction::create([
                    'order_id'            => $order->getKey(),
                    'amount'              => $amount,
                    'transaction_type_id' => TransactionType::ID_TYPE_FOR_INCOMING_BY_ORDER,
                ]);
            } catch (\Exception $e) {
                \Log::critical('Не удалось создать транзакцию:' . $e->getMessage() . ' ' . $e->getTraceAsString());
            }
        }
    }
    
    protected function hitGoogleAnalytics(Order $order)
    {
        $protocol = new Protocol;
        
        $parameters = [
            'cid'=>$order->google_client_id,
            't'=>'transaction',
            'ti'=>$order->order_id,
            'tr'=>$order->getTotalSum(),
            'ts'=>$order->delivery_amount,
            'cu'=>'RUB',           
        ];
        
        $protocol->track($parameters);
        
        $parameters = [
            'cid'=>$order->google_client_id,
            't'=>'item',
            'ti'=>$order->order_id,
            'cu'=>'RUB',
        ];
        
        foreach($order->cart->cartSetCase as $item){
            
            if(isset($item->offer->product)){
                $parameters['ic'] = $item->offer->product->id;
                $parameters['in'] = $item->offer->product->name;
                if(!empty($c = $item->offer->product->firstCategory())){
                    $parameters['iv'] = $c->name;
                } 
                
            }else{
                
                if(isset($item->item_source['DEVICE']['bg']) and $item->item_source['DEVICE']['bg']){
                    
                    $background = $item->item_source['DEVICE']['bg'];
                                    
                    $product = Product::whereHas('background', function ($query) use ($background) {
                                    $query->where('name', $background);
                                })->first();
                        
                    if($product){
                        $parameters['in'] = $product->name;
                        $parameters['ic'] = $product->id;
                        if(!empty($c = $product->firstCategory())){
                            $parameters['iv'] = $c->name;
                        }
                    }
                }else{  
                    $parameters['ic'] = $item->device_name;
                    $parameters['in'] = $item->device->device_caption;
                }
            }
            $parameters['ip'] = $item->item_cost;
            $parameters['iq'] = $item->item_count;
            $protocol->track($parameters);
        }
        
        foreach($order->cart->cartSetProducts as $item){
            $parameters['ic'] = $item->offer->product->id;
            $parameters['in'] = $item->offer->product->name;
            if(!empty($c = $item->offer->product->firstCategory())){
                $parameters['iv'] = $c->name;
            }
            $parameters['ip'] = $item->item_cost;
            $parameters['iq'] = $item->item_count;
            $protocol->track($parameters);
        }
        
    }

    /**
     * @param Order $order
     */
    protected function makeLog(Order $order)
    {
        foreach ($order->getDirty() as $fieldName => $fieldValue) {
            // так мы кастуем null|пустую строку|число 0 в false, чтобы не делать логи
            // старое значеине null, новое - пустая строка
            $oldValue = $order->getOriginal($fieldName, false) ?: false;
            $newValue = $order->{$fieldName} ?: false;
            if ($oldValue != $newValue) {
                OrderLog::create([
                    'order_id'    => $order->order_id,
                    'description' => '',
                    'short_code'  => OrderLog::UPDATE_CODE,
                    'field_name'  => $fieldName,
                    'old_value'   => $order->getOriginal($fieldName),
                    'new_value'   => $fieldValue,
                ]);
            }
        }
    }

    /**
     * @param Order $order
     * @throws \Exception
     */
    protected function trySendSms(Order $order)
    {
        $template = SmsTemplate::whereNull('before_order_status_id')
            ->where('after_order_status_id', $order->order_status_id ?: 0)
            ->first();

        if ($template) {
            try {
                $sender = new OrderTemplateSmsSender($order, $template);
                $sender->send();
            } catch (\Exception $e) {
                \Log::critical($e->getMessage(), $e->getTrace());
            }
        }
    }
}