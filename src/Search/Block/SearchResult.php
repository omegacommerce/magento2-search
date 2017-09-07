<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Block;

use Magento\Framework\View\Element\Template;


class SearchResult extends Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Page\Config $pageConfig,
        $data = array()
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->pageConfig = $pageConfig;

        parent::__construct($context, $data);
    }
    /**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set('Search Results');
        return parent::_prepareLayout();
    }
}