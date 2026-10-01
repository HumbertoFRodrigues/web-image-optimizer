<?php

use App\Support\ImageOptimizer;

$path = $request->file('foto')->getRealPath();
$name = time() . '-foto.webp';
$destination = public_path("uploads/{$name}");

ImageOptimizer::compress($path, $destination);
