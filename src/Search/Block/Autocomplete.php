<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Block;

use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use OmegaCommerce\Search\Model\Config;

class Autocomplete extends Template
{
    public function __construct(
        \OmegaCommerce\Api\Config $config,
        Template\Context $context,
        array $data = []
    )
    {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function _toHtml() {
        $storeId = $this->_storeManager->getStore()->getId();
        $id = $this->config->getID();
        if (!$id) {
            return "";
        }
        $url = $this->config->getBaseApiUrl();
        $url = str_replace('https://', '', str_replace('http://', '', $url));
        $url = rtrim($url, '/');
        $resultsUrl = $this->getUrl("omegasearch");
        return <<<HTML
<script data-cfasync="false" src="//{$url}/instant/initjs?ID={$id}&seid={$storeId}"></script>
<script>'' +
    (function () {
        var endpoint = '{$url}';
        var protocol= ("https:" === document.location.protocol ? "https://" : "http://");
        //url must have the same protocol as page. otherwise js errors possible.
        var url = '{$resultsUrl}'
        url = url.replace("https://", protocol)
        url = url.replace("http://", protocol)
        if (typeof window.OMEGA_CONFIG == "undefined") {
            window.OMEGA_CONFIG = {}
        }
        window.OMEGA_CONFIG.searchResultUrl = url
    })();
</script>
HTML;
    }
}
