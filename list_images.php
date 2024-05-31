<?php
function resizeImage($file, $width, $height) {
    list($originalWidth, $originalHeight) = getimagesize($file);
    $aspectRatio = $originalWidth / $originalHeight;
    
    if ($width / $height > $aspectRatio) {
        $newWidth = $height * $aspectRatio;
        $newHeight = $height;
    } else {
        $newHeight = $width / $aspectRatio;
        $newWidth = $width;
    }
    
    $srcImage = imagecreatefromstring(file_get_contents($file));
    $dstImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
    ob_start();
    imagejpeg($dstImage, null, 75); // Adjust quality here (75 is just an example)
    $imageData = ob_get_clean();
    
    imagedestroy($srcImage);
    imagedestroy($dstImage);
    
    return base64_encode($imageData);
}

$directory = 'images';
$images = glob($directory . '/*.{jpg,jpeg,png,webp,tiff}', GLOB_BRACE);
shuffle($images); // Shuffle the array to randomize the image order
$resizedImages = [];
$width = 300;
$height = 300;

foreach ($images as $image) {
    $resizedImages[] = 'data:image/jpeg;base64,' . resizeImage($image, $width, $height);
}

echo json_encode($resizedImages);
?>
