<?php

namespace AppRanks;
use \PDO;

class Ranks
{
    private $id;
    private $name;
    private $db_user, $db_password, $db_name;

    /**
     * Check if server exist and throwing Exception if not
     * Ranks constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        if($this->tryLoad() == null)
            throw new Exception("Server doesn't exist");
    }

    /**
     * Return name of the server
     * @return string
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Loading database data access for specific server
     * @return int
     */
    private function tryLoad() : int
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

    /**
     * Return url to forum account
     * @param string $steamid
     * @return string
     */
    public static function forumLink(string $steamid) : ?string
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

    /**
     * Return top players on the server
     * @param int $number
     * @return array
     */
    public function getTOP(int $number) : ?array
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

    /**
     * Finding data by player Name
     * @param $nick
     * @return array
     */
    public function findByPlayer($nick) : ?array
    {
        $nick = '%' . $nick . '%';
        $pdo = DbInstance::createInstance(-1, $this->db_user, $this->db_password, $this->db_name);

        $sth = $pdo->prepare("SELECT * FROM `rangi_nowe` WHERE `nick` LIKE :nick LIMIT 50");
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

    /**
     * Listing servers for navbar
     * @return array
     */
    public static function listServers() : ?array
    {
        $pdo = DbInstance::createInstance('System');

        $sth = $pdo->prepare("SELECT `id`, `name` FROM ranks_stats");
        $sth->execute();

        $data = [];
        while($row = $sth->fetch())
        {
            $data[] = [
                'id'=>$row['id'],
                'name'=>$row['name']
            ];
        }

        $pdo = null;
        return $data;
    }
}