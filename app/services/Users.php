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
        if($_POST){
            $_POST['registered'] = time();
            $_POST['verified'] = 1;
            $this->prepare_post();
        }
        return $this->admin->create(self::$TABLE, self::$IGNORE);
    }

    public function update()
    {
        if($_POST){
            $_POST['registered'] = time();
            $this->prepare_post();
        }
        return $this->admin->update(self::$TABLE, $_GET['id'], self::$IGNORE);
    }

    public function delete()
    {
        return $this->admin->delete(self::$TABLE, $_GET['id']);
    }

    private function prepare_post(){
    /*Исключения: пароль и роль*/
    if(isset($_POST['password'])){
        if($_POST['password'] == '')
            unset($_POST['password']);
        else
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    if(isset($_POST['role'])){
        if($_POST['role'] == 'admin')
            $_POST['roles_mask'] = \Delight\Auth\Role::ADMIN;

        if($_POST['role'] == 'user')
            $_POST['roles_mask'] = \Delight\Auth\Role::EMPLOYEE;

        unset($_POST['role']);
    }
}


}