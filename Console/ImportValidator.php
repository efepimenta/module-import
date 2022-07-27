<?php

namespace Fabio\Import\Console;

use Fabio\Import\Helper\FileHelper;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class ImportValidator
{
    private File $fileDriver;
    private FileHelper $fileHelper;
    private LoggerInterface $logger;

    /**
     * @param File $fileDriver
     * @param FileHelper $fileHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        File $fileDriver,
        FileHelper $fileHelper,
        LoggerInterface $logger
    ) {
        $this->fileDriver = $fileDriver;
        $this->fileHelper = $fileHelper;
        $this->logger = $logger;
    }

    /**
     * @param $type
     * @param $file
     * @return bool
     */
    public function validateInputFile($type, $file): bool
    {
        $exists = $this->fileExists($file);
        $mime = $this->fileHelper->validateMime($file);

        if ($type !== $this->fileHelper::VALID_MIME[$this->fileHelper->getMimeType($file)]) {
            return false;
        }

        return $exists && $mime;
    }

    /**
     * @param $file
     * @return bool
     */
    private function fileExists($file): bool
    {
        try {
            return $this->fileDriver->isExists($file) && $this->fileDriver->isFile($file);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return false;
        }
    }


}
