<?php namespace App\Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Storage;

class FileHelper {

    /**
     * Get list of transaction files
     *
     * @return mixed
     */
    public static function getTransactionFiles()
    {
        $files = Storage::disk('transactions')->files();
        foreach ($files as $idx => $file) {
            if (substr($file, 0, 1) == '.')
                unset($files[$idx]);
        }
        return $files;
    }

    public static function upload(UploadedFile $file)
    {
        $transactions_path = storage_path() . '/transactions';
        $filename = date('YmdHis');
        $file->move($transactions_path, $filename);
    }

    public static function removeFile($filename)
    {
        $file_path = storage_path() . '/transactions/' . $filename;
        unlink($file_path);
    }

}