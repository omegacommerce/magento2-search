<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Model;


class Indexer
{

    public function __construct(
        \OmegaCommerce\Api\Indexer $apiIndexer,
        \OmegaCommerce\Search\Entity\Store $storeEntity,
        \OmegaCommerce\Search\Entity\Attribute $attributetEntity,
        \OmegaCommerce\Search\Entity\Product $productEntity,
        \OmegaCommerce\Search\Entity\Category $categoryEntity,
        \OmegaCommerce\Search\Entity\Page $pageEntity
    ) {
        $this->apiIndexer = $apiIndexer;

        $this->apiIndexer->registerEntity($storeEntity);
        $this->apiIndexer->registerEntity($attributetEntity);
        $this->apiIndexer->registerEntity($productEntity);
        $this->apiIndexer->registerEntity($categoryEntity);
        $this->apiIndexer->registerEntity($pageEntity);
    }

    /**
     * @return \OmegaCommerce\Api\Indexer
     */
    public function getApiIndexer(){
        return $this->apiIndexer;
    }
}