<?php

namespace App\Ohcasey;

use App\Models\Casey;
use App\Models\Delivery;
use App\Models\DeliveryCdek;
use App\Models\DeliveryRussianPost;
use App\Models\Device;
use App\Models\Font;
use App\Models\Order;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use App\Models\CdekCity;
use App\Models\Country;
use App\Ohcasey\Delivery\Cdek\Cdek;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Sinergi\BrowserDetector as Br;
use GeoIp2\Database\Reader as Geo;

/**
 * Class Ohcasey
 * @package App\Ohcasey
 */
class Ohcasey
{
    const SKU_DEVICE = 'case';
    const SKU_PRODUCT = 'product';

    const GROUP_ALL = 'все';

    const CASE_WIDTH_DEFAULT = 247;
    const CASE_HEIGHT_DEFAULT = 487;

    const DELIVERY_COURIER = 'courier';
    const DELIVERY_COURIER_MOSCOW = 'courier_moscow';
    const DELIVERY_PICKPOINT = 'pickpoint';
    const DELIVERY_POST = 'post';
    const DELIVERY_SHOWROOM = 'showroom';

    /**
     * Get order directory
     * @param $name
     * @param $phone
     * @param $email
     * @param $deliveryName
     * @param $country
     * @param $city
     * @param $pvz
     * @param $deliveryDate
     * @param $payment_methods_id
     * @param $postCode
     * @param $address
     * @param $comment
     * @param $utm
     * @return string
     */
    public function order(
        $name,
        $phone, $email,
        $deliveryName, $country, $city,
        $pvz, $deliveryDate,
        $payment_methods_id,
        $postCode, $address,
        $comment,
        $utm
    )
    {
        /*
         * Base sizes
         */
        $baseWidth = self::CASE_WIDTH_DEFAULT;
        $baseHeight = self::CASE_HEIGHT_DEFAULT;


        /**
         * @var Cart $cart
         */
        $cart = App::make(Cart::class);
        $mCart = $cart->get();

        /*
         * CART CASES
         */
        foreach ($mCart->cartSetCase as $item) {
            // Source
            $source = $item->item_source;

            // Device
            $device = Device::find($source['DEVICE']['device']);
            $case = Casey::find($source['DEVICE']['casey']);

            // Device
            $source['DEVICE']['deviceName'] = $device->device_caption;
            if ($source['DEVICE']['color']) {
                $source['DEVICE']['colorHex'] = $device->device_color[$source['DEVICE']['color']];
            }
            $source['DEVICE']['caseName'] = $case->case_name;

            // Fonts
            $text = array_get($source, 'TEXT', []);
            foreach ($text as &$t) {
                $t['width_calc'] = round($t['width'] * $baseWidth);
                $t['height_calc'] = round($t['height'] * $baseHeight);
                $t['size_calc'] = round($t['size'] * $baseHeight);
                $t['left_calc'] = round($t['left'] * $baseWidth);
                $t['top_calc'] = round($t['top'] * $baseHeight);

                if (
                    $t['top_calc'] > $baseHeight
                    || $t['top_calc'] + $t['height_calc'] < 0
                    || $t['left_calc'] > $baseWidth
                    || $t['left_calc'] + $t['width_calc'] < 0
                ) {
                    $t['hidden'] = true;
                }

                $font = Font::find($t['name']);
                if (is_null($font)) {
                    $t['fontName'] = (new FontInfo(storage_path('app/fonts/' . $t['name'])))->getFontName();
                } else {
                    $t['fontName'] = $font->font_caption;
                }
            }
            $source['TEXT'] = $text;

            // Smiles
            $smile = array_get($source, 'SMILE', []);
            foreach ($smile as &$s) {
                $s['width_calc'] = round($s['width'] * $baseWidth);
                $s['height_calc'] = round($s['height'] * $baseHeight);
                $s['left_calc'] = round($s['left'] * $baseWidth);
                $s['top_calc'] = round($s['top'] * $baseHeight);

                if (
                    $s['top_calc'] > $baseHeight
                    || $s['top_calc'] + $s['height_calc'] < 0
                    || $s['left_calc'] > $baseWidth
                    || $s['left_calc'] + $s['width_calc'] < 0
                ) {
                    $s['hidden'] = true;
                }
            }
            $source['SMILE'] = $smile;

            $item->item_source = $source;
            $item->save();
        }

        // Get order if exist
        $order = $cart->get()->order;

        /*
         * DELIVERY
         */
        list($deliveryCost, $orderDelivery) = $this->getSuitableDelivery_cart2($deliveryName, $country, $city, $pvz, $postCode, $order);

        // Get discount
        $discount = 0;
        if ($mCart->promotion_code_id) {
            try {
                $promo = new Promotion($mCart->promotion_code_id);
                $discount = $promo->getDiscount($mCart, $deliveryCost, $deliveryName);
            } catch (\Exception $e) {
            }
        }

        $orderData = [
            'order_amount' => $mCart->summary->subtotal,
            'delivery_name' => $deliveryName,
            'delivery_date' => $deliveryDate,
            'delivery_address' => $address,
            'delivery_amount' => $deliveryCost ?: 0,
            'country_iso' => $country,
            'client_name' => $name,
            'client_phone' => $phone,
            'order_comment' => $comment,
            'client_email' => $email,
            'city_id' => $city,
            'discount_amount' => $discount,
            'utm' => $utm,
            'payment_methods_id' => $payment_methods_id
        ];

        if(!empty($order)){
            /*
            * UPDATE ORDER
            */
            $order->update($orderData);
        } else {
            /*
             * CREATE ORDER
             */
            $order = new Order($orderData);
            $order->save();
			
			// fix random id
			// $last_order = Order::orderBy('order_id', 'desc')->first();
			// $order->order_id = $last_order->order_id + mt_rand(0, 100);
			// $r = $order->update();
        }

        $clear = (($order->payment_method && $order->payment_method->is_online) ? false : true);
        $cart->order($order->order_id, $clear);

        if ($orderDelivery) {
            $orderDelivery->order_id = $order->order_id;
            $orderDelivery->save();
        }

        return $order;
    }

    public function getSuitableDelivery($deliveryName, $country, $city, $pvz, $postCode)
    {
        $deliveryCost = 0;
        $orderDelivery = null;

        if ($deliveryName) {
            $mDelivery = Delivery::find($deliveryName);
            if ($country == 'RU') {
                // Cdek
                $cdek = new Cdek();
                $cost = $cdek->cost($city);

                // PVZ
                if ($deliveryName == self::DELIVERY_PICKPOINT) {
                    $pvzCurrent = array_get($cdek->pvz($city, $pvz), '0');

                    $orderDelivery = new DeliveryCdek([
                        'cdek_type' => Cdek::PICKPOINT,
                        'cdek_pvz' => $pvz,
                        'cdek_pvz_name' => array_get($pvzCurrent, 'name'),
                        'cdek_pvz_address' => array_get($pvzCurrent, 'address'),
                        'cdek_pvz_worktime' => array_get($pvzCurrent, 'work_time'),
                        'cdek_city_id' => $city
                    ]);
                    $deliveryCost = array_get($cost, Cdek::PICKPOINT . '.price', null);
                } else if ($deliveryName == self::DELIVERY_COURIER) {
                    $orderDelivery = new DeliveryCdek([
                        'cdek_type' => Cdek::COURIER,
                        'cdek_city_id' => $city
                    ]);
                    $deliveryCost = array_get($cost, Cdek::COURIER . '.price', null);
                } else if ($deliveryName == self::DELIVERY_COURIER_MOSCOW) {
                    $deliveryCost = $mDelivery->delivery_cost;
                } else if ($deliveryName == self::DELIVERY_POST) {
                    $orderDelivery = new DeliveryRussianPost(['post_code' => $postCode]);
                    $deliveryCost = $mDelivery->delivery_cost;
                }
            } else {
                $orderDelivery = new DeliveryRussianPost(['post_code' => $postCode]);
                $deliveryCost = $mDelivery->delivery_cost;
            }
        }

        return [$deliveryCost, $orderDelivery];
    }

    public function getSuitableDelivery_cart2($deliveryName, $country, $city, $pvz, $postCode, $order = null)
    {
        $deliveryCost = 0;
        $orderDelivery = null;
        $classname = '';
        $data = [];

        if ($deliveryName) {
            $mDelivery = Delivery::find($deliveryName);
            if ($country == 'RU') {
                // Cdek
                $cdek = new Cdek();
                $cost = $cdek->cost($city);

                // PVZ
                if ($deliveryName == self::DELIVERY_PICKPOINT) {
                    $pvzCurrent = array_get($cdek->pvz($city, $pvz), '0');

                    $data = [
                        'cdek_type' => Cdek::PICKPOINT,
                        'cdek_pvz' => $pvz,
                        'cdek_pvz_name' => array_get($pvzCurrent, 'name'),
                        'cdek_pvz_address' => array_get($pvzCurrent, 'address'),
                        'cdek_pvz_worktime' => array_get($pvzCurrent, 'work_time'),
                        'cdek_city_id' => $city
                    ];
                    $classname = 'App\Models\DeliveryCdek';
                    $deliveryCost = array_get($cost, Cdek::PICKPOINT . '.price', null);
                } else if ($deliveryName == self::DELIVERY_COURIER) {
                    $data = [
                        'cdek_type' => Cdek::COURIER,
                        'cdek_city_id' => $city
                    ];
                    $classname = 'App\Models\DeliveryCdek';
                    $deliveryCost = array_get($cost, Cdek::COURIER . '.price', null);
                } else if ($deliveryName == self::DELIVERY_COURIER_MOSCOW) {
                    $deliveryCost = $mDelivery->delivery_cost;
                } else if ($deliveryName == self::DELIVERY_POST) {
                    $data = ['post_code' => $postCode];
                    $classname = 'App\Models\DeliveryRussianPost';
                    $deliveryCost = $mDelivery->delivery_cost;
                }
            } else {
                $data = ['post_code' => $postCode];
                $classname = 'App\Models\DeliveryRussianPost';
                $deliveryCost = $mDelivery->delivery_cost;
            }
            //Create new delivery or update if order exists
            if(!empty($classname)){
                if(!empty($order) && $orderDelivery = $classname::find($order->order_id)) {
                    $orderDelivery->update($data);
                } else {
                    $orderDelivery = new $classname($data);
                }
            }
        }

        return [$deliveryCost, $orderDelivery];
    }

    /**
     * Get share object
     * @return Share
     */
    public function getShare()
    {
        $shareId = session('share');
        /** @var Share $share */
        $share = Share::find($shareId);

        if (!$share) {
            $share = new Share(['share_source' => null]);
            $share->save();

            session(['share' => $share->share_hash]);
        }

        return $share;
    }

    /**
     * Clear share
     */
    public function clearShare()
    {
        session(['share' => null]);
    }

    /**
     * Save share source
     * @param $value
     */
    public function saveShare($value)
    {
        $share = $this->getShare();
        $share->share_source = $value;
        $share->save();
    }

    /**
     * Compile order
     * @param Order $order
     * @param int $width
     * @param int $height
     */
    public function orderCompile($order, $width = Ohcasey::CASE_WIDTH_DEFAULT, $height = Ohcasey::CASE_HEIGHT_DEFAULT)
    {
        foreach ($order->cart->cartSetCase as $case) {
            \Storage::put($case->getOrderImgPath(), $this->compile($case->item_source, $width, $height));
        }
    }

    /**
     * Compile to image
     * @param $source
     * @param $width
     * @param $height
     * @return \Imagick
     */
    public function compile($source, $width = Ohcasey::CASE_WIDTH_DEFAULT, $height = Ohcasey::CASE_HEIGHT_DEFAULT)
    {
        $device = new \Imagick();
        $device->readImage(storage_path('app/device/' . $source['DEVICE']['device'] . '/device.png'));
        $device->setBackgroundColor(new \ImagickPixel('transparent'));
        $device->scaleImage($width, $height);

        // Mask
        $mask = new \Imagick();
        $mask->readImage(storage_path('app/device/' . $source['DEVICE']['device'] . '/mask.png'));
        $mask->scaleImage($width, $height);

        // Masked
        $masked = new \Imagick();
        $masked->newImage($width, $height, new \ImagickPixel('transparent'));

        if (array_get($source, 'DEVICE.casey', null)) {
            $casey = new \Imagick();
            $casey->readImage($this->getCasePath($source));
            $casey->scaleImage($width, $height);
            $device->compositeImage($casey, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);
        }

        if (array_get($source, 'DEVICE.bg', null)) {
            $background = new \Imagick();
            $background->readImage(storage_path((array_get($source, 'DEVICE.type') == 'user' ? 'app/upload/' : 'app/bg/') . basename($source['DEVICE']['bg'])));
            $background->scaleImage($width, $height, true);

            $background->setImageBackgroundColor('None');
            $w = $background->getImageWidth();
            $h = $background->getImageHeight();
            $background->extentImage($width, $height, ($w - $width) / 2, ($h - $height) / 2);
            $masked->compositeImage($background, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);
        }

        $layers = [];

        if (array_get($source, 'SMILE', null)) {
            foreach ($source['SMILE'] as $smile) {
                $smile['lType'] = 'smile';
                $layers[] = $smile;
            }
        }

        if (array_get($source, 'TEXT', null)) {
            foreach ($source['TEXT'] as $text) {
                $text['lType'] = 'text';
                $layers[] = $text;
            }
        }

        array_sort($layers, function ($l) {
            return $l['zIndex'] + ($l['lType'] == 'text' ? 200000 : 100000);
        });

        foreach ($layers as $n => $layer) {
            $left = $width * $layer['left'];
            $top  = $height * $layer['top'];

            $imagick = new \Imagick();
            if ($layer['lType'] == 'text') {
                $lWidth = $width * $layer['width'];
                $lHeight = $height * $layer['height'];

                if (
                    $lWidth <= 0 || $lHeight <= 0 || $lWidth >= 1024 || $lHeight >= 1024
                ) {
                    continue;
                }

                $imagick = $this->fontToImage($layer['name'], $layer['text'], floatval($layer['size']) * $height, $layer['color']);
                $imagick->scaleImage($lWidth, $lHeight);
            } else {
                $lWidth = $width * $layer['width'];
                $lHeight = $height * $layer['height'];
                if ($lWidth <= 0 || $lHeight <= 0 || $lWidth >= 1024 || $lHeight >= 1024) {
                    continue;
                }

                $imagick->readImage(storage_path((array_get($layer, 'type') == 'user' ? 'app/upload/' : 'app/smile/') . basename($layer['name'])));
                $imagick->scaleImage($lWidth, $lHeight);
            }

            // Rotate
            $args = [
                $lWidth / 2, # x point to rotate around
                $lHeight / 2, # y point to rotate around
                1, # scaling factor - 1 means no scaling
                -$layer['angle'], # angle to rotate
            ];
            $imagick->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
            $imagick->distortImage(\Imagick::DISTORTION_SCALEROTATETRANSLATE, $args, true);

            $lHeightAfterRotate = $imagick->getImageHeight();
            $lWidthAfterRotate = $imagick->getImageWidth();

            // Add to masked
            $masked->compositeImage(
                $imagick,
                \Imagick::COMPOSITE_DEFAULT,
                $left - ($lWidthAfterRotate - $lWidth) / 2,
                $top - ($lHeightAfterRotate - $lHeight) / 2,
                \Imagick::CHANNEL_ALL
            );
        }

        // Apply mask
        $masked->compositeImage($mask, \Imagick::COMPOSITE_DSTIN, 0, 0, \Imagick::CHANNEL_ALPHA);

        // Full
        $full = new \Imagick();
        $full->newImage($width, $height, new \ImagickPixel('transparent'));
        $full->compositeImage($device, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);
        $full->compositeImage($masked, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);
        $full->setImageFormat("png");

        return $full;
    }

    /**
     * Получить путь до картинки чехла (картинка может не существовать)
     *
     * @param array $source
     * @return string
     */
    public function getCasePath($source)
    {
        return storage_path(
            'app/device/' . $source['DEVICE']['device']
            . '/color/' . $source['DEVICE']['casey']
            . ($source['DEVICE']['color'] !== null && $source['DEVICE']['color'] !== '' ? '_' . $source['DEVICE']['color'] : '')
            . '.png'
        );
    }

    /**
     * Font to image
     * @param $font
     * @param string $text
     * @param int $size
     * @param string $color
     * @return \Imagick
     */
    public function fontToImage($font, $text = 'a', $size = 30, $color = '#000000')
    {
        // Escape font
        $font = str_replace('/', '_', $font);

        if (!file_exists(storage_path('app/fonts/' . $font))) {
            $font = 'Bira.ttf';
        }

        $draw = new \ImagickDraw();
        $draw->setFillColor(new \ImagickPixel($color));
        $draw->setFontSize($size);
        $draw->setFont(storage_path('app/fonts/' . $font));
        $metrics = (new \Imagick())->queryFontMetrics($draw, $text, false);
        $draw->annotation(
            100 - $metrics['boundingBox']['x1'],
            $metrics['textHeight'] + abs($metrics['descender']),
            $text
        );

        $imagick = new \Imagick();
        $imagick->newImage(
            100 + $metrics['textWidth'] + 100,
            100 - $metrics['boundingBox']['y1'] + $metrics['textHeight'] + abs($metrics['descender']) + 100,
            new \ImagickPixel('transparent')
        );
        $imagick->setImageFormat("png");
        $imagick->drawImage($draw);
        $imagick->trimImage(0);

        return $imagick;
    }

    /**
     * Mail order
     * @param $order
     */
    public function orderMail($order)
    {
        $os = new Br\Os($order->cart->cart_user_agent);
        $browser = new Br\Browser($order->cart->cart_user_agent);

        // Send admin mail
        Mail::send(
            'mail.order',
            array_merge([
                'order' => $order,
                'full' => true,
                'printLink' => route('admin.order.print', ['order' => $order->order_id, 'hash' => $order->order_hash, 'type' => 'adminPrint']),
                'os' => $os->getName(),
                'browser' => $browser->getName(),
                'version' => $browser->getVersion()
            ]),
            function ($m) use ($order) {
                // Email
                $m->from(config('mail.from.address'), config('mail.from.name'))
                    ->to(env('ORDER_RECIPIENT_EMAIL'), env('ORDER_RECIPIENT_NAME'))
                    ->subject('OHCASEY: Новый заказ №' . $order->order_id);

                // Admin email
                if (env('ORDER_RECIPIENT_DEV_EMAIL') && env('ORDER_RECIPIENT_DEV_NAME')) {
                    $m->bcc(env('ORDER_RECIPIENT_DEV_EMAIL'), env('ORDER_RECIPIENT_DEV_NAME'));
                }
            }
        );

        // Send user mail
        if ($order->client_email) {
            Mail::send(
                'mail.client',
                [
                    'order' => $order,
                    'os' => $os->getName(),
                    'browser' => $browser->getName(),
                    'version' => $browser->getVersion()
                ],
                function ($m) use ($order) {
                    $m->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($order->client_email, $order->client_name)
                        ->subject('OHCASEY: Ваш заказ №' . $order->order_id);
                }
            );
        }
    }

    /**
     * Get GEO info
     * @return array
     */
    public function geo()
    {
        $ip = Request::ip();
        $countryIso = null;
        $cityName = null;

        // Try ipgeobase.ru
        try {
            $gb = new IPGeoBase(
                base_path('geo/cidr_optim.txt'),
                base_path('geo/cities.txt')
            );
            $record = $gb->getRecord($ip);
            if ($record) {
                $countryIso = $record['cc'];
                $cityName = iconv('WINDOWS-1251', 'UTF-8', $record['city']);
            }
        } catch (\Exception $e) {
        }

        // Try GeoLite2
        if (!$countryIso && !$cityName) {
            try {
                $reader = new Geo(base_path('geo/GeoLite2-City.mmdb'));
                $record = $reader->city($ip);
                $countryIso = $record->country->isoCode;
                $cityName = $record->city->names['ru'];
            } catch (\Exception $e) {
            }
        }

        $country = Country::find($countryIso);
        $city = CdekCity::where('city_name', '=', $cityName)->first();

        return [
            'country_iso' => $countryIso,
            'country_name' => $country ? $country->country_name_ru : null,
            'city_name' => $cityName,
            'cdek_city' => $city ? $city->city_id : null
        ];
    }

    /**
     * Set current group
     * @param null $group
     * @param Carbon|null $expired
     */
    public function setCurrentGroup($group = null, $expired = null)
    {
        session(['userGroup' => $group]);
        if ($expired) {
            session(['expired_' . $group => $expired->toDateTimeString()]);
        }
    }

    /**
     * Get current group
     * @return string
     */
    public function getCurrentGroup()
    {
        $group = session('userGroup', null);

        if ($group != null) {
            $expired = session('expired_' . $group, null);
            if ($expired) {
                $expired = Carbon::parse($expired);
                if (Carbon::now()->gte($expired)) {
                    $group = null;
                }
            }
        }

        return $group;
    }
}
