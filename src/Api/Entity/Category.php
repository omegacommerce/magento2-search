<?php
/**
 * Omega Commerce
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: 2017 Omega Commerce LLC https://omegacommerce.com
 */
namespace OmegaCommerce\Api\Entity;

class Category implements \OmegaCommerce\Api\Interfaces\EntityElementInterface
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
    public function setDescription($v){
        $this->data['description'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setUrl($v){
        $this->data['url'] = $v;
        return $this;
    }

    /**
     * @param int $v
     * @return $this
     */
    public function setStoreId($v){
        $this->data['store_id'] = $v;
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

    /**
     * @param string $v
     * @return $this
     */
    public function setImageUrl($v){
        $this->data['image_url'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setHandle($v){
        $this->data['handle'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setMetaTitle($v){
        $this->data['meta_title'] = $v;
        return $this;
    }

    /**
     * @param string $v
     * @return $this
     */
    public function setMetaKeywords($v){
        $this->data['meta_keywords'] = $v;
        return $this;
    }


    /**
     * @param string $v
     * @return $this
     */
    public function setMetaDescription($v){
        $this->data['meta_description'] = $v;
        return $this;
    }

//    /**
//     * @param string $v
//     * @return $this
//     */
//    public function setSmallImageUrl($v){
//        $this->data['small_image_url'] = $v;
//        return $this;
//    }
//
//    /**
//     * @param string $v
//     * @return $this
//     */
//    public function setThumbnailUrl($v){
//        $this->data['thumbnail_url'] = $v;
//        return $this;
//    }
//
//    /**
//     * @param string $v
//     * @return $this
//     */
//    public function setCreatedAt($v){
//        $this->data['created_at'] = $v;
//        return $this;
//    }
//
//    /**
//     * @param string $v
//     * @return $this
//     */
//    public function setUpdatedAt($v){
//        $this->data['updated_at'] = $v;
//        return $this;
//    }
}
