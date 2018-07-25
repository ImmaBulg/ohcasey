<?php

namespace App\Support;

use App\Ohcasey\Ohcasey;

class ProductImageMaker
{
    const ONLY_ONE_COLOR = null;

    /**
     * Создать картинку чехла.
     * Если у девайса нет цвета, $deviceColor должен быть строго null.
     *
     * @param string $bgName - название картинки
     * @param string $deviceName - название устройство
     * @param string $caseFileName - тип чехла
     * @param int $deviceColorIndex - индекс цвета из таблицы Device
     * @param int|null $deviceColor
     * @param int $width
     * @param int $height
     *
     * @return \Imagick
     */
    public function make($bgName, $deviceName, $caseFileName, $deviceColorIndex, $deviceColor, $width = Ohcasey::CASE_WIDTH_DEFAULT, $height = Ohcasey::CASE_HEIGHT_DEFAULT)
    {
        $device = new \Imagick();
        $device->readImage(storage_path('app/device/' . $deviceName . '/device.png'));
        $device->setBackgroundColor(new \ImagickPixel('transparent'));
        $device->scaleImage($width, $height);

        // Mask
        $mask = new \Imagick();
        $mask->readImage(storage_path('app/device/' . $deviceName . '/mask.png'));
        $mask->scaleImage($width, $height);

        // Masked
        $masked = new \Imagick();
        $masked->newImage($width, $height, new \ImagickPixel('transparent'));

        if ($deviceColor === static::ONLY_ONE_COLOR) {
            $colorPath = '';
        } else {
            $colorPath = ('_' . $deviceColorIndex);
        }
        $caseDeviceColorPath = storage_path('app/device/' . $deviceName . '/color/' . $caseFileName . $colorPath . '.png');

        $casey = new \Imagick();
        $casey->readImage($caseDeviceColorPath);
        $casey->scaleImage($width, $height);
        $device->compositeImage($casey, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);

        $background = new \Imagick();
        $background->readImage(storage_path('app/bg/') . basename($bgName));
        $background->scaleImage($width, $height, true);

        $background->setImageBackgroundColor('None');
        $w = $background->getImageWidth();
        $h = $background->getImageHeight();
        $background->extentImage($width, $height, ($w - $width) / 2, ($h - $height) / 2);
        $masked->compositeImage($background, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);

        // Apply mask
        $masked->compositeImage($mask, \Imagick::COMPOSITE_DSTIN, 0, 0, \Imagick::CHANNEL_ALPHA);

        // Full
        $full = new \Imagick();
        $full->newImage($width, $height, new \ImagickPixel('transparent'));
        $full->compositeImage($device, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);
        $full->compositeImage($masked, \Imagick::COMPOSITE_DEFAULT, 0, 0, \Imagick::CHANNEL_ALL);
        $full->setImageFormat('png');

        return $full;
    }
}