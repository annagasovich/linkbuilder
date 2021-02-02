<?php
declare(strict_types=1);

namespace App;

use ORM;
use App\cache\Redirect as RedirectCache;

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
        if($linkExists){
            if($_POST['double']){
                return $this->buildLink();
            } else {
                return HTTP . $_SERVER['HTTP_HOST'].'/'.$linkExists->slug;
            }
        }
        else {
            $responseCode = $this->checkResponse($this->link);
            if(!$this->validResponse($responseCode))
                return ERROR;
            return $this->buildLink();
        }
    }

    /**
     * сгенерировать хэш + сохранить его в базу
     */
    public function buildLink()
    {
        $url = $this->buildHash();
        $this->save($url);
        return HTTP . $_SERVER['HTTP_HOST'].'/'.$url;
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
     * Проверить для апи, правильная ли структура в json
     * @return bool
     */
    public function checkForApi()
    {
        return is_string($this->link);
    }

    /**
    Проверить хэш на уникальность
     */
    public function checkHash($slug)
    {
        return ORM::for_table(TABLE)->where(
            'slug', $slug
        )->find_one();
    }

    public function save($slug)
    {
        $link = ORM::for_table(TABLE)->create();
        $link->slug = $slug;
        $link->url = $this->link;
        $link->hits = 0;
        $link->save();
        $cache = new RedirectCache();
        $cache->append($link);
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

	public function checkResponse($url)
    {
        if(isset($_POST['future']))
            return 200;
        if(!strstr($url, 'http://') && !strstr( $url, 'https://') )
            $url = 'http://' . $url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_NOBODY  , true);
        $c = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $info;
    }

    public function validResponse($code)
    {
        return !(($code > 500) || ($code == 0) || ($code == 401));
    }

}
