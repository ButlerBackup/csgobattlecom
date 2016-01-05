<?php

class ProfileModel extends Model
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

    public function getUserInfo($id, $columns)
    {
        if ($id and $columns) {
            $columnsText = '';

            foreach ($columns as $column) {
                $columnsText .= $column . ',';
            }

            $columnsText = trim($columnsText, ',');
            $sql = "
                SELECT $columnsText
                FROM `users`
                WHERE `id` = $id
                LIMIT 1
            ";

            return $this->query($sql);
        }
    }


    public function countNotice($uid)
    {
        $sql = "
            SELECT COUNT(`id`)
            FROM `notice`
            WHERE `uid` = '$uid'
        ";

        return $this->fetch($this->query($sql), 'row')[0];
    }

    public function getNotice($uid, $start, $end)
    {
        $sql = "
            SELECT *
            FROM `notice`
            WHERE `uid` = '$uid'
            ORDER BY `time` DESC
            LIMIT $start, $end
        ";

        return $this->query($sql);
    }


    public function getMatchUsers($id1, $id2)

    {

        $sql = "

            SELECT `id`, `nickname`, `elo`, `country`, `wins`, `ties`, `losses`

            FROM `users`

            WHERE `id` = '$id1' OR `id` = '$id2'

        ";


        return $this->getAll($this->query($sql));

    }

    /********** DISCOVER PAGE Methods ***************/
    public function getDiscoverPageList($uid, $from, $to)

    {

        $sql = "

            SELECT  users.dateLast,users.nickname as username, discover_page.*

            FROM `discover_page`

            LEFT JOIN users ON users.id = discover_page.uid

            WHERE (discover_page.available = 1
              OR ( discover_page.last_looking > '" . (time() - 3600) . "'  AND discover_page.looking = 1 ))
              AND  users.dateLast > '" . (time() - 300) . "'

              ORDER BY discover_page.last_looking DESC

            LIMIT $from, $to

        ";


        return $this->query($sql);

    }

    public function checkDiscoverRecord($uid)
    {
        $sql = "
            SELECT id,looking,available

            FROM discover_page

            WHERE uid = '$uid' ";


        return $this->fetch($this->query($sql));
    }

    public function updateDiscoverRecord($id, $updateString)
    {
        $sql = "

            UPDATE  discover_page

            SET $updateString

            WHERE id = '$id'   ";


        return $this->query($sql);
    }

    public function insertDiscoverRecord($column, $value)
    {
        $sql = "

            INSERT INTO  discover_page( $column )

            VALUE ( $value );  ";


        return $this->query($sql);
    }
// /************ END DISCOVER PAGE Methods  SECTION**********************


    /*-------------------- STAMINA -----------------*/
    public function checkStamina($id)
    {

        $refillTime = time() - 60 * 60 * 5;

        $sql = "UPDATE users

                    SET stamina = case
                      when last_stamina_changes < $refillTime then stamina_max
                      else stamina
                     end

                 WHERE id = $id
                ";

        $this->query($sql);

    }


    public function getStamina($id)
    {

        $sql = "SELECT stamina
                FROM users
                WHERE id = $id
                LIMIT 1
                ";
        $staminaRes = $this->fetch($this->query($sql));

        return $staminaRes->stamina;

    }

    public function getStaminaMax($id)
    {

        $sql = "SELECT stamina_max
                FROM users
                WHERE id = $id
                LIMIT 1
                ";
        $staminaRes = $this->fetch($this->query($sql));

        return $staminaRes->stamina_max;

    }

    public function updateStamina($id, $value)
    {

        $sql = "UPDATE users
                    SET stamina = case
                      when stamina+$value <= stamina_max then stamina+$value
                      else stamina_max
                     end,
                      last_stamina_changes = case
                      when stamina+$value <= stamina_max then " . time() . "
                      else last_stamina_changes
                     end
                WHERE id = $id
                LIMIT 1
        ";
        $this->query($sql);
    }

    /*-------------------- END STAMINA -----------------*/


    public function addMessage($data)

    {

        return $this->insert('match_chat', $data);

    }


    public function getChatMessages($mid, $from = null)

    {

        $sql = "

            SELECT *

            FROM `match_chat`

            WHERE `mid` = '$mid'

        ";


        if (!is_null($from))

            $sql .= " AND `id` > '$from'";


        $sql .= " ORDER BY `id` ASC";


        if (is_null($from))

            $sql .= " LIMIT 100";


        return $this->query($sql);

    }


    public function getLadderList($min = 0, $max = 799, $start, $count)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE `ladder` = '1' AND `elo` >= '$min' AND `elo` <= '$max'

            ORDER BY `wins` DESC

            LIMIT $start, $count

        ";


        return $this->query($sql);

    }

    /*      MAIN PAGE METHODS      */
    public function getTopLadder($limit)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE `ladder` = '1'

            ORDER BY `elo` DESC

            LIMIT $limit

        ";

        return $this->query($sql);

    }

    public function getLastRegistered($limit)
    {
        $sql = "
            SELECT dateReg,nickname,id as uid
            FROM `users`
            WHERE `role` LIKE 'user'
            ORDER BY id DESC
            LIMIT $limit
        ";

        return $this->query($sql);
    }

    public function getLastMatchesList($limit)
    {
        $sql = "
            SELECT matches.id, matches.uwin, matches.pwin,uid,pid, startTime
            FROM matches
            WHERE `end` LIKE 'success'
            ORDER BY id DESC
            limit $limit
        ";

        return $this->query($sql);
    }

    /**********************************************************/


    public function countLadderList($min = 0, $max = 799)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `users`

            WHERE `ladder` = '1' AND `elo` >= '$min' AND `elo` <= '$max'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }

    public function getServersList()
    {

        return $this->getAll($this->query("SELECT * FROM `servers`;"));

    }

    public function getServerLock($sid, $interval)
    {

        return $this->fetch($this->query("
          SELECT * FROM `matches`
          WHERE `sid` = '$sid' AND
          FROM_UNIXTIME(`startTime`) BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL '$interval' MINUTE)) AND timestamp(NOW())
          LIMIT 1
          ;"
        ));

    }

    public function setMatchServer($matchID, $serverID) {

        return $this->update('matches', array('sid' => $serverID), "`id` = '$matchID' ");

    }

    public function getMatchByID($id)

    {

        $sql = "

            SELECT *

            FROM `matches`

            WHERE `id` = '$id'

            LIMIT 1

        ";


        return $this->fetch($this->query($sql));

    }


    public function getMatchesByUP($uid, $pid)

    {

        $sql = "

            SELECT *

            FROM `matches`

            WHERE (`uid` = '$uid' AND `pid` = '$pid' OR `uid` = '$pid' AND `pid` = '$uid') AND `status` != '2'

            LIMIT 1

        ";


        return $this->fetch($this->query($sql));

    }


    public function getMatchesList($uid, $start, $count)

    {

        $sql = "

            SELECT m.id, u.nickname, u.country, m.uready, m.pready

            FROM `matches` m, `users` u

            WHERE (m.uid = '$uid' OR m.pid = '$uid') AND m.status = '1' AND u.id != '$uid' AND (u.id = m.uid OR u.id = m.pid)

            ORDER BY m.startTime DESC

            LIMIT $start, $count

        ";


        return $this->query($sql);

    }


    public function countMatchesList($uid)
    {
        $sql = "
            SELECT COUNT(*)
            FROM `matches`
            WHERE (`uid` = '$uid' OR `pid` = '$uid') AND `status` = '1'
        ";

        return $this->fetch($this->query($sql), 'row')[0];
    }

    public function countMatchesHistory($uid)
    {
        $sql = "
            SELECT COUNT(*)
            FROM `matches`
            WHERE (`uid` = '$uid' OR `pid` = '$uid') AND `status` = '2'
        ";

        return $this->fetch($this->query($sql), 'row')[0];
    }

    public function getChallengesList($uid, $start, $count)
    {
        $sql = "

            SELECT m.id, u.nickname, u.country

            FROM `matches` m, `users` u

            WHERE m.pid = '$uid' AND m.status = '0' AND u.id != '$uid' AND u.id = m.uid

            ORDER BY m.startTime DESC

            LIMIT $start, $count

        ";

        return $this->query($sql);
    }

    public function getHistoryList($uid, $start, $count)
    {
        $sql = "

            SELECT m.id, u.nickname, u.country

            FROM `matches` m, `users` u

            WHERE (m.pid = '$uid' AND m.status = '2' AND u.id != '$uid' AND u.id = m.uid)
              OR (m.uid = '$uid' AND m.status = '2' AND u.id != '$uid' AND u.id = m.pid)

            ORDER BY m.startTime DESC

            LIMIT $start, $count

        ";

        return $this->query($sql);
    }

    public function countChallengesList($uid)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `matches`

            WHERE `pid` = '$uid' AND `status` = '0'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }


    public function getRattingHistory($uid, $pid)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `rating_history`

            WHERE `uid` = '$uid' AND `pid` = '$pid' AND `time` > '" . (time() - 24 * 3600) . "'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }


    public function countRattingToday($uid)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `rating_history`

            WHERE `uid` = '$uid' AND `time` > '" . (time() - 24 * 3600) . "'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }


    public function setSteamID($id, $steamID)

    {

        $fields['steamid'] = null;

        $this->update('users', $fields, "`steamid` = '$steamID'");


        $fields['steamid'] = $steamID;

        return $this->update('users', $fields, "`id` = '$id'");

    }


    public function countRefByID($id)

    {

        $sql = "

            SELECT COUNT(*)

            FROM `users`

            WHERE `referral` = '$id'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }


    // Count messages for user

    public function countMsg($id)

    {

        $sql = "

            SELECT

                (SELECT sum(d1.countMsg1) FROM `dialog` d1 WHERE d1.`uid1` = '$id') as `count`

            UNION

            SELECT

                (SELECT sum(d2.countMsg2) FROM `dialog` d2 WHERE d2.`uid2` = '$id') as `count`

        ";


        $result = $this->getAll($this->query($sql), 'row');

        return $result[0][0] + $result[1][0];

    }


    public function countUsersOnline()

    {

        $sql = "

            SELECT COUNT(`id`)

            FROM `users`

            WHERE `dateLast` >= '" . (time() - 300) . "'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }

    public function getUsersOnline()

    {

        $sql = "

            SELECT nickname

            FROM `users`

            WHERE `dateLast` >= '" . (time() - 300) . "'

        ";


        return $this->query($sql);

    }


    public function countGuestsOnline()

    {

        $sql = "

            SELECT COUNT(`id`)

            FROM `guests`

            WHERE `time` >= '" . (time() - 300) . "'

        ";


        return $this->fetch($this->query($sql), 'row')[0];

    }


    public function getGuestByIP($ip)

    {

        $sql = "

            SELECT *

            FROM `guests`

            WHERE `ip` = '$ip'

            LIMIT 1

        ";


        return $this->fetch($this->query($sql));

    }


    // Update last visit time in preDispatch
    public function updateUserByID($data, $id)
    {
        return $this->update('users', $data, "`id` = '$id' LIMIT 1");
    }


    public function getCountryByCode($code)
    {
        return $this->select('countries', "`code` =  '$code'");
    }


    public function friendsStatus($uid, $pid)
    {
        $query = "SELECT `uid`,`pid`,`status`,`ban` FROM `friends` WHERE (`uid` = '$uid' AND `pid` = '$pid') OR (`uid` = '$pid' AND `pid` = '$uid') LIMIT 1; ";

        return $this->fetch($this->query($query), 'assoc');
    }


    public function countRequests($uid)
    {
        $query = "SELECT COUNT(*) FROM `friends` WHERE `pid` = '$uid' AND `status` = 0 AND `ban` = 0; ";

        return $this->fetch($this->query($query), 'row')[0];
    }


    public function addMatchAsset($item)

    {

        return $this->insert('assets', $item);

    }


    public function removeAsset($uid, $id)

    {

        return $this->delete('assets', " `id` = '$id' AND `uid` = '$uid' LIMIT 1");

    }


    public function getMatchAssets($uid, $mid)

    {

        $query = "SELECT * FROM `assets` WHERE `uid` = '$uid' AND `mid` = '$mid' ";


        return $this->getAll($this->query($query));

    }


    public function getMatchAsset($uid, $id)

    {

        $query = "SELECT * FROM `assets` WHERE `id` = '$id' AND `uid` = '$uid' LIMIT 1 ";


        return $this->fetch($this->query($query));

    }


    public function getCountRequestedMatchAssets($mid)

    {

        $query = "SELECT COUNT(*) FROM `assets` WHERE `mid` = '$mid' AND `requested` = '1' ";


        return $this->fetch($this->query($query), 'row')[0];

    }


    public function getCountReceivedMatchAssets($mid)

    {

        $query = "SELECT COUNT(*) FROM `assets` WHERE `mid` = '$mid' AND `requested` = '1' AND `newAssetId` IS NOT NULL; ";


        return $this->fetch($this->query($query), 'row')[0];

    }


    public function getCountMatchAssets($mid)

    {

        $query = "SELECT COUNT(*) FROM `assets` WHERE `mid` = '$mid' ";


        return $this->fetch($this->query($query), 'row')[0];

    }


    public function setMatchReady($matchID, $data)

    {

        return $this->update('matches', $data, " `id` = '$matchID' ");

    }


    public function setMatchBlocked($matchID)

    {

        return $this->update('matches', array('blocked' => '1'), " `id` = '$matchID' ");

    }


    public function getTradeLink($userID)

    {

        $query = $this->query("SELECT partner, token FROM users WHERE id = '$userID' LIMIT 1;");


        if ($query) {

            $fetch = $this->fetch($query);

            return ($fetch->partner) ? "https://steamcommunity.com/tradeoffer/new/?partner=" . $fetch->partner . "&token=" . $fetch->token : 'None';

        } else

            return false;

    }

    public function setSteamTradeLink($id, $data)
    {
        return $this->update('users', $data, "`id` = '$id' LIMIT 1");
    }

    public function updateMatchWL($mid, $data)
    {
        return $this->update('matches', $data, " `id` = '$mid' ");
    }

    public function checkMatchExist($userID, $playerID)
    {
        $query = "SELECT COUNT( id ) as matchCount
                        FROM matches
                        WHERE (  (  uid =$userID  AND pid =$playerID ) OR ( pid =$userID AND uid =$playerID )   )
                        AND STATUS NOT LIKE 2";
        $fetch = $this->fetch($this->query($query));
        return $fetch->matchCount;

    }

    public function updateWLStat($winnerId, $loserId, $eloW = false, $eloL = false)

    {

        if ($eloW)

            $winPart = ", `elo` = '$eloW'";

        else

            $winPart = "";


        if ($eloL)

            $losePart = ", `elo` = '$eloL'";

        else

            $losePart = "";


        $queryWinner = "UPDATE `users` SET `wins` = `wins` +1$winPart WHERE `id` = '$winnerId' LIMIT 1;";

        $queryLoser = "UPDATE `users` SET `losses` = `losses` +1$losePart WHERE `id` = '$loserId' LIMIT 1;";


        return $this->query($queryWinner) && $this->query($queryLoser);

    }

}



/* End of file */