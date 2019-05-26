<?php
namespace Framework\Cache;


use Zend\Cache\StorageFactory;

class CacheAdapter implements CacheInterface
{
    /**
     * @var \Zend\Cache\Storage\StorageInterface
     */
    public $storageFactory;

    public function __construct($cfg = null)
    {
        $this->storageFactory = StorageFactory::factory($cfg);
    }

    public function save($value, $key, $ttl = 7200)
    {
        $this->storageFactory->getOptions()->setTtl($ttl);
        $this->storageFactory->addItem($key, $value);
    }

    public function test($key)
    {
        return $this->storageFactory->hasItem($key);
    }

    public function load($key)
    {
        return $this->storageFactory->getItem($key);
    }

    public function remove($key)
    {
        $this->storageFactory->removeItem($key);
    }

    public function loadItems($keys)
    {
        return $this->storageFactory->getItems($keys);
    }

    public function saveItems($keyValuePairs, $ttl = 7200)
    {
        $this->storageFactory->getOptions()->setTtl($ttl);
        $this->storageFactory->addItems($keyValuePairs);
    }

    public function testItems($keys)
    {
        return $this->storageFactory->hasItems($keys);
    }

    public function checkAndSave($key, $value, $token)
    {
        return $this->storageFactory->checkAndSetItem($token, $key, $value);
    }

}