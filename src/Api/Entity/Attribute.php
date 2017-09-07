<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Api\Entity;

class Attribute implements \OmegaCommerce\Api\Interfaces\EntityElementInterface
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
    public function setCode($v){
        $this->data['code'] = $v;
        return $this;
    }
}
