<?php
declare(strict_types=1);

namespace App;

use DeviceDetector\DeviceDetector;

class Logger
{
    private $data;
    public function __construct($preset)
    {
        $this->data = array_merge($this->userAgentParse(), $this->otherInfo(), $preset);
    }

    public function get()
    {
        return $this->data;
    }

    private function userAgentParse()
    {
        $dd = new DeviceDetector($_SERVER['HTTP_USER_AGENT']);

        $dd->parse();

        $clientInfo = json_encode($dd->getClient());
        $osInfo = json_encode($dd->getOs());
        $device = $dd->getDeviceName();
        $brand = json_encode($dd->getBrandName());
        $model = json_encode($dd->getModel());

        return compact('clientInfo', 'osInfo', 'device', 'brand', 'model');
    }

    private function otherInfo()
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $referer = $_SERVER['HTTP_REFERER'];

        return compact('date', 'time', 'ip', 'referer');
    }

    public function redisKey($id)
    {
        return REDIS_REQUEST_HASH . ':'
            . $this->data['original'] . ':'
            . $this->data['slug'] . ':'
            . $this->data['date'] . ':'
            . $id;
    }
}