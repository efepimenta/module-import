<?php

namespace Fabio\Import\Console;

use Fabio\Import\Helper\FileHelper;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;

class CustomerImport extends Command
{

    protected const COMMAND_TYPE = 'command-type';
    protected const FILE_NAME = 'filename';

    private ImportValidator $validator;
    private ProcessorFactoryInterface $processorFactory;
    private FileHelper $fileHelper;
    private LoggerInterface $logger;

    /**
     * @param ImportValidator $validator
     * @param ProcessorFactoryInterface $processorFactory
     * @param FileHelper $fileHelper
     * @param LoggerInterface $logger
     * @param string|null $name
     */
    public function __construct(
        ImportValidator $validator,
        ProcessorFactoryInterface $processorFactory,
        FileHelper $fileHelper,
        LoggerInterface $logger,
        string $name = null
    ) {
        parent::__construct($name);
        $this->validator = $validator;
        $this->processorFactory = $processorFactory;
        $this->fileHelper = $fileHelper;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this->setName('customer:import');
        $this->setDescription('This is my first console command.');

        $this->addArgument(
            self::COMMAND_TYPE,
            InputArgument::REQUIRED,
            'The command type'
        );
        $this->addArgument(
            self::FILE_NAME,
            InputArgument::REQUIRED,
            'The file name'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Start import');
        $importType = $input->getArgument(self::COMMAND_TYPE);
        $filemane = $input->getArgument(self::FILE_NAME);

        if (!$importType) {
            $this->logger->info('Provide type is unknown');
            $output->writeln('<error>Provide type is unknown</error>');
            return 1;
        }

        if (!in_array($importType, ['json', 'csv'])) {
            $this->logger->info('Incorrect import type. Must be json ou csv');
            $output->writeln('<error>Incorrect import type. Must be json ou csv</error>');
            return 1;
        }

        if (!$this->validator->validateInputFile($importType, $filemane)) {
            $this->logger->info('Provide file is invalid');
            $output->writeln('<error>Provide file is invalid</error>');
            return 1;
        }

        try {
            $processorInstance = $this->processorFactory->getProcessorInstance($importType);
        } catch (LocalizedException $exception) {
            $this->logger->info($exception->getMessage());
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
            return 1;
        }

        $data = $processorInstance->getData($this->fileHelper->getFullPath($filemane));
        if (!$data) {
            $this->logger->info('Data is empty or invalid');
            $output->writeln('<error>Data is empty or invalid</error>');
            return 1;
        }

        try {
            $processorInstance->execute($data);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
            return 1;
        }
        $this->logger->info('End import');
        return 0;
    }

}
