<?php
declare(strict_types=1);

namespace App\interfaces;

use App\Linkbuilder;

class Api
{
	public function process()
    {
        if (!$_POST['links'])
            return json_encode(
                [
                    'status' => 'error',
                    'message' => 'Необходим параметр links - json с массивом ссылок!'
                ]
            );
        $links = json_decode($_POST['links']);

        if(count($links) == 0)
            return json_encode(
                [
                    'status' => 'error',
                    'message' => 'Список ссылок links не может быть пустым!'
                ]
            );
        $response = [];

        foreach ($links as $link){
            $linkbuilder = new Linkbuilder($link);
            if(!$linkbuilder->checkForApi()) {
                return json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Список ссылок links не должен содержать типы данных кроме строк!'
                    ]
                );
            }
            $short_link = $linkbuilder->getLink();
            $response[] = [
                'full_link' => $link,
                'short_link' => $short_link == ERROR ? false : $short_link
            ];
        }
        return json_encode($response);
    }
}