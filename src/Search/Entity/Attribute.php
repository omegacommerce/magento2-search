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

class Attribute implements EntityInterface
{
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory  $attributeCollectionFactory
    )
    {
        $this->resource = $resource;
        $this->collectionFactory = $attributeCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainTable() {
        $table = new Table($this->resource->getTableName("eav_attribute"));
        $table->addIDField("attribute_id");
        $table->addField("attribute_code");
        $table->addField("frontend_label");
        $table->setWhere("1 = 1");
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
        return "attribute";
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanName() {
        return "attributes";
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityElementsByIds($ids){
        if (count($ids) == 0) {
            return array();
        }
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $collection */
        $collection = $this->collectionFactory
            ->create()
            ->addFilter('is_user_defined', true)
            ->addFieldToFilter('main_table.attribute_id', ['in' => $ids])
            ->load();

        $items = array();
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
        foreach($collection as $attribute) {
            $items[]= $this->getEntityElement($attribute);
        }
        return $items;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return \OmegaCommerce\Api\Interfaces\EntityElementInterface
     */
    public function getEntityElement($attribute)
    {
        $apiAttribute = new \OmegaCommerce\Api\Entity\Attribute();
        $apiAttribute->setId($attribute->getId())
            ->setName($attribute->getDefaultFrontendLabel())
            ->setCode($attribute->getAttributeCode())
        ;
        return $apiAttribute;
    }
}

