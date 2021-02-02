<?php


namespace App\interfaces;

use App\cache\Request as RequestCache;


class Analytics
{
    private $mask;

    public function __construct()
    {
        $this->mask = $_POST['mask'] ?? '*';
    }

    public function get()
    {
        return json_encode(array_merge($this->getFromDB(), $this->getFromRedis()));
    }

    private function getFromRedis()
    {
        $cache = new RequestCache();
        return $cache->getAll(REDIS_REQUEST_HASH . ':' . $this->mask);
    }

    private function getFromDB()
    {
        $ids = $this->getIds();
        if(!$ids)
            return [];
        $anyMatch = [];
        foreach ($ids as $item) {
            $anyMatch[] = ['id' => $item];
        }

        return \ORM::for_table(TABLE_REQUESTS)
            ->where_any_is($anyMatch)
            ->find_array();
    }

    private function getIds()
    {
        $columns_to_check = [
            'date',
            'original',
            'slug'
        ];

        $params = explode('*', trim($this->mask, '*'));

        $ids = [];

        foreach ($params as $param) {
            $anyMatch = [];
            foreach ($columns_to_check as $item) {
                $anyMatch[] = [$item => '%' . $param . '%'];
            }

            $matches = \ORM::for_table(TABLE_REQUESTS)
                ->select('id')
                ->where_any_is($anyMatch, 'LIKE')
                ->find_array();

            foreach ($matches as $match)
            {
                $ids[$param][] = $match['id'];
            }
        }

        $vals = array_values($ids);

        if(count($vals) == 1)
            return $vals[0];

        return array_intersect(...$vals);
    }
}
