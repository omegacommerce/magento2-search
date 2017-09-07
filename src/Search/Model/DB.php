<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Search\Model;


class DB implements \OmegaCommerce\Api\Interfaces\DBInterface
{

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @param string $table
     * @return string
     */
    function getTableName($table)
    {
        return $this->resource->getTableName($table);
    }

    /**
     * @param string $sql
     * @return void
     */
    function query($sql)
    {
       $this->resource->getConnection()->query($sql);
    }

    /**
     * @param string $sql
     * @return array
     */
    function fetchCol($sql)
    {
        return $this->resource->getConnection()->fetchCol($sql);
    }
}