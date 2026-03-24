<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;


class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
        //Custom Rule Added By cllit
// In App\Validation\Validation.php or any custom validation class
public function imageUpload(array $size = [], bool $isRequired = false): array
{
    $file = service('request')->getFile('img');

    $ruleList = [];
    $errors = [];

    // Case 1: Image is required — always validate upload
    if ($isRequired) {
        $ruleList[] = 'uploaded[img]';
        $errors['uploaded'] = 'Image is required.';
    }

    // Case 2: Image is optional but was uploaded — still validate it
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $ruleList[] = 'is_image[img]';
        $ruleList[] = 'mime_in[img,image/jpg,image/jpeg,image/png,image/gif]';

        $errors['is_image'] = 'Only image files are allowed.';
        $errors['mime_in'] = 'Only JPG, PNG, JPEG, or GIF formats are allowed.';

        if (!empty($size['width']) && !empty($size['height'])) {
            $ruleList[] = 'check_image_size[' . $size['width'] . ',' . $size['height'] . ']';
            $errors['check_image_size'] = 'Image must be exactly ' . $size['width'] . 'x' . $size['height'] . ' pixels.';
        }
    }

    // Return rules only if there's anything to validate
    if (!empty($ruleList)) {
        return [
            'img' => [
                'rules' => implode('|', $ruleList),
                'errors' => $errors,
            ],
        ];
    }

    // Image is not required and not uploaded — no validation
    return [];
}




    // --------------------------------------------------------------------
}
