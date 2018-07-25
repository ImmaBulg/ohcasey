<?php

namespace App\Ohcasey;

use Intervention\Image\ImageManager;

class ProductPhotoManager
{
    /**
     * @param string $filePath
     * @return string
     */
    public function savePhoto($file)
    {
        if (!file_exists(config('product.photo.path'))){
            mkdir(config('product.photo.path'), 0775, true);
        }

        $manager = new ImageManager(array('driver' => 'imagick'));

        $img  = $manager->make($file->getRealPath());
        $name = md5($file->getRealPath() . microtime()) . '.' .$file->getClientOriginalExtension();

        if ($img->width() > $img->height()) {
            $img->widen(config('product.photo.width'), function ($constraint) {
                $constraint->upsize();
            });
        } else {
            $img->heighten(config('product.photo.height'), function ($constraint) {
                $constraint->upsize();
            });
        }

        $img->save(public_path(config('product.photo.path')).$name);

        return $name;
    }
}