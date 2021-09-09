<?php
declare(strict_types=1);

namespace App\interfaces;

use App\extentions\Admin as AdminPanel;
use App\cache\Redirect as RedirectCache;
use App\services\Auth;
use App\services\Users;

class Admin
{
    private $admin;
    private $auth;

	public function init(){

        if (strstr($_SERVER['REQUEST_URI'], 'adminer')){
            header("HTTP/1.0 404 Not Found");
            ob_start();
            include(DOCROOT . 'views/404.tpl');
            $content = ob_get_clean();
            return $content;
        }

        if (strstr($_SERVER['REQUEST_URI'], 'users')){
            $users = new Users();
            return $users->init();
        }

        if (strstr($_SERVER['REQUEST_URI'], 'login')){
            return $this->login();
        }

            $this->admin = new AdminPanel([
            'tpl' => 'custom_templates',
            'headers' => [
                'slug' => 'Краткая ссылка',
                'url' => 'Полная ссылка',
                'hits' => 'Число запросов',
            ]
        ]);
        if (strstr($_SERVER['REQUEST_URI'], 'edit')){
            $view = $this->update();
            $cache = new RedirectCache();
            $cache->rebuild();
            return $view;
        }

        if (strstr($_SERVER['REQUEST_URI'], 'del')){
            return $this->delete();            
        }

        if (strstr($_SERVER['REQUEST_URI'], 'make')){
            return $this->make();
        }

        return $this->table();

    }

    public function table()
    {
        return $this->admin->table(TABLE, ['id']);
    }

    public function update()
    {
        return $this->admin->update(TABLE, $_GET['id'], ['id', 'hits', 'date']);
    }

    public function delete()
    {
        return $this->admin->delete(TABLE, $_GET['id']);
    }

    public function login()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            //отображаем форму авторизации
            ob_start();
            include(DOCROOT . 'views/login.tpl');
            $login = ob_get_clean();
            return $login;
        } else {
            $result = Auth::login($_POST['email'], $_POST['password']);
            if(is_string($result))
                return '<h1>' . $result. '</h1>';
        }
    }

}