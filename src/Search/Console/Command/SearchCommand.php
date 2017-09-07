<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Store\Model\Store;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command for executing cron jobs
 */
class SearchCommand extends Command
{
    const CLEAR = "clear";

    public function __construct(
        \Magento\Framework\App\State $state,
        \OmegaCommerce\Search\Model\Indexer $indexer
    ) {
        $this->state = $state;
        $this->apiIndexer = $indexer->getApiIndexer();
        parent::__construct();
    }
    /**
     *
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::CLEAR,
                null,
                InputOption::VALUE_NONE,
                'Clear all before reindexing'
            ),
        ];

        $this->setName('omega:reindex')
            ->setDescription('Runs omegacommerce reindexing')
            ->setDefinition($options);
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();

        //area code is set when we run php -f bin/magento setup:upgrade
        $this->state->setAreaCode('frontend');

        if ($options[self::CLEAR]) {
            $tries = 1;
            while (true) {
                try {
                    $this->apiIndexer->clean();
                    break;
                } catch (\OmegaCommerce\Api\Exception $e) {
                    $output->writeln("<error>" . $e->getMessage() . " Trying again ($tries)...</error>");
                    sleep(5);
                    $tries++;
                    if ($tries > 10) {
                        exit(1);
                    }
                    continue;
                }
            }
        }


        $i = 1;
        $tries = 1;
        $limit = 100;
        foreach ($this->apiIndexer->getEntities() as $entity) {
            $output->writeln( "<info>"."Reindexing ".$entity->getHumanName()."...</info>");

            $this->apiIndexer->refreshStatus($entity);

            while (true) {
                try {
                    $count = $this->apiIndexer->removeEntity($entity, 500);
                    if ($count == 0) {
                        break;
                    }
                } catch (\OmegaCommerce\Api\Exception $e) {
                    $output->writeln("<error>" . $e->getMessage() . " Trying again ($tries)...</error>");
                    sleep(5);
                    $tries++;
                    if ($tries > 10) {
                        exit(1);
                    }
                    continue;
                }
                $tries = 1;
            }


            $totalNumber = $this->apiIndexer->reindexQueueLength($entity);
            if ($totalNumber == 0 && $entity->getMainTable()) {
                $output->writeln( "everything is updated");
                continue;
            }
            while (true) {
                try {
                    $this->apiIndexer->reindexEntity($entity, $limit);
                } catch (\OmegaCommerce\Api\Exception $e) {
                    $output->writeln("<error>" . $e->getMessage() . " Trying again ($tries)...</error>");
                    sleep(5);
                    $tries++;
                    if ($tries > 10) {
                        exit(1);
                    }
                    continue;
                }
                $tries = 1;
                $currentTotal = $i * $limit;
                if ($currentTotal > $totalNumber) {
                    $currentTotal = $totalNumber;
                }
                if ($currentTotal >= $totalNumber) {
                    break;
                }
                $i++;
            }
        }
        $output->writeln('<info>' . 'Reindexing is completed!' . '</info>');
    }
}