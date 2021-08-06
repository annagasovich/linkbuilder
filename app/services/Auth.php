<?php


namespace App\services;

use \Delight\Db\PdoDsn;
use \Delight\Auth\Auth as VendorAuth;
use \Delight\Auth\InvalidEmailException;
use \Delight\Auth\InvalidPasswordException;
use \Delight\Auth\UserAlreadyExistsException;
use \Delight\Auth\TooManyRequestsException;

class Auth
{
    private static $auth = false;

    public static function initAuth()
    {
        if(!self::$auth){
            $db = new PdoDsn('mysql:dbname='.MYSQL_DATABASE.';host='.MYSQL_HOST.';charset=utf8mb4', MYSQL_USER, MYSQL_PASSWORD);
            self::$auth = new VendorAuth($db);
        }
        return self::$auth;
    }

    public static function makeUser($mail, $login, $pass)
    {

        try {
            $userId = self::$auth->register($mail, $pass, $login, null);

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

    public static function login($login, $pass, $is_api = false){
        if(!$login || !$pass)
            return false;
        self::initAuth();
        try {
            self::$auth->loginWithUsername($login, $pass);
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
            return('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            return('Исчерпаны попытки входа');
        }

        if($is_api){
            ActionLog::log('api login');
            return true;
        } else {
            ActionLog::log('login');
        }
        header('Location: '.SITE.'/', TRUE, 302);
    }

    public static function api(){

        $login = self::login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], true);
        if($login !== true){
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        return true;
    }

    public static function redirectToLogin(){
        header('Location: '.SITE.'/admin/login/', TRUE, 302);
    }

    public static function authorized(){
        return self::$auth->isLoggedIn();
    }

    public static function check(){
        self::initAuth();
        if(self::authorized())
            return true;
        if(!strstr($_SERVER['REQUEST_URI'], 'admin/login'))
            self::redirectToLogin();
        return true;
    }

    public static function logout(){
        if(!self::$auth){
            self::initAuth();
        }
        if(self::$auth){
            ActionLog::log('logout');
            self::$auth->logout();
            self::$auth->destroySession();
            self::$auth = false;
        }
    }

    public static function isAdmin(){
        return self::$auth && self::$auth->hasAnyRole(
                \Delight\Auth\Role::ADMIN
            );
    }

    public static function user(){
        return self::$auth->id();
    }

}