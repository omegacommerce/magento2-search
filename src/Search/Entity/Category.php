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

class Category implements EntityInterface
{
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection,
        \Magento\Catalog\Helper\Image $imageHelper
    )
    {
        $this->resource = $resource;
        $this->categoryCollection = $categoryCollection;
        $this->imageHelper = $imageHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainTable() {
        $table = new Table($this->resource->getTableName("catalog_category_entity"));
        $table->addIDField("entity_id");
        $table->addField("path");
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
        return "category";
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanName() {
        return "categories";
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityElementsByIds($ids){
        if (count($ids) == 0) {
            return array();
        }

        $categorys = $this->categoryCollection
            ->addFieldToSelect("*")
            ->addFieldToFilter('entity_id', ['in' => $ids]);

        $items = array();
        /** @var \Magento\Catalog\Model\Category $category */
        foreach($categorys as $category) {
            $items[]= $this->getEntityElement($category);
        }
        return $items;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @return \OmegaCommerce\Api\Interfaces\EntityElementInterface
     */
    public function getEntityElement($category)
    {
        $apiCategory = new \OmegaCommerce\Api\Entity\Category();
        $apiCategory->setId($category->getId())
            ->setName($category->getName())
            ->setDescription($category->getData("description"))
            ->setUrl($category->getUrl())
            ->setStoreId($category->getStoreId())
            ->setIsActive(true)
            ->setImageUrl($category->getImageUrl())
            ->setMetaTitle($category->getData("meta_title"))
            ->setMetaKeywords($category->getData("meta_keywords"))
            ->setMetaDescription($category->getData("meta_description"))
        ;

        return $apiCategory;
    }
}

