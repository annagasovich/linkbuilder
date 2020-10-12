<?php
declare(strict_types=1);

namespace App\interfaces;

class Admin
{
    private $admin;
    private $auth;

	public function init(){

	    $this->initAuth();

        if (strstr($_SERVER['REQUEST_URI'], 'adminer')){
            header("HTTP/1.0 404 Not Found");
            ob_start();
            include(DOCROOT . 'views/404.tpl');
            $content = ob_get_clean();
            return $content;
        }

        if (strstr($_SERVER['REQUEST_URI'], 'login')){
            return $this->login();
        }

        if (!$this->auth->isLoggedIn())
            header('Location: '.SITE.'/admin/login', TRUE, 302);
            $this->admin = new \CRUD\Admin([
            'tpl' => 'custom_templates',
            'headers' => [
                'slug' => 'Краткая ссылка',
                'url' => 'Полная ссылка',
                'hits' => 'Число запросов',
            ]
        ]);
        if (strstr($_SERVER['REQUEST_URI'], 'edit')){
            return $this->update();            
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

    private function initAuth()
    {
        $db = new \Delight\Db\PdoDsn('mysql:dbname='.MYSQL_DATABASE.';host='.MYSQL_HOST.';charset=utf8mb4', MYSQL_USER, MYSQL_PASSWORD);
        $this->auth = new \Delight\Auth\Auth($db);
        return;
    }

    public function make()
    {
        $db = new \Delight\Db\PdoDsn('mysql:dbname='.MYSQL_DATABASE.';host='.MYSQL_HOST.';charset=utf8mb4', MYSQL_USER, MYSQL_PASSWORD);
        $auth = new \Delight\Auth\Auth($db);

        try {
            $userId = $auth->register('annie.ga@yandex.ru', 'NpW5VgZNGZNXavqZZEGz', 'ciom', null);

            return 'We have signed up a new user with the ID ' . $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

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
            try {
                $this->auth->loginWithUsername($_POST['email'], $_POST['password']);
                header('Location: '.SITE.'/admin/', TRUE, 302);
            }
            catch (\Delight\Auth\UnknownUsernameException $e) {
                return('Неверный логин');
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                return('Неверный логин');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                return('Неверный пароль');
            }
            catch (\Delight\Auth\EmailNotVerifiedException $e) {
                die('Email not verified');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Исчерпаны попытки входа');
            }
        }
    }
}