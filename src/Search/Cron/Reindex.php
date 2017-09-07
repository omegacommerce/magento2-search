<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace Magento\Catalog\Cron;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ObjectManager;

class Reindex
{
    public function __construct(
        \OmegaCommerce\Search\Model\Indexer $indexer
    ) {
        $this->apiIndexer = $indexer->getApiIndexer();
    }


    /**
     * Reindex changed items by cron
     *
     * @return void
     */
    public function execute()
    {
        $limit = 200;
        foreach ($this->apiIndexer->getEntities() as $entity) {
            $this->apiIndexer->refreshStatus($entity);
            $this->apiIndexer->removeEntity($entity, $limit);
            $this->apiIndexer->reindexEntity($entity, $limit);
        }
    }
}
