<?php


namespace App\services;

use App\extentions\Admin as AdminPanel;

class Users
{
    private $admin;
    private static $TABLE = 'users';
    private static $IGNORE = ['id', 'password', 'status', 'verified', 'resettable', 'roles_mask', 'registered', 'last_login', 'force_logout'];
    public function init(){

        $this->admin = new AdminPanel([
            'tpl' => 'user_templates',
            'headers' => [
                'username' => 'Логин',
                'password' => 'Пароль',
                'roles_mask' => 'Права'
            ]
        ]);

        if (strstr($_SERVER['REQUEST_URI'], 'edit')){
            $view = $this->update();
            return $view;
        }

        if (strstr($_SERVER['REQUEST_URI'], 'del')){
            return $this->delete();
        }

        if (strstr($_SERVER['REQUEST_URI'], 'create')){
            return $this->create();
        }

        return $this->table();
    }

    public function table()
    {
        return $this->admin->table(self::$TABLE, self::$IGNORE);
    }

    public function create()
    {
        return $this->admin->create(self::$TABLE, $_GET['id'], self::$IGNORE);
    }

    public function update()
    {
        return $this->admin->update(self::$TABLE, $_GET['id'], self::$IGNORE);
    }

    public function delete()
    {
        return $this->admin->delete(self::$TABLE, $_GET['id']);
    }

}