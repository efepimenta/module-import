<?php

namespace Fabio\Import\Console;

use Fabio\Import\Model\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;

interface ProcessorFactoryInterface
{
    /**
     * @param string $type
     * @return ProcessorInterface|null
     * @throws LocalizedException
     */
    public function getProcessorInstance(string $type): ?ProcessorInterface;
}
