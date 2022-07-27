<?php

namespace Fabio\Import\Model;

use Fabio\Import\Model\Services\CustomerService;
use Magento\Framework\File\Csv;
use Psr\Log\LoggerInterface;

class CsvProcessor extends AbstractProcessor implements ProcessorInterface
{

    private Csv $csvParser;

    /**
     * @param CustomerService $customerService
     * @param LoggerInterface $logger
     * @param Csv $csvParser
     */
    public function __construct(
        CustomerService $customerService,
        LoggerInterface $logger,
        Csv $csvParser
    )
    {
        parent::__construct($customerService, $logger);
        $this->csvParser = $csvParser;
    }

    /** @inheirtDoc */
    public function getData($file): array
    {
        $this->csvParser->setDelimiter(',');
        try {
            $allCsv = $this->csvParser->getData($file);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return [];
        }

        $data = [];
        $header = array_shift($allCsv);
        foreach ($allCsv as $item) {
            $data[] = [
                'fname' => $item[$this->orderHeader($header, 'fname')],
                'lname' => $item[$this->orderHeader($header, 'lname')],
                'emailaddress' => $item[$this->orderHeader($header, 'emailaddress')]
            ];
        }
        return $data;
    }

    private function orderHeader($header, $field): int
    {
        $index = array_search($field, $header);
        if ($index === false) {
            $this->logger->error("Csv header is invalid");
            throw new \Exception("Csv header is invalid");
        }
        return $index;
    }
}
