<?php
namespace Framework\Cache;

interface CacheInterface
{
    /**
     * save a Item
     *
     * @param mixed $value
     * @param string $key
     * @param $ttl
     *
     * @return bool
     */
    public function save($value, $key, $ttl);

    /**
     * load an Item
     *
     * @param string $key
     * @return mixed
     */
    public function load($key);

    /**
     * check a Item
     *
     * @param string $key
     * @return bool
     */
    public function test($key);

    /**
     * remove Item
     *
     * @param string $key
     * @return mixed
     */
    public function remove($key);

    /**
     * @param array $keys
     * @return mixed
     */
    public function loadItems($keys);

    /**
     * @param array $keyValuePairs
     * @param $ttl
     * @return mixed
     */
    public function saveItems($keyValuePairs, $ttl);

    /**
     * @param array $keys
     * @return mixed
     */
    public function testItems($keys);
}