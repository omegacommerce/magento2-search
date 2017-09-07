<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Entity;

use OmegaCommerce\Api\Entity\Table;
use OmegaCommerce\Api\Interfaces\EntityInterface;

class Store implements EntityInterface
{
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface  $storeManager,
        \Magento\Framework\Locale\Currency $currency,
        \Magento\Framework\Locale\ResolverInterface $localeResolver
    )
    {
        $this->resource = $resource;
        $this->storeManager = $storeManager;
        $this->currency = $currency;
        $this->localeResolver = $localeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainTable() {
        $table = new Table($this->resource->getTableName("store"));
        $table->addIDField("store_id");
        $table->addField("name");
        $table->setWhere("store_id > 0");
        return $table;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedTables() {
        return array();
    }


    /**
     * {@inheritdoc}
     */
    public function getType() {
        return "store";
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanName() {
        return "stores";
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityElementsByIds($ids){
        if (count($ids) == 0) {
            return array();
        }
        $stores = $this->storeManager->getStores();

        $items = array();
        /** @var \Magento\Store\Model\Store  $store */
        foreach($stores as $store) {
            $items[]= $this->getEntityElement($store);
        }
        return $items;
    }

    /**
     * @param \Magento\Store\Model\Store  $store
     * @return \OmegaCommerce\Api\Interfaces\EntityElementInterface
     */
    public function getEntityElement($store)
    {
        $apiStore = new \OmegaCommerce\Api\Entity\Store();
        $apiStore->setId($store->getId())
            ->setName($store->getName())
            ->setCurrency($store->getDefaultCurrencyCode())
            ->setCurrencyFormat($this->currency->getCurrency( $store->getDefaultCurrencyCode())->toString())
            ->setLocale($this->localeResolver->getLocale())
            ->setIsActive($store->isActive())
            ->setBaseUrl($store->getBaseUrl())
        ;
        return $apiStore;
    }
}

