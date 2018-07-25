<?php

namespace App\Console\Commands;

use App\Models\Background;
use App\Models\BackgroundGroup;
use App\Models\Shop\Category;
use App\Models\Device;
use App\Models\Shop\Offer;
use App\Models\Shop\Option;
use App\Models\Shop\OptionValue;
use App\Models\Shop\Product;
use App\Ohcasey\ProductPhotoManager;
use App\Support\ProductImageMaker;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class OnceFillProductAndOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalog:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @param ProductPhotoManager $photoManager
     * @return mixed
     * @throws
     */
    public function handle(ProductPhotoManager $photoManager)
    {
        // iphone4 и iphone4s сейчас без цвета
        $this->fresh();

        // фиксируем чтобы нормально работал firstOrCreate
        $published_at = '2017-03-07 21:18:04';
        //\DB::beginTransaction();
        try {
            $sort = 100;
            // создаем коллекции
            $collectionRoot = Category::firstOrCreate([
                'name'         => 'Коллекции',
                'title'        => 'Коллекции',
                'slug'         => 'collection',
                'order'        => 100,
                'published_at' => $published_at,
            ]);
            $this->info('Создана категория - Коллекции');
            BackgroundGroup::each(function (BackgroundGroup $group) use ($collectionRoot, $published_at, $sort) {
                Category::firstOrCreate([
                    'name'         => $group->name,
                    'title'        => $group->name,
                    'slug'         => str2url($group->name),
                    'order'        => $sort,
                    'published_at' => $published_at,
                    'parent'       => $collectionRoot->getKey(),
                ]);
                $this->info('Создана категория - ' . $group->name);
             });

            // Создаем девайсы
            $this->info('Создана категория - Каталог');
            $deviceRoot = Category::firstOrCreate([
                'name'         => 'Каталог',
                'title'        => 'Каталог',
                'slug'         => 'catalog',
                'order'        => 100,
                'published_at' => $published_at,
            ]);
            $this->info('Создана категория - По модели');
            $sort = 100;
            /** @var Collection|Device[] $devices */
            $devices = \Cache::remember('devices', 200, function () {
                return Device::all();
            });
            $devices->each(function (Device $device) use ($deviceRoot, $sort, $published_at) {
                Category::firstOrCreate([
                    'name'         => $device->device_caption,
                    'title'        => $device->device_caption,
                    'slug'         => str2url($device->device_caption),
                    'order'        => $sort,
                    'published_at' => $published_at,
                    'parent'       => $deviceRoot->getKey(),
                ]);
                $this->info('Создана категория - ' . $device->device_caption);
            });

            $this->info('Старт создания товаров');

            Background::with(['backgroundGroups'])->each(function (Background $background) use ($photoManager) {
                $this->info('Создание товара - ' . $background->name);
                /** @var Product $product */
                $product = Product::firstOrCreate([
                    'name'          => $background->name,
                    'title'         => $background->name,
                    'description'   => $background->name,
                    'code'          => $background->name,
                    'background_id' => $background->id,
                    'price'         => 1500.00,
                    'active'        => true,
                ]);

                $background->backgroundGroups->each(function (BackgroundGroup $group) use ($product) {
                    $this->info('Привязка товара ' . $product->name . ' к категори ' . $group->name);
                    $product->categories()->sync([
                            Category::where(['name' => $group->name])->firstOrFail()->getKey()
                        ],
                        false
                    );
                });

                /** @var Collection|Device[] $devices */
                $devices = \Cache::remember('devices', 200, function () {
                    return Device::all();
                });
                $devices->each(function (Device $device) use ($product) {
                    $this->info('Привязка товара ' . $product->name . ' к категори ' . $device->device_caption);
                    $product->categories()->sync([
                            Category::where(['name' => $device->device_caption])->firstOrFail()->getKey()
                        ],
                        false
                    );
                });

                $this->info('Старт создания предложений');
                Device::each(function (Device $device) use ($background, $product, $photoManager) {
                    /** @var Option $deviceOption */
                    $deviceOption = Option::where([
                        'key'  => 'device',
                        'name' => 'Модель телефона',
                    ])->firstOrFail();

                    /** @var Option $colorOption */
                    $colorOption = Option::where([
                        'key' => 'color',
                        'name' => 'Цвет телефона',
                    ])->firstOrFail();

                    /** @var Option $caseOption */
                    $caseOption = Option::where([
                        'key'  => 'case',
                        'name' => 'Материал чехла',
                    ])->firstOrFail();

                    /** @var OptionValue $deviceOptionValue */
                    $deviceOptionValue = OptionValue::firstOrCreate([
                        'option_id' => $deviceOption->getKey(),
                        'value'     => $device->device_name,
                        'title'     => $device->device_caption,
                    ]);

                    if (empty($device->device_colors)) {
                        $device->device_colors = [ProductImageMaker::ONLY_ONE_COLOR];
                    }

                    foreach ($device->device_colors as $rgbArrayIndex => $rgbColor) {
                        /** @var OptionValue $colorOptionValue */
                        $colorOptionValue = OptionValue::firstOrCreate([
                            'option_id' => $colorOption->getKey(),
                            'title'     => $rgbColor,
                            'value'     => $rgbColor,
                        ]);

                        $cases = [
                            'plastic'   => 'Матовый пластик',
                            'silicone'  => 'Силикон',
                            'softtouch' => 'Soft Touch',
                        ];

                        foreach ($cases as $caseFileName => $caseName) {
                            $caseDeviceColorPath = storage_path('app/device/' . $device->device_name . '/case/' . $caseFileName  . '.png');
                            if (\File::exists($caseDeviceColorPath)) {
                                $caseOptionValue = OptionValue::firstOrCreate([
                                    'option_id' => $caseOption->getKey(),
                                    'value'     => $caseFileName,
                                    'title'     => $caseName,
                                ]);

                                $this->info('Предложение ' . $product->name
                                    . ' девайс: ' . $device->device_caption
                                    . ' цвет: '   . $rgbColor
                                    . ' чехол: '  . $caseName
                                );

                                Offer::create([
                                    'product_id' => $product->getKey(),
                                    'options' => [
                                        $deviceOptionValue->getKey(),
                                        $colorOptionValue->getKey(),
                                        $caseOptionValue->getKey(),
                                    ],
                                    'active' => true,
                                ]);
                            }
                        }
                    }
                });
            });
            //\DB::commit();
        } catch (\Exception $e) {
            //\DB::rollBack();
            throw $e;
        }

        return;
    }

    /**
     * iphone4 + iphone4s в базе без цвета, новый магазин обязательно ждет цвет.
     */
    protected function fresh()
    {
        /** @var Device $device */
        $device = Device::where('device_name', 'iphone4')->firstOrFail();
        $device->device_colors = ["#000000"];
        $device->save();
        /** @var Device $device */
        $device = Device::where('device_name', 'iphone4s')->firstOrFail();
        $device->device_colors = ["#000000"];
        $device->save();
    }
}
