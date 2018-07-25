<?php

namespace App\Ohcasey;
use App\Models\Cart as CartTwo;
use App\Models\PromotionCode;

/**
 * Class promotion
 * @package App\Ohcasey
 */
class Promotion
{
    const TYPE_PERCENTAGE   = '%';
    const TYPE_VALUE        = '';
    const TYPE_DELIVERY     = 'D';
    const TYPE_CUP          = 'Кружка бесплатно';

    /**
     * @var PromotionCode
     */
    protected $promotionCode;
    protected $type;
    protected $value;

    /**
     * promotion parser
     * @param $v
     * @return array
     * @throws \Exception
     */
    public static function parse($v)
    {
        $value = null;
        $type  = null;

        if (preg_match('/^(\d+)%$/', $v, $matches)) {
            $value = intval($matches[1]);
            $type  = self::TYPE_PERCENTAGE;
        } else if ($v == self::TYPE_DELIVERY) {
            $value = null;
            $type  = self::TYPE_DELIVERY;
        } else if (preg_match('/^\d+$/', $v)) {
            $value = (int)$v;
            $type  = self::TYPE_VALUE;
        } else if ($v == self::TYPE_CUP) {
            $value = null;
            $type = self::TYPE_CUP;
        } else {
            throw new \Exception('Invalid value');
        }

        return [$type, $value];
    }

    /**
     * promotion constructor.
     * @param $id
     */
    public function __construct($id)
    {
        /** @var PromotionCode o */
        $this->promotionCode = PromotionCode::findOrFail($id);
        list($this->type, $this->value) = self::parse($this->promotionCode->code_discount);
    }

    /**
     * Get discount
     * @param Cart $cart
     * @param int $deliveryCost
     * @return int
     */
    /* public function getDiscount(Cart $cart, $deliveryCost)
    {
        // Cart summary
        $summary = $cart->summary;

        if ($this->promotionCode->active
            && (
                $this->promotionCode->code_cond_cart_count
                && $this->promotionCode->code_cond_cart_count <= $summary->cnt
            )
            && (
                $this->promotionCode->code_cond_cart_amount
                && $this->promotionCode->code_cond_cart_amount <= $summary->amount
            )
        ) {
            $total = $summary->amount + $deliveryCost;
            switch ($this->type) {
                case self::TYPE_PERCENTAGE:
                    return round($total * $this->value / 100.0);
                case self::TYPE_VALUE:
                    return $this->value;
                case self::TYPE_DELIVERY:
                    return $deliveryCost;
            }
        }

        return 0;
    } */
	// временная промоакция для некоторых доставок
	public function getDiscount(CartTwo $cart, $deliveryCost, $deliveryName = false)
    {	
        // Cart summary
        $summary = $cart->summary;

        if ($this->promotionCode->active
            && (
                $this->promotionCode->code_cond_cart_count
                && $this->promotionCode->code_cond_cart_count <= $summary->cnt
            )
            && (
                $this->promotionCode->code_cond_cart_amount
                && $this->promotionCode->code_cond_cart_amount <= $summary->amount
            )
        ) {
            $total = $summary->amount + $deliveryCost;
            switch ($this->type) {
                case self::TYPE_PERCENTAGE:
                    return round($total * $this->value / 100.0);
                case self::TYPE_VALUE:
                    return $this->value;
                case self::TYPE_DELIVERY:
					// промоакция для некоторых доставок
					if($deliveryName && (($deliveryName == "pickpoint") // Пункты выдачи
					 || ($deliveryName == "post") // Почта России 
					 || ($deliveryName == "courier_moscow") // Курьер по Москве в пределах МКАД
					)){
						return $deliveryCost;						
					}
                case self::TYPE_CUP:
                    if (($cart->cartSetCase->count() >= 2 || $cart->cartSetCase->first()->item_count >= 2) && $cart->cartSetProducts->count() >= 1)
                        return $cart->cartSetProducts->first()->item_cost;
            }
        }

        return 0;
    }
}
