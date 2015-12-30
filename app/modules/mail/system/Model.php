<?php
class MailModel extends Model
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

    public function friendsStatus($uid, $pid)
    {
        $query = "SELECT `uid`,`pid`,`status`,`ban` FROM `friends` WHERE (`uid` = '$uid' AND `pid` = '$pid') OR (`uid` = '$pid' AND `pid` = '$uid') LIMIT 1; ";

        return $this->fetch($this->query($query),'assoc');
    }

    public function addDialog($data)
    {
        return $this->insert('dialog', $data);
    }

    public function countDialog($uid)
    {
        $sql = "
            SELECT COUNT(`id`)
            FROM `dialog`
            WHERE
                `time` > 0 AND (`uid1` = '$uid' OR `uid2` = '$uid')
        ";

        return $this->fetch($this->query($sql), 'row')[0];
    }

    public function selectDialog($uid, $start, $end)
    {
        $sql = "
            SELECT
                dl.id,
                dl.uid1,
                dl.uid2,
                dl.time,
                dl.countMsg1,
                dl.countMsg2,
                (SELECT mm.message FROM `messages` mm WHERE mm.did = dl.id ORDER BY mm.id DESC LIMIT 1) as 'message',
                (SELECT us.nickname FROM `users` us WHERE (us.id = dl.uid2 AND us.id != '$uid') OR (us.id = dl.uid1 AND us.id != '$uid')) as 'nickname'
            FROM
                `dialog` dl
            WHERE
                dl.time > 0 AND (dl.uid1 = '$uid' OR dl.uid2 = '$uid')
            ORDER BY dl.time DESC
            LIMIT $start, $end
        ";

        return $this->query($sql);
    }

    public function getDialogByUsers($uid1, $uid2)
    {
        $sql = "
            SELECT *
            FROM `dialog`
            WHERE
                (`uid1` = '$uid1' AND `uid2` = '$uid2')
            LIMIT 1
        ";

        return $this->fetch($this->query($sql));
    }

    public function getDialogByID($id)
    {
        $sql = "
            SELECT *
            FROM `dialog`
            WHERE `id` = '$id'
            LIMIT 1
        ";

        return $this->fetch($this->query($sql));
    }

    public function getMessages($did, $desc = 'DESC', $from = false)
    {
        $sql = "
            SELECT *
            FROM `messages`
            WHERE
                `did` = '$did'
        ";

        if ($from)
            $sql .= " AND `id` > '$from'";

        $sql .= " ORDER BY `id` $desc";

        if (!$from)
            $sql .= " LIMIT 100";

        return $this->getAll($this->query($sql), 'assoc');
    }

    public function setReadMessages($did, $uid)
    {
        $fields['read'] = 1;
        return $this->update('messages', $fields, "`did` = '$did' AND `pid` = '$uid'");
    }
}

/* End of file */