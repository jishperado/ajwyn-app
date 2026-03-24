<?php

namespace App\Libraries;

class FileUploader
{
    public function upload($fileInputName, $uploadFolder )
    {
     
        $file = \Config\Services::request()->getFile($fileInputName);


        if ($file->isValid() && !$file->hasMoved()) {
            $filename = date('YmdHis') . '-' . $file->getRandomName();
            $path = getcwd() . '/' . trim($uploadFolder, '/') . '/';

      
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

       
            if ($file->move($path, $filename)) {
                return $filename; 
            }
        }

        return false; 
    }
}
