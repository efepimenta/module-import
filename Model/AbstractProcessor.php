<?php

namespace Fabio\Import\Model;

use Fabio\Import\Model\Services\CustomerService;
use Psr\Log\LoggerInterface;

abstract class AbstractProcessor
{

    private CustomerService $customerService;
    protected LoggerInterface $logger;

    /**
     * @param CustomerService $customerService
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerService $customerService,
        LoggerInterface $logger
    )
    {
        $this->customerService = $customerService;
        $this->logger = $logger;
    }

    /** @inheirtDoc */
    public function execute(array $data): void
    {
        foreach ($data as $customerData) {
            $this->customerService->saveCustomer($customerData);
        }
    }
}
