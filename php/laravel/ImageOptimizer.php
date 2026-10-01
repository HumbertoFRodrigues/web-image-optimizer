<?php

declare(strict_types=1);

namespace App\Support;

use Intervention\Image\ImageManager;

final class ImageOptimizer
{
    public static function compress(
        string $src,
        string $dst,
        int $maxWidth = 1920,
        int $targetKb = 350
    ): void {
        $manager = ImageManager::gd();
        $image = $manager->read($src);
        $image->scaleDown(width: $maxWidth);

        foreach ([78, 70, 62, 55, 48] as $quality) {
            $encoded = $image->toWebp(quality: $quality);

            if (strlen($encoded->toString()) <= $targetKb * 1024) {
                $encoded->save($dst);
                return;
            }
        }

        $image->toWebp(quality: 48)->save($dst);
    }
}
