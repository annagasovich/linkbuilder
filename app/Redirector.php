<?php
declare(strict_types=1);

namespace App;

use ORM;
use App\Cache;

class Redirector
{
    public function checkLink(string $link)
    {
        $link = substr($link, 1);

        $cache = new Cache();
        $cached_link = $cache->check($link);
        if($cached_link)
        {
            $cache->hit($link);
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
        }
    }

    public function redirect(string $url)
    {
        if(!strstr($url, 'http://') && !strstr( $url, 'https://') )
            $url = 'http://' . $url;
        header('Location: ' . $url, true, 301);
    }

}