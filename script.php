<?php

$data = json_decode(file_get_contents('data.json'), true);

$photographers = [
    'Don Cochran',
    'Don Cochran',
    'Don Cochran',
    'Bob Clemens',
    'Steve Kelly',
    'Don Cochran',
    'Don Cochran',
    'Alfons Rudolph',
    'John Menihan',
    'John Menihan',
    'Don Cochran',
    'Don Cochran',
    'Norm Kerr',
    'Norm Kerr',
    'Steve Kelly',
    'Don Cochran',
    'Stephen Wolf',
    'Alan Fink',
    'Alan Fink',
    'Alan Fink',
    'Alan Fink',
    'Cindy Branham',
    'Steve Kelly',
    'Alfons Rudolph',
];

$output = [];
$output['default_scale'] = 'fit';
$output['images'] = [];

$index = 1;

foreach ($data as $image) {

    $original_path = __DIR__ . '/images/' . $image['file'] . '.original.png';

    $size = getimagesize($original_path);

    $output_image = [];
    $output_image['image_name'] = "Kodim $index";
    $output_image['width'] = $size[0];
    $output_image['height'] = $size[1];
    $output_image['variants'] = [];

    // original

    $original = [];
    $original['variant_name'] = 'original';
    $original['path'] = 'images/' . $image['file'] . '.original.png';
    $original['labels'] = [];
    array_push($original['labels'], [
        'key' => 'Size',
        'value' => '–',
    ]);
    array_push($original['labels'], [
        'key' => 'Change',
        'value' => '—',
    ]);
    array_push($original['labels'], [
        'key' => 'SSIM',
        'value' => '—',
    ]);
    array_push($original['labels'], [
        'key' => 'Q',
        'value' => '—',
    ]);
    array_push($original['labels'], [
        'key' => 'Photographer',
        'value' => $photographers[$index - 1],
    ]);

    array_push($output_image['variants'], $original);

    // cjpeg

    $cjpeg = [];
    $cjpeg['variant_name'] = 'cjpeg';
    $cjpeg['path'] = 'images/' . $image['file'] . '.cjpeg.png';
    $cjpeg['labels'] = [];
    array_push($cjpeg['labels'], [
        'key' => 'Size',
        'value' => round($image['cjpeg']['size'] / 1000) . ' KB',
    ]);
    array_push($cjpeg['labels'], [
        'key' => 'Change',
        'value' => round((-($image['cjpeg']['size'] - $image['cjpeg']['size']) / $image['cjpeg']['size'] * 100)) . ' %',
    ]);
    array_push($cjpeg['labels'], [
        'key' => 'SSIM',
        'value' => $image['cjpeg']['ssim'],
    ]);
    array_push($cjpeg['labels'], [
        'key' => 'Q',
        'value' => $image['cjpeg']['quality'],
    ]);
    array_push($cjpeg['labels'], [
        'key' => 'Photographer',
        'value' => $photographers[$index - 1],
    ]);

    array_push($output_image['variants'], $cjpeg);

    // mozjpeg

    $mozjpeg = [];
    $mozjpeg['variant_name'] = 'mozjpeg';
    $mozjpeg['path'] = 'images/' . $image['file'] . '.mozjpeg.png';
    $mozjpeg['labels'] = [];
    array_push($mozjpeg['labels'], [
        'key' => 'Size',
        'value' => round($image['mozjpeg']['size'] / 1000) . ' KB',
    ]);
    array_push($mozjpeg['labels'], [
        'key' => 'Change',
        'value' => round(-(($image['cjpeg']['size'] - $image['mozjpeg']['size']) / $image['cjpeg']['size'] * 100)) . ' %',
    ]);
    array_push($mozjpeg['labels'], [
        'key' => 'SSIM',
        'value' => $image['mozjpeg']['ssim'],
    ]);
    array_push($mozjpeg['labels'], [
        'key' => 'Q',
        'value' => $image['mozjpeg']['quality'],
    ]);
    array_push($mozjpeg['labels'], [
        'key' => 'Photographer',
        'value' => $photographers[$index - 1],
    ]);

    array_push($output_image['variants'], $mozjpeg);

    // webp

    $webp = [];
    $webp['variant_name'] = 'webp';
    $webp['path'] = 'images/' . $image['file'] . '.webp.png';
    $webp['labels'] = [];
    array_push($webp['labels'], [
        'key' => 'Size',
        'value' => round($image['webp']['size'] / 1000) . ' KB',
    ]);
    array_push($webp['labels'], [
        'key' => 'Change',
        'value' => round(-(($image['cjpeg']['size'] - $image['webp']['size']) / $image['cjpeg']['size'] * 100)) . ' %',
    ]);
    array_push($webp['labels'], [
        'key' => 'SSIM',
        'value' => $image['webp']['ssim'],
    ]);
    array_push($webp['labels'], [
        'key' => 'Q',
        'value' => $image['webp']['quality'],
    ]);
    array_push($webp['labels'], [
        'key' => 'Photographer',
        'value' => $photographers[$index - 1],
    ]);

    array_push($output_image['variants'], $webp);

    // avif

    $avif = [];
    $avif['variant_name'] = 'avif';
    $avif['path'] = 'images/' . $image['file'] . '.avif.png';
    $avif['labels'] = [];
    array_push($avif['labels'], [
        'key' => 'Size',
        'value' => round($image['avif']['size'] / 1000) . ' KB',
    ]);
    array_push($avif['labels'], [
        'key' => 'Change',
        'value' => round(-(($image['cjpeg']['size'] - $image['avif']['size']) / $image['cjpeg']['size'] * 100)) . ' %',
    ]);
    array_push($avif['labels'], [
        'key' => 'SSIM',
        'value' => $image['avif']['ssim'],
    ]);
    array_push($avif['labels'], [
        'key' => 'Q',
        'value' => $image['avif']['quality'],
    ]);
    array_push($avif['labels'], [
        'key' => 'Photographer',
        'value' => $photographers[$index - 1],
    ]);

    array_push($output_image['variants'], $avif);

    array_push($output['images'], $output_image);

    $index++;
}

file_put_contents('src/data.json', json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));