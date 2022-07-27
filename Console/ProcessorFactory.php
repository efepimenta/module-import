<?php

namespace Fabio\Import\Console;

use Fabio\Import\Model\ProcessorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

class ProcessorFactory implements ProcessorFactoryInterface
{

    private ObjectManagerInterface $objectManager;
    private LoggerInterface $logger;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        LoggerInterface $logger
    )
    {
        $this->objectManager = $objectManager;
        $this->logger = $logger;
    }

    /**
     * @param $type
     * @return ProcessorInterface|null
     * @throws LocalizedException
     */
    public function getProcessorInstance($type): ?ProcessorInterface
    {
        try {
            $instance = $this->objectManager->create('Fabio\Import\Model\\' . ucfirst($type) . 'Processor');
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            throw new LocalizedException(__('Error creating Processor class'));
        }
        if ($instance instanceof ProcessorInterface) {
            return $instance;
        }
        return null;
    }

}
