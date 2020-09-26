<?php
declare(strict_types=1);

namespace App;

use ORM;

class Redirector
{
    public function checkLink(string $link)
    {
        $link = substr($link, 1);
        $link = ORM::for_table(TABLE)->where(
            'slug', $link
        )->find_one();

        if($link) {
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