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
    /**
     * Return PDO Instantion for specific type
     * @param $type
     * @param null $db_user
     * @param null $db_password
     * @param null $db_name
     * @return PDO
     */
    public static function createInstance(string $type, string $db_user=null, string $db_password=null, string $db_name=null) : PDO
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

    /**
     * Return path to database data
     * @param string $type
     * @return string
     */
    public function getType(string $type) : string
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