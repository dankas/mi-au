<?php

function uploadImage($file, $petName = null) {
    $targetDir = "assets/imgs/uploads/";
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow certain image formats
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
        // Upload file to server
        move_uploaded_file($file["tmp_name"], $targetFilePath);
        if (($petName !== null)) {
            addWatermark($targetFilePath, $petName, $fileType);
            }
            return $fileName;
        } 
    else {
            return false;
        }
    
}

function addWatermark($imagePath, $text, $fileType) {
    list($width, $height) = getimagesize($imagePath);
    $image = null;
    if ($fileType == 'jpeg' || $fileType == 'jpg') {
        $image = imagecreatefromjpeg($imagePath);
    } elseif ($fileType == 'png') {
        $image = imagecreatefrompng($imagePath);
    } elseif ($fileType == 'gif') {
        $image = imagecreatefromgif($imagePath);
    }

    $textColor = imagecolorallocate($image, 255, 255, 255); // White color
    $font ='assets/fonts/arial.ttf'; // Path to a TrueType font file
    imagettftext($image, 20, 0, 10, $height - 20, $textColor, $font, $text);

    imagejpeg($image, $imagePath, 90); // Save the watermarked image
    imagedestroy($image);
}
?>