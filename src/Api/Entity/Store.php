<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Api\Entity;

class Store implements \OmegaCommerce\Api\Interfaces\EntityElementInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id){
        $this->data['id'] = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name){
        $this->data['name'] = $name;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setBaseUrl($v){
        $this->data['base_url'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setCurrency($v){
        $this->data['currency'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setCurrencyFormat($v){
        $this->data['currency_format'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setLocale($v){
        $this->data['locale'] = $v;
        return $this;
    }

    /**
     * @param bool $v
     * @return $this
     */
    public function setIsActive($v){
        $this->data['is_active'] = $v;
        return $this;
    }
}
