<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Models\CartSet;
use App\Models\CartSetCase;
use App\Models\CartSetProduct;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Shop\Product;
use App\Models\SpecialOrderItem;
use Illuminate\Support\Collection;

class OrderImplode extends Job
{
    /**
     * @var Order
     */
    protected $destination;

    /**
     * @var \App\Models\Order[]|Collection
     */
    protected $orders;

    /**
     * @var array
     */
    protected $filesToMove = [];

    /**
     * @param Order $destination
     * @param Collection|Order[] $orders
     */
    public function __construct($destination, $orders)
    {
        $this->destination = $destination;
        $this->orders = $orders;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function handle()
    {
        $self = $this;

        \DB::transaction(function () use ($self) {
            $self->implode();
        });

        foreach ($this->filesToMove as $paths) {
            list($oldPath, $newPath) = $paths;
            \Storage::move($oldPath, $newPath);
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    protected function implode()
    {
        foreach ($this->orders as $order) {
            OrderLog::create([
                'order_id'    => $this->destination->order_id,
                'description' => 'Присоединил заказ #' . $order->order_id,
                'short_code'  => OrderLog::CUSTOM_CODE,
            ]);
            if ($order->order_id == $this->destination->order_id) {
                continue;
            }
            $this->moveOrder($order);

            // todo - что делать с промокодами?
            $this->destination->order_amount += abs($order->order_amount);
            $this->destination->save();
            $order->delete();
        }

        $this->destination->save();
    }

    /**
     * @param Order $order
     * @return bool
     */
    protected function moveOrder($order)
    {
        if ($order->order_id == $this->destination->order_id) {
            return false;
        }

        $destination = $this->destination;

        $order->specialItems->each(function (SpecialOrderItem $item) use($destination) {
            $item->order()->associate($destination)->save();
        });

        if ($order->cart) {
            foreach ($order->cart->cartSet as $cartSet) {
                $this->moveSet($cartSet);
            }
            foreach ($order->cart->cartSetCase as $cartSet) {
                $this->moveSet($cartSet);
            }
            foreach ($order->cart->cartSetProducts as $cartProduct) {
                $this->moveProduct($cartProduct);
            }
        }

        return true;
    }

    /**
     * @param CartSet|CartSetCase $cartSet
     * @return array
     */
    protected function moveSet($cartSet)
    {
        $cartSet->load('cart.order');
        if ($this->destination->cart) {
            $cartSet->cart_id = $this->destination->cart->cart_id;
        } else {
            $cart = $cartSet->cart;
            $cart->cart_order_id = $this->destination->order_id;
            $cart->save();
            $this->destination->load('cart');
        }

        $cartSet->save();

        $path = $cartSet->getOrderImgPath();

        if (\Storage::exists($path)) {

            $cartSet->load('cart.order');

            $this->filesToMove[] = [
                $path,
                $cartSet->getOrderImgPath()
            ];
        }

        return null;
    }

    /**
     * @param CartSetProduct $product
     * @return array
     */
    protected function moveProduct($product)
    {
        $product->load('cart.order');
        if ($this->destination->cart) {
            $product->cart_id = $this->destination->cart->cart_id;
        } else {
            $cart = $product->cart;
            $cart->cart_order_id = $this->destination->order_id;
            $cart->save();
            $this->destination->load('cart');
        }

        $product->save();

        return null;
    }
}