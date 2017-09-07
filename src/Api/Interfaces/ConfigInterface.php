<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */

namespace OmegaCommerce\Api\Interfaces;


interface ConfigInterface
{
    /**
     * @return string
     */
    function getPlatform();

    /**
     * @return string
     */
    function getVersion();

    /**
     * @return string
     */
    function getStoreBaseURL();

    /**
     * @param string $path
     * @return string
     */
    function getValue($path);

    /**
     * @param string $path
     * @param string $value
     * @return void
     */
    function saveValue($path, $value);


    /**
     * @param string $path
     * @return string
     */
    function getEncryptedValue($path);

    /**
     * @param string $path
     * @param string $value
     * @return void
     */
    function saveValueEncrypted($path, $value);
}