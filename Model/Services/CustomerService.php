<?php

namespace Fabio\Import\Model\Services;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Psr\Log\LoggerInterface;

class CustomerService
{
    private CustomerInterfaceFactory $customerFactory;
    private CustomerRepositoryInterface $customerRepository;
    private LoggerInterface $logger;

    /**
     * @param CustomerInterfaceFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerInterfaceFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        LoggerInterface $logger
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    /**
     * @param $data
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function saveCustomer($data): void
    {
        try {
            $customer = $this->customerFactory->create();
            $customer->setFirstname($data['fname']);
            $customer->setLastname($data['lname']);
            $customer->setEmail($data['emailaddress']);
            if ($customerExists = $this->customerExists($data['emailaddress'])) {
                $customer->setId($customerExists->getId());
                $customer->setWebsiteId($customerExists->getWebsiteId());
            }
            $this->customerRepository->save($customer);
            $this->logger->info("Customer {$customer->getEmail()} saved/updated");
        } catch (\Exception $exception) {
            $this->logger->error("Error saving {$customer->getEmail()}");
            throw $exception;
        }
    }

    /**
     * @param $email
     * @return bool|CustomerInterface
     */
    private function customerExists($email): bool|CustomerInterface
    {
        try {
            return $this->customerRepository->get($email);
        } catch (\Exception $exception) {
            return false;
        }
    }
}
