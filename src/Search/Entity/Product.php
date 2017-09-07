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

class Product implements EntityInterface
{
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility
    )
    {
        $this->resource = $resource;
        $this->productCollection = $productCollection;
        $this->imageHelper = $imageHelper;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainTable() {
        $table = new Table($this->resource->getTableName("catalog_product_entity"));
        $table->addIDField("entity_id");
        $table->addField("sku");
        $table->setWhere("1 = 1");
        return $table;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedTables() {
        $names = [
            "catalog_product_entity_datetime",
            "catalog_product_entity_decimal",
            "catalog_product_entity_gallery",
            "catalog_product_entity_int",
//            "catalog_product_entity_media_gallery",
//            "catalog_product_entity_media_gallery_value",
//            "catalog_product_entity_media_gallery_value_to_entity",
//            "catalog_product_entity_media_gallery_value_video",
            "catalog_product_entity_text",
            "catalog_product_entity_tier_price",
            "catalog_product_entity_varchar",
        ];
        $tables = [];
        foreach($names as $name) {
            $table = new Table($this->resource->getTableName($name));
            $table->addIDField("entity_id");
            $table->addField("value");
            $tables[] = $table;
        }

        return $tables;
    }


    /**
     * {@inheritdoc}
     */
    public function getType() {
        return "product";
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanName() {
        return "products";
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityElementsByIds($ids){
        if (count($ids) == 0) {
            return array();
        }

        $products = $this->productCollection
            ->addFieldToSelect("*")
            ->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
            ->setVisibility($this->productVisibility->getVisibleInSiteIds())
            ->addFieldToFilter('entity_id', ['in' => $ids]);

        $items = array();
        /** @var \Magento\Catalog\Model\Product $product */
        foreach($products as $product) {
            $items[]= $this->getEntityElement($product);
        }
        return $items;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \OmegaCommerce\Api\Interfaces\EntityElementInterface
     */
    public function getEntityElement($product)
    {
        $apiProduct = new \OmegaCommerce\Api\Entity\Product();
        $apiProduct->setId($product->getId())
            ->setName($product->getName())
            ->setSku($product->getSku())
            ->setShortDescription($product->getData("short_description"))
            ->setDescription($product->getData("description"))
            ->setUrl($product->getProductUrl())
            ->setStoreId($product->getStoreId())
            ->setIsInStock($product->isInStock())
            ->setIsActive(true)
            ->setImageUrl($this->imageHelper->init($product, 'product_page_image_large')->setImageFile($product->getData('image'))->getUrl())
            ->setSmallImageUrl($this->imageHelper->init($product, 'product_page_image_large')->setImageFile($product->getData('small_image'))->getUrl())
            ->setThumbnailUrl($this->imageHelper->init($product, 'product_page_image_large')->setImageFile($product->getData('thumbnail'))->getUrl())
            ->setCreatedAt($product->getCreatedAt())
            ->setUpdatedAt($product->getUpdatedAt())
            ->setHandle($product->getUrlKey())
            ->setRegularPrice($product->getPrice())
            ->setFinalPrice($product->getFinalPrice())
            ->setMetaTitle($product->getData("meta_title"))
            ->setMetaKeywords($product->getData("meta_keywords"))
            ->setMetaDescription($product->getData("meta_description"))
        ;
        foreach ($product->getAttributes() as $attribute) {
            if (!$attribute->getIsUserDefined()) {
                continue;
            }
            $value = $product->getAttributeText($attribute->getAttributeCode());
            if (!$attribute->getBackendModel() == 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend') {
                $value = (string)$value;
            }
            if ($value == "") {
                continue;
            }
            $apiProduct->addAttribute($attribute->getAttributeCode(), $value);
        }
        return $apiProduct;
    }
}

