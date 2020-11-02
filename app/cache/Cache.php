<?php
declare(strict_types=1);

namespace App\cache;

use ORM;

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

}