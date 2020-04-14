<?php
/**
 * Created by PhpStorm.
 * User: Camer
 * Date: 17.01.2018
 * Time: 14:59
 */

namespace AppRanks;
use \PDO;

class DbInstance extends PDO
{
    public static function createInstance($type, $db_user=null, $db_password=null, $db_name=null)
    {
        if($type == -1)
        {
            try
            {
                $host = '178.250.45.237';
                $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db_name . ';encoding=utf8;charset=utf8', $db_user, $db_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            }
            catch( PDOException $e )
            {
                echo 'PDO Connect Error';
                exit();
            }
            return $pdo;
        }
        else
        {
            try
            {
                $path = DbInstance::getType($type);
            }
            catch(Exception $e)
            {
                echo 'Problem: ',  $e->getMessage(), "\n";
                exit();
            }

            if($path == NULL)
            {
                echo 'Type od DB doesnt exist';
                exit();
            }


            require  '../' . $path;

            try
            {
                $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';encoding=utf8;charset=utf8', $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            }
            catch( PDOException $e )
            {
                echo 'PDO Connect Error';
                exit();
            }
            return $pdo;
        }
    }

    public function getType($type)
    {
        $path = NULL;
        switch($type)
        {
            case 'Forum':
                $path = 'config/forum.php';
                break;
            case 'PO':
                $path = 'config/panelopiekuna.php';
                break;
            case 'System':
                $path = 'config/systemflag.php';
                break;
            case 'SB':
                $path = 'config/sourcebans.php';
                break;
            default:
                throw new Exception("Type doesn't exist");
                break;
        }
        return $path;
    }
}