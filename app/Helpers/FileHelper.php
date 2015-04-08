<?php namespace App\Helpers;

use App\Dto\Metadata;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Storage;

class FileHelper {

    /**
     * Get list of transaction files
     *
     * @return array
     */
    public static function getTransactionFiles()
    {
        $new_list = [];
        $files = Storage::disk('transactions')->files();
        foreach ($files as $idx => $file) {
            if (substr($file, 0, 1) == '.' || strpos($file, 'metadata') !== false)
                unset($files[$idx]);
            else{
                $new_list[] = ['filename' => $file, 'metadata' => self::getMetadata($file)];
            }
        }
        return $new_list;
    }

    /**
     * @param $filename
     * @return Metadata
     */
    public static function getMetadata($filename)
    {
        $metadataFilename = self::getMetadataFilename($filename);
        $metadataFileExists = Storage::disk('transactions')->exists($metadataFilename);

        if($metadataFileExists)
        {
            $metadata = json_decode(Storage::disk('transactions')->get($metadataFilename), true);
            return new Metadata(
                $metadata['start_line'],
                $metadata['source'],
                $metadata['separator'],
                $metadata['date_format'],
                $metadata['columns']
            );
        }

        throw new FileNotFoundException($metadataFilename);
    }

    public static function getMetadataFilename($filename)
    {
        return 'metadata/' . $filename;
    }

    /**
     * @param UploadedFile $file
     * @param array $metadata
     */
    public static function upload(UploadedFile $file, array $metadata)
    {
        $transactions_path = storage_path() . '/transactions';
        $filename = date('YmdHis');
        $file->move($transactions_path, $filename);

        Storage::disk('transactions')->put(self::getMetadataFilename($filename), json_encode($metadata));
    }

    public static function removeFile($filename)
    {
        Storage::disk('transactions')->delete([$filename, self::getMetadataFilename($filename)]);
    }
}