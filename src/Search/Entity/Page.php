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

class Page implements EntityInterface
{
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Cms\Model\ResourceModel\Page\Collection $pageCollection
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->resource = $resource;
        $this->pageCollection = $pageCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getMainTable() {
        $table = new Table($this->resource->getTableName("cms_page"));
        $table->addIDField("page_id");
        $table->addField("title");
        $table->addField("meta_keywords");
        $table->addField("meta_description");
        $table->addField("identifier");
        $table->addField("content_heading");
        $table->addField("content");
        $table->addField("meta_title");
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
        return "page";
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanName() {
        return "pages";
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityElementsByIds($ids){
        if (count($ids) == 0) {
            return array();
        }

        $pages = $this->pageCollection
            ->addFieldToSelect("*")
            ->addFieldToFilter('page_id', ['in' => $ids]);

        $items = array();
        /** @var \Magento\Cms\Model\Page $page */
        foreach($pages as $page) {
            $items[]= $this->getEntityElement($page);
        }
        return $items;
    }

    /**
     * @param \Magento\Cms\Model\Page $page
     * @return \OmegaCommerce\Api\Interfaces\EntityElementInterface
     */
    public function getEntityElement($page)
    {
        $apiPage = new \OmegaCommerce\Api\Entity\Page();
        $apiPage->setId($page->getId())
            ->setName($page->getTitle())
            ->setDescription($page->getData("description"))
            ->setUrl($this->urlBuilder->getUrl(null, ['_direct' => $page->getIdentifier()]))
//            ->setStoreId($page->getStoreId())
            ->setIsActive(true)
            ->setHandle($page->getIdentifier())
            ->setMetaTitle($page->getData("meta_title"))
            ->setMetaKeywords($page->getData("meta_keywords"))
            ->setMetaDescription($page->getData("meta_description"))
        ;

        return $apiPage;
    }
}

