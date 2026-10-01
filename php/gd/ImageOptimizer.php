<?php

declare(strict_types=1);

function compressToWebp(
    string $src,
    string $dst,
    int $maxWidth = 1920,
    int $targetKb = 350
): bool {
    $info = @getimagesize($src);
    if (!$info) {
        return false;
    }

    [$w, $h, $type] = $info;

    $img = match ($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($src),
        IMAGETYPE_PNG  => imagecreatefrompng($src),
        IMAGETYPE_WEBP => imagecreatefromwebp($src),
        IMAGETYPE_GIF  => imagecreatefromgif($src),
        default => false,
    };

    if (!$img) {
        return false;
    }

    // Corrige orientação EXIF em JPEG.
    if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $orientation = @exif_read_data($src)['Orientation'] ?? 1;
        $img = match ($orientation) {
            3 => imagerotate($img, 180, 0),
            6 => imagerotate($img, -90, 0),
            8 => imagerotate($img, 90, 0),
            default => $img,
        };
    }

    $w = imagesx($img);
    $h = imagesy($img);

    imagepalettetotruecolor($img);

    if ($w > $maxWidth) {
        $newH = (int) round($h * $maxWidth / $w);
        $resized = imagecreatetruecolor($maxWidth, $newH);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled(
            $resized, $img, 0, 0, 0, 0,
            $maxWidth, $newH, $w, $h
        );
        imagedestroy($img);
        $img = $resized;
    } else {
        imagealphablending($img, false);
        imagesavealpha($img, true);
    }

    $tmp = $dst . '.tmp';

    foreach ([78, 70, 62, 55, 48] as $quality) {
        imagewebp($img, $tmp, $quality);
        clearstatcache(true, $tmp);

        if (filesize($tmp) <= $targetKb * 1024) {
            break;
        }
    }

    imagedestroy($img);

    return rename($tmp, $dst);
}

function makeWebpVariant(
    string $src,
    string $dst,
    int $tw,
    int $th,
    int $quality = 78
): void {
    $img = imagecreatefromwebp($src);
    $w = imagesx($img);
    $h = imagesy($img);

    $scale = max($tw / $w, $th / $h);
    $sw = (int) round($tw / $scale);
    $sh = (int) round($th / $scale);

    $thumb = imagecreatetruecolor($tw, $th);
    imagealphablending($thumb, false);
    imagesavealpha($thumb, true);

    imagecopyresampled(
        $thumb, $img, 0, 0,
        (int) (($w - $sw) / 2),
        (int) (($h - $sh) / 2),
        $tw, $th, $sw, $sh
    );

    imagewebp($thumb, $dst, $quality);

    imagedestroy($thumb);
    imagedestroy($img);
}
