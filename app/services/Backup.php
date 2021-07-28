<?php


namespace App\services;

use ORM;

class Backup
{
    public static function backup()
    {
        $filename='database_backup_'.date('G_a_m_d_y').'.sql';

        $result=exec('mysqldump '.MYSQL_DATABASE.' -h127.0.0.1 --port=3310 --password='.MYSQL_PASSWORD.' --user='.MYSQL_USER.' --single-transaction >'.DOCROOT.'backups/'.$filename,$output);
    }


}