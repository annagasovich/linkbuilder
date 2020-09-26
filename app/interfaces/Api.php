<?php
declare(strict_types=1);

namespace App\interfaces;

use App\Linkbuilder;

class Api
{
	public function process()
    {
        $links = json_decode($_POST['links']);
        $response = [];
        foreach ($links as $link){
            $linkbuilder = new Linkbuilder($link);
            $response[] = [
                'full_link' => $link,
                'short_link' => $linkbuilder->getLink()
            ];
        }
        return json_encode($response);
    }
}