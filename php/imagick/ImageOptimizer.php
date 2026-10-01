<?php

declare(strict_types=1);

function compressToWebpImagick(
    string $src,
    string $dst,
    int $maxWidth = 1920,
    int $targetKb = 350
): void {
    $img = new Imagick($src);

    switch ($img->getImageOrientation()) {
        case Imagick::ORIENTATION_BOTTOMRIGHT:
            $img->rotateImage('#000', 180);
            break;
        case Imagick::ORIENTATION_RIGHTTOP:
            $img->rotateImage('#000', 90);
            break;
        case Imagick::ORIENTATION_LEFTBOTTOM:
            $img->rotateImage('#000', -90);
            break;
    }

    $img->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
    $img->stripImage();

    if ($img->getImageWidth() > $maxWidth) {
        $img->resizeImage($maxWidth, 0, Imagick::FILTER_LANCZOS, 1);
    }

    $img->setImageFormat('webp');

    $blob = '';
    foreach ([78, 70, 62, 55, 48] as $quality) {
        $img->setImageCompressionQuality($quality);
        $blob = $img->getImageBlob();

        if (strlen($blob) <= $targetKb * 1024) {
            break;
        }
    }

    file_put_contents($dst, $blob);
    $img->clear();
}
