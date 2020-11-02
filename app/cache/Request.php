<?php


namespace App\cache;


class Request extends Cache
{

    public function saveReq($key, $data)
    {
        $this->storage->hMSet($key, $data);
    }
}