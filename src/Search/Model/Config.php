<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem;

class Config implements \OmegaCommerce\Api\Interfaces\ConfigInterface
{
    protected $resourceConfig;
    protected $scopeConfig;
    protected $encryptor;


    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConfig = $resourceConfig;
        $this->cacheTypeList = $cacheTypeList;
        $this->encryptor = $encryptor;
    }

    /**
     * @param string $path
     * @return string
     */
    function getValue($path)
    {
        return $this->scopeConfig->getValue($path);
    }

    /**
     * @param string $path
     * @param string $value
     * @return void
     */
    function saveValue($path, $value)
    {
        $this->resourceConfig->saveConfig($path, $value, 'default', 0);
        $this->cacheTypeList->cleanType('config');

    }

    /**
     * @param string $path
     * @return string
     */
    function getEncryptedValue($path)
    {
        return $this->encryptor->decrypt($this->getValue($path));
    }

    /**
     * @param string $path
     * @param string $value
     * @return void
     */
    function saveValueEncrypted($path, $value)
    {
        $this->saveValue($path, $this->encryptor->encrypt($value));
    }

    /**
     * @return string
     */
    function getVersion()
    {
        return "1.0.0";
    }

    /**
     * @return string
     */
    function getStoreBaseURL()
    {
        return "http://fafa";
    }

    /**
     * @return string
     */
    function getPlatform()
    {
        return "magento2";
    }
}