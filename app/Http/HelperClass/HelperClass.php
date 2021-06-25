<?php

namespace App\Http\HelperClass;

class HelperClass
{
    public static function optimizeImage($image, $destination)
    {
        $image_info = getimagesize($image);
        if ($image_info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($image);
            imagejpeg($image, $destination, 35);
        } elseif ($image_info['mime'] == 'image/png') {
            $image = imagecreatefrompng($image);
            imagepng($image, $destination, 3);
        }
        return $compress_image;
    }

    public static function convertToReadableSize($size)
    {
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }
}
