<?php
declare(strict_types=1);

namespace App;

use App\Linkbuilder;
use App\interfaces\Api;
use App\interfaces\Admin;
use App\interfaces\Analytics;
use App\Redirector;
use App\services\Auth;

class Router
{
	public function distribute()
    {

        if ($_SERVER['REQUEST_URI'] == '/'){
            Auth::check();
            ob_start();
            include(DOCROOT . 'views/main.tpl');
            $content = ob_get_clean();
            $this->render($content);
            return;
        }

        //единичная ссылка из веб-интерфейса
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/build'){
            Auth::check();
            $linkbuilder = new Linkbuilder();
            echo $linkbuilder->getLink();
            return;
        }

        //пачка ссылок по апи
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/api'){
            if(Auth::api() === true){
                $this->buildHeaders();
                $linkbuilder = new Api();
                echo $linkbuilder->process();
                return;
            }
        }

        //получить лог запросов
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/logs'){
            $this->buildHeaders();
            $logs = new Analytics();
            echo $logs->get();
            return;
        }

        //админка
        if (strstr( $_SERVER['REQUEST_URI'], '/admin')){
            Auth::check();

            $admin = new Admin();

            $content = $admin->init();

            $this->render($content);

            return;
        }

        //редирект
        if(preg_match("/^\/[A-Za-z0-9_]{1,100}$/", $_SERVER['REQUEST_URI'])){
            $redirector = new Redirector();
            $this->render($redirector->checkLink($_SERVER['REQUEST_URI']));
            return;
        }

        header("HTTP/1.0 404 Not Found");
        ob_start();
        include(DOCROOT . 'views/404.tpl');
        $content = ob_get_clean();
        $this->render($content);
        exit;
    }

    public function render($content = 'контент не найден')
    {
        ob_start();
        include(DOCROOT . 'views/sweet_branding.tpl');
        $template = ob_get_clean();

        $result = str_replace('{content}', $content, $template);

        echo $result;
        return;
    }

    public function buildHeaders()
    {
        if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], DOMAINS)) {
            header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
        } else {
            header("Access-Control-Allow-Origin:".DOMAINS[0]);
        }
    }


}