<?php

namespace Fabio\Import\Model;

class JsonProcessor extends AbstractProcessor implements ProcessorInterface
{

    /** @inheirtDoc  */
    public function getData($file): array
    {
        try {
            $data = json_decode(\Safe\file_get_contents($file), true);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return [];
        }
        return $this->checkStruct($data);
    }

    /**
     * @param $data
     * @return array
     */
    private function checkStruct($data): array
    {
        $headers = ['fname', 'lname', 'emailaddress'];

        foreach ($data as $customerData) {
            foreach ($headers as $header) {
                if (!isset($customerData[$header])) {
                    $this->logger->error('Error on check data structure');
                    return [];
                }
            }
        }
        return $data;
    }
}
