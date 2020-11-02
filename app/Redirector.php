<?php
declare(strict_types=1);

namespace App;

use ORM;
use App\cache\Cache;

class Redirector
{
    private $link;
    public function checkLink(string $link)
    {
        $this->link = substr($link, 1);

        $cache = new Cache();
        $cached_link = $cache->check($this->link);
        if($cached_link)
        {
            $cache->hit($this->link);
            $this->redirect($cached_link);
            return;
        }
        $link = ORM::for_table(TABLE)->where(
            'slug', $link
        )->find_one();

        if($link) {
            $cache->append($link);
            $link->hits++;
            $link->save();
            $this->redirect($link->url);
        } else {
            header("HTTP/1.0 404 Not Found");
            ob_start();
            include(DOCROOT . 'views/404.tpl');
            $content = ob_get_clean();
            return $content;
        }
    }

    public function redirect(string $url)
    {
        if(!strstr($url, 'http://') && !strstr( $url, 'https://') )
            $url = 'http://' . $url;

        $routes = explode('://', $url);
        $this->logRequest($routes[1]);

        header('Location: ' . $url, true, 301);
    }

    private function logRequest($url)
    {
        $preset = [
            'original' => $url,
            'slug' => $this->link
        ];
        $logger = new Logger($preset);
        $cache = new Cache();
        $key = $logger->redisKey();
        $cache->saveReq($key, $logger->get());
    }

}