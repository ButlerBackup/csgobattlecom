<?php

class AdminModel extends Model

{

    public function getUserByID($id)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE `id` = '$id'

            LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    /*-------------------- NEWS --------------------*/



    public function getNewsByID($nid)

    {

        $sql = "

            SELECT *

            FROM `news`

            WHERE `id` = '$nid'

            LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    public function getNews($status = false)

    {

        $sql = "

            SELECT *

            FROM `news`

        ";



        if ($status !== false)

            $sql .= "`status` = '$status'";



        $sql .= "ORDER BY `time` DESC";



        return $this->getAll($this->query($sql));

    }



    public function getLangNewsByID($id)

    {

        $sql = "

            SELECT *

            FROM `news_lang`

            WHERE `id` = '$id'

            LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    public function getLangNewsList($id)

    {

        $sql = "

            SELECT *

            FROM `news_lang`

            WHERE `nid` = '$id'

        ";



        $sql .= "ORDER BY `lang` ASC";



        return $this->getAll($this->query($sql));

    }



    /*-------------------- Verify --------------------*/



    public function countVerifyUsers()

    {

        $sql = "

            SELECT COUNT(*)

            FROM `users`

            WHERE `role` = 'claim'

        ";



        return $this->fetch($this->query($sql), 'row')[0];

    }



    public function getVerifyUsers()

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE `role` = 'claim'

        ";



        return $this->query($sql);

    }



    public function setVerifyUser($id, $data)

    {

        return $this->update('users', $data, "`id` = '$id' AND `role` = 'claim'");

    }



 


    public function getDisputes()

    {

        $query = "SELECT

                      `ms`.*,

                      (SELECT `nickname` FROM `users` WHERE id = `ms`.`pid`) as `pidName`,

                      (SELECT `nickname` FROM `users` WHERE id = `ms`.`uid`) as `uidName`

                    FROM

                      `matches` `ms`

                    WHERE

                      (`ms`.`pwin` = '1' AND `ms`.`uwin` = '1')

                      OR (`ms`.`pwin` = '2' AND `ms`.`uwin` = '2')

                ";



        return $this->getAll($this->query($query));

    }



    public function addServer($data)

    {

        return $this->insert('servers', $data);

    }



    public function deleteServer($sid)

    {

        return $this->delete('servers', " `id` = '$sid' LIMIT 1; ");

    }



    public function getServers()

    {

        return $this->getAll($this->query("SELECT * FROM `servers`;"));

    }



    public function getServer($sid)

    {

        return $this->fetch($this->query("SELECT * FROM `servers` WHERE `id` = '$sid' LIMIT 1;"));

    }



    public function editServer($sid, $data)

    {

        return $this->update('servers',$data, " `id` = '$sid' LIMIT 1");

    }



    /*-------------------- Edit users --------------------*/



    public function countSearchUsers($uid = false, $nickname = false, $steamid = false, $role = false)

    {

        $sql = "SELECT COUNT(`id`) FROM `users` ";

        $where = "";



        if ($uid)

            $where .= "`id` LIKE '%$uid%' ";



        if ($nickname)

            $where .= (($where) ? "AND " : "" ) . "`nickname` LIKE '%$nickname%' ";



        if ($steamid)

            $where .= (($where) ? "AND " : "" ) . "`steamid` = '$steamid' ";



        if ($role)

            $where .= (($where) ? "AND " : "" ) . "`role` = '$role' ";



        if ($role == 'ban')

            $where .= "AND (`banRange` = '0' OR (`banRange` + `banDate`) > ".time().") ";



        if ($where)

            $sql .= "WHERE " . $where;



        return $this->fetch($this->query($sql), 'row')[0];

    }



    public function searchUsers($uid = false, $nickname = false, $steamid = false, $role = false, $start = false, $end = false)

    {

        $sql = "SELECT * FROM `users` ";

        $where = "";



        if ($uid)

            $where .= "`id` LIKE '%$uid%' ";



        if ($nickname)

            $where .= (($where) ? "AND " : "" ) . "`nickname` LIKE '%$nickname%' ";



        if ($steamid)

            $where .= (($where) ? "AND " : "" ) . "`steamid` = '$steamid' ";



        if ($role)

            $where .= (($where) ? "AND " : "" ) . "`role` = '$role' ";



        if ($role == 'ban')

            $where .= "AND (`banRange` = '0' OR (`banRange` + `banDate`) > ".time().") ";



        if ($where)

            $sql .= "WHERE " . $where;



        $sql .= "LIMIT $start, $end";



        return $this->query($sql);

    }



    public function changeRole($uid, $role)

    {

        return $this->update('users', array('role' => $role), "`id`  = '$uid' LIMIT 1");

    }



    /*-------------------- Other --------------------*/



    public function getGuests($field, $search)

    {

        $sql = "

            SELECT *

            FROM `guests`

        ";



        if ($field && $search)

            $sql .= "WHERE `$field` LIKE '%$search%'";



        return $this->query($sql);

    }



    public function getGuestsOnline()

    {

        $sql = "

            SELECT INET_NTOA(`ip`) AS 'ip', `browser`, `referer`, `count`, `time`

            FROM `guests`

            WHERE `time` >= '".(time()-600)."'

        ";



        return $this->query($sql);

    }



    public function countGuests($where = false)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `guests`

        ";



        if ($where)

            $sql .= "WHERE ".$where;



        return $this->fetch($this->query($sql), 'row')[0];

    }



    public function countUsers($where = false)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `users`

        ";



        if ($where)

            $sql .= "WHERE ".$where;



        return $this->fetch($this->query($sql), 'row')[0];

    }



    public function getUsersOnline($where = false)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE `dateLast` >= '".(time()-600)."'

        ".$where;



        return $this->query($sql);

    }

}



/* End of file */