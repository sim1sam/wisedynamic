<?php
// Create a simple pattern background image for the about page header
header('Content-Type: image/png');

// Create image
$width = 400;
$height = 400;
$image = imagecreatetruecolor($width, $height);

// Colors
$bg = imagecolorallocatealpha($image, 255, 255, 255, 127); // Transparent background
$dot = imagecolorallocate($image, 255, 255, 255); // White dots

// Fill background with transparency
imagefill($image, 0, 0, $bg);

// Draw pattern (dots)
for ($x = 0; $x < $width; $x += 20) {
    for ($y = 0; $y < $height; $y += 20) {
        imagefilledellipse($image, $x, $y, 3, 3, $dot);
    }
}

// Save the image
imagepng($image, __DIR__ . '/images/pattern-bg.png');
imagedestroy($image);

echo "Pattern background created successfully at: /images/pattern-bg.png";
