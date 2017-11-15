<?php
namespace SwayOleg\ApiBulk\Api;

interface ProductInterface
{
    /**
     * Returns Status of import result
     *
     * @api
     * @param mixed $data Data with products.
     * @return string[] Status of import result.
     */
    public function saveProducts($data = []);
}