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
}