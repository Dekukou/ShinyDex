<?php

namespace App\Service;

use GdImage;

class SpriteImageProcessor
{
    public function removeWhiteBackground(string $sourcePath): void
    {
        $image = imagecreatefrompng($sourcePath);

        if (!$image instanceof GdImage) {
            return;
        }

        imagesavealpha($image, true);

        $width = imagesx($image);
        $height = imagesy($image);

        $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $rgb);

                if (
                    $colors['red'] > 245 &&
                    $colors['green'] > 245 &&
                    $colors['blue'] > 245
                ) {
                    imagesetpixel($image, $x, $y, $transparent);
                }
            }
        }

        imagepng($image, $sourcePath);

        // PHP 8.3+ : destruction automatique
        unset($image);
    }
}
