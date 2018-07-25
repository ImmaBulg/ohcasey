<?php

namespace App\Ohcasey;

use App\Models\Cart as CartModel;
use App\Models\CartSet;
use App\Models\CartSetCase;
use App\Models\CartSetProduct;
use App\Models\Item;
use App\Models\Shop\Offer;
use Illuminate\Support\Facades\Request;
use Sinergi\BrowserDetector as Br;

/**
 * Class Cart
 * @package App\Ohcasey
 */
class Cart
{
    /**
     * Static cart
     */
    static $mCart = null;

    /**
     * Add cart item
     * @param $sku
     * @param $count
     * @param int $offer_id = null
     * @param CartModel $cart
     * @param array $params
     */
    public function put($sku, $count, $params = [], $offer_id = null, $cart = null)
    {
        $cart = ($cart ?: $this->get());
        $item = Item::find($sku);
        if ($sku == Ohcasey::SKU_DEVICE) {
            $s = new CartSetCase([
                'item_sku'    => $sku,
                'item_count'  => $count,
                'device_name' => array_get($params, 'DEVICE.device'),
                'case_name'   => array_get($params, 'DEVICE.casey'),
                'item_source' => $params,
                'item_cost'   => $item->item_cost,
                'offer_id'    => $offer_id,
            ]);
            $cart->cartSetCase()->save($s);
        } else if ($sku == Ohcasey::SKU_PRODUCT) {
            /** @var Offer $offer */
            $offer = Offer::findOrFail($offer_id);
            /** @var CartSet $cartSet */
            $cartSet = new CartSet([
                'item_sku'   => $sku,
                'item_count' => $count,
                'item_cost'  => $offer->product->price,
            ]);
            $cart->cartSet()->save($cartSet);
            /** @var Cart $cartSetProduct */
            $cartSetProduct = new CartSetProduct([
                'item_sku'    => $sku,
                'item_count'  => $count,
                'offer_id'    => $offer_id,
                'item_cost'   => $offer->product->price,
                'cart_set_id' => $cartSet->cart_set_id,
            ]);
            $cart->cartSetProducts()->save($cartSetProduct);
        } else {
            $s = new CartSet([
                'item_sku'   => $sku,
                'item_count' => $count,
                'item_cost'  => $item->item_cost,
            ]);
            $cart->cartSet()->save($s);
        }
    }

    /**
     * Remove cart item
     * @param $id
     */
    public function remove($id)
    {
        $cart = $this->get();
        $cart->cartSet()->where('cart_set_id', $id)->delete();
    }

    /**
     * Exists
     */
    public function exists()
    {
        $cart = session('cartId');
        $o = CartModel::find($cart);
        if ($o) {
            self::$mCart = $o;

            return true;
        } else {
            return false;
        }
    }

    /**
     * Get cart
     * @return CartModel|null
     */
    public function get()
    {
        if (!self::$mCart) {
            $cart = session('cartId');
            $o = CartModel::find($cart);
            if (!$o) {
                $o = new CartModel();
                $o->cart_ip = Request::ip();
                $o->cart_user_agent = $_SERVER['HTTP_USER_AGENT'];
                $o->save();
                session(['cartId' => $o->cart_id]);
            }
            self::$mCart = $o;

            // Convert old cart to new
            $oldCart = session('cart');
            if ($oldCart) {
                foreach ($oldCart as $id => $device) {
                    $this->put(Ohcasey::SKU_DEVICE, $device);
                }
                session(['cart' => null]);
            }
        }
		
		// куку для нового раздела с дизайнами
		$basketItemsCount = (self::$mCart->summary->cnt) ? self::$mCart->summary->cnt : 0;
		setcookie("basketItemsCount", $basketItemsCount, time() + 36000, "/");

        return self::$mCart;
    }

    /**
     * Order
     * @param $id
     * @param bool $clear
     */
    public function order($id, $clear = true)
    {
        // Mark order
        $cart = $this->get();
        $cart->cart_order_id = $id;
        $cart->save();
        
//        foreach($cart->cartSetCase as $item){
//            
//            if(isset($item->offer)){
//                ++$item->offer->order_count;
//                $item->offer->save();
//            }
//        }

        // Clear current link
        if ($clear) session(['cartId' => null]);
    }
}
