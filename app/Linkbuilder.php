<?php
declare(strict_types=1);

namespace App;

use ORM;

class Linkbuilder
{
    private $link;

    public function __construct($link = null)
    {
        $this->link = $link ? $link : $_POST['link'];
    }

    public function getLink()
    {
        $linkExists = $this->check();
        if($linkExists)
            return $_SERVER['HTTP_HOST'].'/'.$linkExists->slug;
        else {
            $url = $this->buildHash();
            $this->save($url);
            return $_SERVER['HTTP_HOST'].'/'.$url;
        }
    }

    /**
      Проверить, есть ли такая ссылка в хранилище
     */
    public function check()
    {
        return ORM::for_table(TABLE)->where(
            'url', $this->link
        )->find_one();
    }

    /**
    Проверить хэш на уникальность
     */
    public function checkHash($slug)
    {
        return ORM::for_table(TABLE)->where(
            'slug', $this->slug
        )->find_one();
    }

    public function save($slug)
    {
        $link = ORM::for_table(TABLE)->create();
        $link->slug = $slug;
        $link->url = $this->link;
        $link->hits = 0;
        $link->save();
    }

    /**
     * Пилим хэши пока не получим уникальный
     * Вероятностные алгоритмы зло, но что вы хотите, это криптография Х)))
     */
    public function buildHash()
	{
		$hash = substr(uniqid(), 8);
		do{
            $check = $this->checkHash($hash);
            $hash = substr(uniqid(), 13 - LENGTH);
        } while ($check);
		return $hash;
	}

}