<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use OmegaCommerce\Api\Iframe;

class Application extends Template
{
    /**
     * @var \OmegaCommerce\Api\Iframe
     */
    protected $iframe;

    public function __construct(
        Iframe $iframe,
        Template\Context $context,
        array $data = []
    ) {
        $this->iframe = $iframe;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        return $this->iframe->toHtml();
    }
}
