<?php
namespace App\extentions;

use CRUD\CRUD;
use App\extentions\Views\Create;
use App\extentions\Views\Read;
use App\extentions\Views\Table;
use App\extentions\Views\Update;

class Admin {

    public function __construct($config = null) {
        $this->crud = new CRUD();
        $this->config = $config;
    }

    public function table($table, $ignore_keys = null) {
        $data['table'] = $table;
        $data['rows'] = \ORM::for_table($table)->find_array();
        if($ignore_keys)
            $data['filter'] = $ignore_keys;
        $this->view = new Table('table', $data, $this->config);
        return $this->view->render();
    }

    public function create($table, $ignore_keys = null) {
        if($_POST) {
            $params = $_POST;
            $insert = \ORM::for_table($table)->create();
            \ORM::get_db()->beginTransaction();
            try {
                foreach ($params as $key => $value) {
                    $insert->$key = $value;
                }
                $insert->save();
                \ORM::get_db()->commit();
            } catch (Exception $e) {
                \ORM::get_db()->rollBack();
                throw $e;
            }
            $this->view = new Create('done', null, $this->config);
            return $this->view->render();
        } else {
            $fields = \ORM::for_table($table)->raw_query('DESCRIBE '.$table)->find_array();
            if($ignore_keys) {
                foreach ($fields as $key => $field) {
                    if(in_array($field['Field'], $ignore_keys))
                        unset($fields[$key]);
                }
            }
            $data['fields'] = $fields;
            $this->view = new Create('create', $data, $this->config);
            return $this->view->render();
        }
    }

    public function read($table, $id, $ignore_keys = null) {
        $row = $this->crud->read($table, $id);
        if($ignore_keys) {
            foreach ($row as $key => $value) {
                if(in_array($key, $ignore_keys))
                    unset($row[$key]);
            }
        }
        $data['row'] = $row;
        $this->view = new Read('read', $data);
        return $this->view->render();
    }

    public function update($table, $id, $ignore_keys = null) {
        if($_POST) {
            try {

                $params = $_POST;

                $this->crud->save($table, $params, $id);
                $this->view = new Create('done', null, $this->config);
            } catch (\PDOException $e) { //Added slash
                //echo $e->getMessage();
                return 'Нарушение уникальности адреса';
            } catch (Exception $e) {
                //echo $e->getMessage();
            }
        } else {
            $fields = \ORM::for_table($table)->raw_query('DESCRIBE '.$table)->find_array();
            $row = $this->crud->read($table, $id);
            if($ignore_keys) {
                foreach ($row as $key => $value) {
                    if(in_array($key, $ignore_keys))
                        unset($row[$key]);
                }
                foreach ($fields as $key => $field) {
                    if(in_array($field['Field'], $ignore_keys))
                        unset($fields[$key]);
                }
            }
            $data['fields'] = $fields;
            $data['row'] = $row;
            $this->view = new Update('update', $data, $this->config);
        }
        return $this->view->render();
    }

    public function delete($table, $id) {
        $this->crud->delete($table, $id);
        $this->view = new Create('done', null, $this->config);
        return $this->view->render();
    }

}
