<?php

namespace App\Models;

use App\Support\OrderTemplateSmsSender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Класс заказа.
 *
 * @property bool $processed_online_payment
 * @property int $order_id
 * @property int $order_status_id
 * @property int $order_amount
 * @property int $city_id
 * @property int $discount_amount
 * @property int $delivery_amount
 * @property int payment_methods_id
 * @property string $delivery_name
 * @property string $client_phone
 * @property string $delivery_date
 * @property string $country_iso
 * @property string $client_name
 * @property string $order_comment
 * @property string $client_email
 * @property string $delivery_address
 * @property string $consultant_note
 * @property string $order_hash
 * @property string $utm
 * @property string $google_client_id
 * @property string $order_ts
 * @property string $deleted_at
 * @property-read Cart|null $cart
 * @property-read Country|null $country
 * @property-read CdekCity|null $city
 * @property-read DeliveryRussianPost|null $deliveryRussianPost
 * @property-read DeliveryCdek|null $deliveryCdek
 * @property-read Collection|OrderLog[] $orderLogs
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|SpecialOrderItem[] $specialItems
 * @property-read PaymentMethod $payment_method
 * @property-read Collection|Transaction[] $transactions
 * @method static Builder byStatus(OrderStatus $status)
 * @method static Builder betweenDates(Carbon $dateStart, Carbon $dateEnd)
 * @package App\Models
 */
class Order extends Model
{
    use SoftDeletes;

    // Заказ создан с выбором "онлайн оплаты" при оформлении
    const PROCESSED_ONLINE_PAYMENT = 'processed_online_payment';
    // Оплачен
    const ALL_PAYMENTS_PAID = 'all_payments_paid';
    // Частично оплачен
    const PARTIAL_PAYMENTS_PAID = 'partial_payments_paid';
    // Не оплачен
    const NOONE_PAYMENTS_PAID = 'noone_payments_paid';
    // Нет счетов
    const WITHOUT_PAYMENTS = 'without_payments';

    public $table = 'order';
    public $timestamps = false;
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'order_amount', // сумма без учёта скидки и доставки и стоимости спец. товара
        'delivery_name',
        'delivery_date',
        'delivery_address',
        'delivery_amount',
        'country_iso',
        'client_name',
        'client_phone',
        'order_comment',
        'client_email',
        'order_status_id',
        'city_id',
        'discount_amount',
        'utm',
        'google_client_id',
        'consultant_note',
        'payment_methods_id',
        'processed_online_payment',
        'delivery_time_to',
        'delivery_time_from',
    ];

    protected $dates = ['deleted_at'];

    public function setDeliveryDateAttribute($value)
    {
    	$this->attributes['delivery_date'] = $value ?: null;
    }

    /**
     * @param Builder $query
     * @param OrderStatus $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, OrderStatus $status)
    {
        return $query->where('order_status_id', $status->getKey());
    }

    /**
     * @param Builder $query
     * @param Carbon $dateStart
     * @param Carbon $dateEnd
     * @return Builder
     */
    public function scopeBetweenDates(Builder $query, Carbon $dateStart, Carbon $dateEnd)
    {
        return $query->whereBetween('order_ts', [$dateStart, $dateEnd]);
    }

    /**
     * Общая стоимость заказа.
     *
     * @return int
     */
    public function getTotalSum()
    {
        return (
            $this->order_amount
            + $this->getSpecialItemsSum()
            + $this->delivery_amount
            - $this->discount_amount
        );
    }

    public function getProductSum() {
        $sum = 0;
        foreach ($this->cart->cartSetCase as $item) {
            $sum += $item->item_cost * $item->item_count;
        }
        foreach ($this->cart->cartSetProducts as $item) {
            $sum += $item->item_cost * $item->item_count;
        }

        return $sum;
    }

    /**
     * Сумма всех сопутствующих товаров.
     *
     * @return float
     */
    public function getSpecialItemsSum()
    {
        return $this->specialItems->sum(function (SpecialOrderItem $item) {
            return $item->price;
        });
    }

    /**
     * Получить статус оплаты.
     *
     * @return string
     */
    public function getPaymentsStatus()
    {
        if ($this->processed_online_payment) {
            return static::PROCESSED_ONLINE_PAYMENT;
        }

        $amount = $this->payments->sum(function (Payment $payment) {
            return $payment->is_paid ? $payment->amount : 0;
        });

        if ($this->payments->count()) {
            if ($amount >= $this->getTotalSum()) {
                return static::ALL_PAYMENTS_PAID;
            } else if ($amount) {
                return static::PARTIAL_PAYMENTS_PAID;
            } else {
                return static::NOONE_PAYMENTS_PAID;
            }
        }

        return static::WITHOUT_PAYMENTS;
    }

    /**
     * Cart
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart()
    {
        return $this->hasOne(Cart::class, 'cart_order_id', 'order_id');
    }

    /**
     * Status
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne(OrderStatus::class, 'status_id', 'order_status_id');
    }

    /**
     * Delivery
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'delivery_name', 'delivery_name');
    }

    /**
     * Delivery CDEK
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryCdek()
    {
        return $this->hasOne(DeliveryCdek::class, 'order_id', 'order_id');
    }

    /**
     * Delivery Russian post
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryRussianPost()
    {
        return $this->hasOne(DeliveryRussianPost::class, 'order_id', 'order_id');
    }

    /**
     * Country
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'country_iso', 'country_iso');
    }

    /**
     * City
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function city()
    {
        return $this->hasOne(CdekCity::class, 'city_id', 'city_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specialItems()
    {
        return $this->hasMany(SpecialOrderItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment_method(){
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_methods_id');
    }
}
