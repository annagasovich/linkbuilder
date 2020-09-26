<?php
declare(strict_types=1);

namespace App;

use App\Linkbuilder;
use App\interfaces\Api;
use App\interfaces\Admin;
use App\Redirector;

class Router
{
	public function distribute()
    {
        if ($_SERVER['REQUEST_URI'] == '/'){
            $this->render();
            include(DOCROOT . 'views/main.tpl');
            return;
        }

        //единичная ссылка из веб-интерфейса
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/build'){
            $linkbuilder = new Linkbuilder();
            echo $linkbuilder->getLink();
            return;
        }

        //пачка ссылок по апи
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/api'){
            $linkbuilder = new Api();
            echo $linkbuilder->process();
            return;
        }

        //админка
        if (strstr($_SERVER['REQUEST_URI'], 'admin')){
            $admin = new Admin();
            $this->renderStart();
            $admin->init();
            $this->renderEnd();
            return;
        }

        //редирект
        if(preg_match("/^\/[A-Za-z0-9]{1,13}$/", $_SERVER['REQUEST_URI'])){
            $redirector = new Redirector();
            $redirector->checkLink($_SERVER['REQUEST_URI']);
        }
    }

    public function render()
    {
        ob_start();
        include(DOCROOT . 'views/sweet_branding.tpl');
    }

    private function renderStart()
    {
        ob_start();
    }

    private function renderEnd()
    {
        include(DOCROOT . 'views/sweet_branding.tpl');
    }


}