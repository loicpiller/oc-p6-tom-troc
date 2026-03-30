<?php

if (!function_exists('upload_image')) {

    function upload_image(array $imageFile, string $uploadDir, int $maxWidth = 1920, int $quality = 100): string
    {
        if ($quality < 1 || $quality > 100) {
            throw new \Exception("Quality parameter is incorrect");
        }
        
        $allowedTypes = ['image/jpeg', 'image/png'];
        
        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception($imageFile['error'], 500);
        }

        if (!is_uploaded_file($imageFile['tmp_name'])) {
            throw new \Exception("Invalid uploaded file", 400);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($imageFile['tmp_name']);
        if (!in_array($mimeType, $allowedTypes)) {
            throw new \Exception("Image file type in not allowed", 415);
        }

        $imageSize = getimagesize($imageFile['tmp_name']);
        if ($imageSize === false) {
            throw new \Exception("Invalid image file", 415);
        }
        [$originalWidth, $originalHeight] = $imageSize;

        $originalImage = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($imageFile['tmp_name']),
            'image/png'  => imagecreatefrompng($imageFile['tmp_name']),
            default      => false,
        };

        if ($originalImage === false) {
            throw new \Exception("Error while creating resource image");
        }

        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        if ($originalWidth > $maxWidth) {
            $ratio = $originalWidth / $originalHeight;
            $newWidth = $maxWidth;
            $newHeight = (int) ($newWidth / $ratio);
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($mimeType === 'image/png') {
            imagealphablending($newImage , false);
            imagesavealpha($newImage , true);
            $transparent = imagecolorallocatealpha($newImage , 255, 255, 255, 127);
            imagefilledrectangle($newImage , 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled(
            $newImage, $originalImage, 
            0, 0, 0, 0, 
            $newWidth, $newHeight, 
            $originalWidth, $originalHeight
        );

        $fileExtension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            default      => 'bin',
        };

        $publicImgDir = __DIR__ . '/../../public/img';
        $finalFilename =  $uploadDir . '/' . uniqid('img_', true) . '.' . $fileExtension;

        $save_success = match ($mimeType) {
            'image/jpeg' => imagejpeg($newImage, $publicImgDir . '/' . $finalFilename, $quality), 
            'image/png'  => imagepng($newImage, $publicImgDir . '/' . $finalFilename, (int) (9 - ($quality * 9 / 100))), 
            default      => false,
        };

        imagedestroy($originalImage);
        imagedestroy($newImage);

        if ($save_success) {
            return $finalFilename;
        } else {
            throw new \Exception("Error while saving image");
        }
    }
}
