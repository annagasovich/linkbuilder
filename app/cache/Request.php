<?php


namespace App\cache;


class Request extends Cache
{

    public function saveReq($key, $data)
    {
        $this->storage->hMSet($key, $data);
        $this->incr('counter', 'request');
    }

    public function lastId()
    {
        return $this->storage->hGet('counter', 'request') ?? 1;
    }

    /**
     * ХЭШ => БАЗА
     *
     */
    public function save()
    {
        $mask = REDIS_REQUEST_HASH . ':*';
        $requests = $this->getAll($mask);

        if(!$requests)
            return;

        $params = array_keys($requests[0]);

        foreach($requests as $request) {
            $insert = \ORM::for_table(TABLE_REQUESTS)->create();
            \ORM::get_db()->beginTransaction();
            try {
                foreach ($request as $key => $value) {
                    $insert->$key = $value;
                }
                $insert->save();
                \ORM::get_db()->commit();
            } catch (Exception $e) {
                \ORM::get_db()->rollBack();
                throw $e;
            }
        }

        $this->delete($mask);
    }
}