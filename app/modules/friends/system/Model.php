<?php
class FriendsModel extends Model
{
    public function getFriends($userID, $request = false, $status = 1, $ban = 0, $online = false, $start = false, $count = false)
    {
        $time = time()-300;
        $query = "SELECT
                    `fr`.*,
                    `usr`.`nickname` AS `name`,
                    `usr`.`elo` AS `elo`
                FROM
                    `friends` `fr`,
                    `users` `usr`
                WHERE
                    ".( ($request)?
                        ($request == 'in')?
                        "(`fr`.`pid` = '$userID' AND `usr`.`id` = `fr`.`uid`)"
                        : "( `fr`.`uid` = '$userID' AND `usr`.`id` = `fr`.`pid` )"
                    : "(
                        (`fr`.`uid` = '$userID' AND `usr`.`id` = `fr`.`pid`)
                        OR
                        (`fr`.`pid` = '$userID' AND `usr`.`id` = `fr`.`uid`)
                       )" )."
                    AND `fr`.`status` = $status
                    AND `fr`.`ban` = $ban
                    ".( ($online)? "AND `usr`.`dateLast` > '$time'" : "" )."
                    ". ( ($start !== false && $count !== false)? " LIMIT $start, $count " : "" ) ."
                ;";
        
        return $this->getAll($this->query($query));
    }
    
    public function countFriends($userID, $request = false, $status = 1, $ban = 0, $online = false)
    {
        $time = time()-300;
        $query = "SELECT
                    COUNT(`fr`.`id`)
                FROM
                    `friends` `fr`,
                    `users` `usr`
                WHERE
                    ".( ($request)?
                        ($request == 'in')?
                        "(`fr`.`pid` = '$userID' AND `usr`.`id` = `fr`.`uid`)"
                        : "( `fr`.`uid` = '$userID' AND `usr`.`id` = `fr`.`pid` )"
                    : "(
                        (`fr`.`uid` = '$userID' AND `usr`.`id` = `fr`.`pid`)
                        OR
                        (`fr`.`pid` = '$userID' AND `usr`.`id` = `fr`.`uid`)
                       )" )."
                    AND `fr`.`status` = $status
                    AND `fr`.`ban` = $ban
                    ".( ($online)? "AND `usr`.`dateLast` > '$time'" : "" )."
                ;";
        
        return $this->fetch($this->query($query),'row')[0];
    }
    
    public function userExist($id)
    {
        $query = "SELECT COUNT(*) FROM `users` WHERE `id` = '$id' LIMIT 1; ";
        
        return $this->fetch($this->query($query),'row')[0];
    }
    
    public function friendsStatus($uid, $pid)
    {
        $query = "SELECT `uid`,`pid`,`status`,`ban` FROM `friends` WHERE (`uid` = '$uid' AND `pid` = '$pid') OR (`uid` = '$pid' AND `pid` = '$uid') LIMIT 1; ";
        
        return $this->fetch($this->query($query),'assoc');
    }
    
    public function processFriend($operation, $uid, $pid)
    {
        switch ($operation) {
            case 'sendFriendsRequest':
                $query = "INSERT INTO `friends` (`uid`, `pid`) VALUES ('$uid', '$pid');";
                break;
            case 'deleteFriend':
                $query = "DELETE FROM `friends` WHERE ((`uid` = '$uid' AND `pid` = '$pid') OR (`uid` = '$pid' AND `pid` = '$uid')) LIMIT 1;";
                break;
            case 'cancelRequest':
                $query = "DELETE FROM `friends` WHERE (`uid` = '$uid' AND `pid` = '$pid') AND `status` = 0 LIMIT 1;";
                break;
            case 'acceptRequest':
                $query = "UPDATE `friends` SET `status` = 1 WHERE (`uid` = '$pid' AND `pid` = '$uid') LIMIT 1;";
                break;
            case 'declineRequest':
                $query = "DELETE FROM `friends` WHERE (`uid` = '$pid' AND `pid` = '$uid') AND `status` = 0 LIMIT 1;";
                break;
            case 'banRequest':
                $query = "INSERT INTO `friends` (`uid`, `pid`, `ban`) VALUES ('$uid', '$pid', 1);";
                break;
            case 'cancelBan':
                $query = "DELETE FROM `friends` WHERE (`uid` = '$uid' AND `pid` = '$pid') AND `ban` = 1 LIMIT 1;";
                break;
        }
        
        if ($query)
            return $this->query($query);
        else
            return false;
    }
    
}

/* End of file */