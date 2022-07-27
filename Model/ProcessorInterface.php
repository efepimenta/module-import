<?php

namespace Fabio\Import\Model;

use Magento\Framework\Exception\LocalizedException;

interface ProcessorInterface
{

    /**
     * @param array $data
     * @return void
     * @throws LocalizedException
     */
    public function execute(array $data): void;

    /**
     * @param $file
     * @return array
     */
    public function getData($file): array;
}
