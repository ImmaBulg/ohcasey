<?php

namespace App\Console\Commands;

use App\Models\Background;
use Illuminate\Console\Command;
use Intervention\Image\ImageManager;

class DeviceImageImplode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device-image-implode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'iPhone 7 + case plastic_0 + top 30 background';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manager = new ImageManager(array('driver' => 'imagick'));
        Background::with('backgroundGroups')
            ->get()
            ->each(function (Background $background) use ($manager) {
                foreach ($background->backgroundGroups as $group) {
                    $destinationDirPath = storage_path('iphone7_silicone_0_' . strtolower($group->name));

                    if (!\File::exists($destinationDirPath)) {
                        \File::makeDirectory($destinationDirPath);
                    }
                    $backgroundFilePath = storage_path('app/generated/bg/500/' . $background->name);
                    if (\File::exists($backgroundFilePath)) {
                        $iphone7Image = $manager->make(storage_path('app/device/iphone7/device.png'));
                        $resultFile = $destinationDirPath . '/' . $background->name;
                        $iphone7Image
                            ->insert($manager->make(storage_path('app/device/iphone7/color/silicone_0.png')))
                            ->insert($manager
                                ->make($backgroundFilePath)
                                ->resize($iphone7Image->width(), $iphone7Image->height()))
                            ->save($resultFile);

                        $this->info($resultFile);
                    }
                }
        });
    }
}
