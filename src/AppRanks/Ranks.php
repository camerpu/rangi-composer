<?php

namespace AppRanks;
use \PDO;
use AppRanks\DbInstance;


class Ranks
{
    private $id;
    private $name;
    private $db_user, $db_password, $db_name;
    public function __construct($id)
    {
        $this->id = $id;
        if($this->tryLoad() == null)
            throw new Exception("Server doesn't exist");
    }
    private function tryLoad()
    {
        $pdo = DbInstance::createInstance('System');

        $sth = $pdo->prepare("SELECT * FROM ranks_stats WHERE `id`=:srvid");
        $sth->bindParam(':srvid', $this->id, PDO::PARAM_INT);
        $sth->execute();

        if($row = $sth->fetch())
        {
            $this->name = $row['name'];
            $this->db_name = $row['db_name'];
            $this->db_password = $row['db_password'];
            $this->db_user = $row['db_user'];

            return 1;
        }
        else
            return null;
    }

    public static function forumLink($steamid)
    {
        $pdo = DbInstance::createInstance('Forum');

        $sth = $pdo->prepare("SELECT `members_seo_name`, `member_id` FROM core_members WHERE `steamid`=:steamid");
        $sth->bindParam(':steamid', $steamid, PDO::PARAM_STR);
        $sth->execute();

        if($row = $sth->fetch())
        {
            $url = "https://max-play.pl/profile/" . $row['member_id'] . '-' . $row['members_seo_name'];
            return $url;
        }
        else
            return null;
    }

    public function getTOP($number)
    {
        $pdo = DbInstance::createInstance(-1, $this->db_user, $this->db_password, $this->db_name);

        $sth = $pdo->prepare("SELECT * FROM `rangi_nowe` ORDER BY `punkty` DESC LIMIT :number");
        $sth->bindParam(':number', $number, PDO::PARAM_INT);
        $sth->execute();

        $data = [];
        while($row = $sth->fetch())
        {
            $data[] = [
                'steamid'=>$row['steamid'],
                'nick'=>$row['nick'],
                'punkty'=>$row['punkty'],
                'czas'=>$row['czas'],
                'ranga_id'=>$row['ranga_id']
            ];
        }

        $pdo = null;
        return $data;
    }

    public function findByPlayer($nick)
    {
        $nick = '%' . $nick . '%';
        $pdo = DbInstance::createInstance(-1, $this->db_user, $this->db_password, $this->db_name);

        $sth = $pdo->prepare("SELECT * FROM `rangi_nowe` WHERE `nick` LIKE :nick");
        $sth->bindParam(':nick', $nick, PDO::PARAM_STR);
        $sth->execute();

        $data = [];
        while($row = $sth->fetch())
        {
            $data[] = [
                'steamid'=>$row['steamid'],
                'nick'=>$row['nick'],
                'punkty'=>$row['punkty'],
                'czas'=>$row['czas'],
                'ranga_id'=>$row['ranga_id']
            ];
        }


        $pdo = null;
        return $data;
    }

    public function getName()
    {
        return $this->name;
    }

    public static function listServers()
    {
        $pdo = DbInstance::createInstance('System');

        $sth = $pdo->prepare("SELECT `id`, `name` FROM ranks_stats");
        $sth->execute();

        $data = [];
        while($row = $sth->fetch())
        {
            $data[] = ['id'=>$row['id'], 'name'=>$row['name']];
        }

        $pdo = null;
        return $data;
    }
}