<?php
declare(strict_types=1);

namespace App\cache;

class Cache
{
	protected $storage;

	public function __construct()
    {
        $this->storage = new \Redis();
        $this->storage->connect('127.0.0.1');
    }

    public function instance()
    {
	    return $this->storage;
    }

    public function set($key, $value)
    {
        $keys = $this->storage->keys($key);

        $this->storage->del($keys);

    }

    public function incr($key, $name)
    {
        $this->storage->hIncrBy($key, $name, 1);
    }

    public function delete($key)
    {
        $keys = $this->storage->keys($key);

        $this->storage->del($keys);

    }

    public function getAll($mask)
    {
        $keys = $this->storage->keys($mask);
        $result = [];

        foreach ($keys as $key) {
            $result[] = $this->storage->hGetAll($key);
        }
        return $result;
    }

}