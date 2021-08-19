<?php
declare(strict_types=1);

namespace App;

use App\Linkbuilder;
use App\interfaces\Api;
use App\interfaces\Admin;
use App\interfaces\Analytics;
use App\Redirector;
use App\services\ActionLog;
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

        if ($_SERVER['REQUEST_URI'] == '/exit/'){
            Auth::logout();
            Auth::redirectToLogin();
            return;
        }

        //единичная ссылка из веб-интерфейса
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/build'){
            Auth::check();
            $linkbuilder = new Linkbuilder();
            $link = $linkbuilder->getLink();

            ActionLog::log('build link from interface ' . $_POST['link'] . ' => ' . $link);

            echo $link;
            return;
        }

        //пачка ссылок по апи
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/api'){
            $this->buildHeaders();
            if(Auth::api() === true){
                $linkbuilder = new Api();

                $links =  $linkbuilder->process();

                ActionLog::log('build link from api ' . json_encode($links));

                echo $links;
                return;
            }
        }

        //действия
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] == '/user_actions'){
            $this->buildHeaders();
            if(Auth::api() === true  && Auth::isAdmin()){
                echo ActionLog::get_log();
                return;
            }
        }

        //получить лог запросов
        if (($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') && $_SERVER['REQUEST_URI'] == '/logs'){
            $this->buildHeaders();
            if(Auth::api() === true && Auth::isAdmin()){

                ActionLog::log('request logs');

                $logs = new Analytics();

                echo $logs->get();

                return;
            } else {
                header("HTTP/1.0 401 Unauthorized");
                exit;
            }
        }

        //админка
        if (strstr( $_SERVER['REQUEST_URI'], '/admin')){
            Auth::check();
            if(Auth::authorized() && !Auth::isAdmin()){
                header("HTTP/1.0 401 Unauthorized");
                ob_start();
                include(DOCROOT . 'views/forbidden.tpl');
                $content = ob_get_clean();
                $this->render($content);
                exit;
            }

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
        if(Auth::isAdmin()){
            include(DOCROOT . 'views/admin_style.tpl');
        }
        $template = ob_get_clean();

        $result = str_replace('{content}', $content, $template);

        echo $result;
        return;
    }

    public function buildHeaders()
    {

        if(isset($_SERVER['HTTP_ORIGIN']) && strstr($_SERVER['HTTP_ORIGIN'], 'wciom.ru')){
            header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
        } else {
            header("Access-Control-Allow-Origin:https://wciom.ru");
        }

        return;
        if(isset($_SERVER['HTTP_ORIGIN'])) {
            foreach (DOMAINS as $domain) {
                if(preg_match('/'.$domain.'/', $_SERVER['HTTP_ORIGIN'])){
                    header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
                    return;
                }
            }
            header("Access-Control-Allow-Origin:https://".DOMAINS[1]);
        } else {
            header("Access-Control-Allow-Origin:https://".DOMAINS[1]);
        }
    }



}