<?php
require __DIR__ . '/ImageOptimizer.php';

$src = __DIR__ . '/../../examples/input/photo.jpg';
$dst = __DIR__ . '/../../examples/output/photo.webp';

compressToWebp($src, $dst, 1920, 350);

foreach ([[150,150], [370,222], [740,444], [1024,615]] as [$w, $h]) {
    makeWebpVariant(
        $dst,
        __DIR__ . "/../../examples/output/photo-{$w}x{$h}.webp",
        $w,
        $h
    );
}
