<?php


namespace App\cache;


class Redirect extends Cache
{

    /**
     * БАЗА => ХЭШ
     * Сохранить всю базу в хэш, т.к. там все равно пространства на 4 миллиарда записей
     */
    public function rebuild()
    {
        $this->delete(REDIS_HASH . ':*');
        $links = \ORM::for_table(TABLE)->findMany();
        foreach ($links as $link){
            $this->storage->hMSet(REDIS_HASH . ':' . $link->slug,
                [
                    'url' => $link->url,
                    'hits' => $link->hits
                ]
            );
        }
    }

    /**
     * ХЭШ => БАЗА
     * Обновление хитов
     */
    public function save()
    {
        $links = \ORM::for_table(TABLE)->findMany();
        foreach ($links as $link){
            $link->hits = $this->storage->hGet(REDIS_HASH . ':' . $link->slug, 'hits');
            $link->save();
        }
    }

    public function hit($slug)
    {
        $this->incr(REDIS_HASH . ':' . $slug, 'hits');
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