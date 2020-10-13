<?php
declare(strict_types=1);

namespace App;

use ORM;

class Cache
{
	private $storage;

	public function __construct()
    {
        $this->storage = new \Redis();
        $this->storage->connect('127.0.0.1');
    }

    public function instance()
    {
	    return $this->storage;
    }

    /**
     * Сохранить всю базу в хэш, т.к. там все равно пространства на 4 миллиарда записей
     */
    public function rebuild()
    {
        $links = ORM::for_table(TABLE)->findMany();
        foreach ($links as $link){
            $this->storage->del(REDIS_HASH . ':' . $link->slug);
            $this->storage->hMSet(REDIS_HASH . ':' . $link->slug,
                [
                    'url' => $link->url,
                    'hits' => $link->hits
                ]
            );
        }
    }

    /**
     * Обновление хитов
     */
    public function save()
    {
        $links = ORM::for_table(TABLE)->findMany();
        foreach ($links as $link){
            $link->hits = $this->storage->hGet(REDIS_HASH . ':' . $link->slug, 'hits');
            $link->save();
        }
    }

    public function hit($slug)
    {
        $this->storage->hIncrBy(REDIS_HASH . ':' . $slug, 'hits', 1);
    }

    public function check($slug)
    {
        return $this->storage->hGet(REDIS_HASH . ':' . $slug, 'url');
    }

    public function append($link)
    {
        $this->storage->hMSet(REDIS_HASH . ':' . $link->slug,
            [
                'url' => $link->url,
                'hits' => $link->hits
            ]
        );
    }

}