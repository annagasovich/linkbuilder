<?php
declare(strict_types=1);

namespace App\interfaces;

class Admin
{
    private $admin;

	public function init(){
        $this->admin = new \CRUD\Admin([
            'tpl' => 'custom_templates',
            'headers' => [
                'slug' => 'Краткая ссылка',
                'url' => 'Полная ссылка',
                'hits' => 'Число запросов',
            ]
        ]);
        if (strstr($_SERVER['REQUEST_URI'], 'edit')){
            $this->update();
            return;
        }

        if (strstr($_SERVER['REQUEST_URI'], 'del')){
            $this->delete();
            return;
        }

        $this->table();

    }

    public function table()
    {
        $this->admin->table(TABLE, ['id']);
    }

    public function update()
    {
        $this->admin->update(TABLE, $_GET['id'], ['id', 'hits', 'date']);
    }

    public function delete()
    {
        $this->admin->delete(TABLE, $_GET['id']);
    }
}