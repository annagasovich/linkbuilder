<?php


namespace App\services;


class ActionLog
{
    public static function log($action){
        $id = Auth::user();
        $time = date('Y-m-d H:i:s');
        $action_record = \ORM::for_table('user_actions')->create();

        $action_record->user_id = $id;
        $action_record->time = $time;
        $action_record->action = $action;

        $action_record->save();
    }

    public static function get_log(){
        $q = \ORM::for_table('user_actions')->select('users.username')->select('user_actions.*')->join('users', array('user_actions.user_id', '=', 'users.id'));//->find_many();
        if(isset($_POST['from'])){
            $q = $q->where_gte('user_actions.id', $_POST['from']);
        }
        if(isset($_POST['to'])){
            $q = $q->where_lte('user_actions.id',  $_POST['to']);
        }

        return json_encode($q->findArray());
    }
}