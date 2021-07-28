<?php


namespace App\services;

use ORM;

class Backup
{
    public static function backup()
    {
        $filename='database_backup_'.date('G_a_m_d_y').'.sql';

        $result=exec('mysqldump '.MYSQL_DATABASE.' --password='.MYSQL_PASSWORD.' --user='.MYSQL_USER.' --single-transaction >'.DOCROOT.'backups/'.$filename,$output);
    }


}