<?php

namespace Fabio\Import\Helper;

use Magento\Framework\Filesystem\Driver\File;

class FileHelper
{

    const VALID_MIME = [
        'text/csv' => 'csv',
        'application/json' => 'json'
    ];
    private File $fileDriver;

    /**
     * @param File $fileDriver
     */
    public function __construct(
        File $fileDriver
    )
    {
        $this->fileDriver = $fileDriver;
    }

    /**
     * @param $file
     * @return bool|string
     */
    public function getFullPath($file): bool|string
    {
        return $this->fileDriver->getRealPath($file);
    }

    /**
     * @param $file
     * @return bool|string
     */
    public function getMimeType($file): bool|string
    {
        return mime_content_type($file);
    }

    /**
     * @param $file
     * @return bool
     */
    public function validateMime($file): bool
    {
        return array_key_exists($this->getMimeType($file), self::VALID_MIME);
    }

}
