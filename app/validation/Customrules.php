<?php

namespace App\Validation;

class Customrules
{
    public function check_image_size($str, string $params, array $data, string &$error = null): bool
    {
        $file = service('request')->getFile('img');

        if (!$file || !$file->isValid()) {
            $error = 'Invalid file.';
            return false;
        }

        $imageSize = getimagesize($file->getTempName());

        if (!$imageSize) {
            $error = 'Cannot read image dimensions.';
            return false;
        }

        [$expectedWidth, $expectedHeight] = explode(',', $params);

        if ($imageSize[0] != $expectedWidth || $imageSize[1] != $expectedHeight) {
            $error = "Image must be exactly {$expectedWidth}x{$expectedHeight} pixels.";
            return false;
        }

        return true;
    }
}
