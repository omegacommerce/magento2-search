<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */

namespace OmegaCommerce\Api\Interfaces;


interface DBInterface
{
    /**
     * @param string $table
     * @return string
     */
    function getTableName($table);

    /**
     * @param string $sql
     * @return void
     */
    function query($sql);


    /**
     * @param string $sql
     * @return array
     */
    function fetchCol($sql);//get_col
}