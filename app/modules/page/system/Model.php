<?php

class PageModel extends Model

{

    /*-------------------- MAPS --------------------*/



    public function getMaps()

    {

        $sql = "

            SELECT *

            FROM `maps`

            ORDER BY `name` ASC

        ";



        return $this->query($sql);

    }



    public function getMapImages($mid)

    {

        $sql = "

            SELECT *

            FROM `maps_img`

            WHERE `mid` = '$mid'

        ";



        return $this->query($sql);

    }




    /*-------------------- NEWS --------------------*/



    public function countNews($lang)

    {

        $sql = "

            SELECT

                COUNT(n.`id`)

            FROM

                `news` n,

                `news_lang` l

            WHERE

                n.`status` = '1'

                AND l.`nid` = n.`id`

                AND l.`lang` = '$lang'

        ";



        return $this->fetch($this->query($sql), 'row')[0];

    }



    public function getAllNews($lang, $start, $end)

    {

        $sql = "

            SELECT

                n.`id` as 'id',

                l.`id` as 'lnid',

                l.`name` as 'name',

                l.`text` as 'text',

                n.`views` as 'views',

                n.`comments` as 'comments',

                n.`time` as 'time'

            FROM

                `news` n,

                `news_lang` l

            WHERE

                n.`status` = '1'

                AND l.`nid` = n.`id`

                AND l.`lang` = '$lang'

            ORDER BY n.`time` DESC

            LIMIT $start, $end

        ";



        return $this->query($sql);

    }



    public function getNews($id, $lang)

    {

        $sql = "SELECT

                    n.`id` as 'id',

                    l.`id` as 'lnid',

                    l.`name` as 'name',

                    l.`text` as 'text',

                    n.`status` as 'status',

                    n.`views` as 'views',

                    n.`comments` as 'comments',

                    n.`time` as 'time'

                FROM

                    `news` n,

                    `news_lang` l

                WHERE

                    n.`id` = '$id'

                    AND l.`nid` = n.`id`

                    AND l.`lang` = '$lang'

                    LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    public function getComments($nid)

    {

        $sql = "

            SELECT *

            FROM `news_comments`

            WHERE `nid` = '$nid'

            ORDER BY `id` DESC

        ";



        return $this->query($sql);

    }



    /*-----------------------------------*/



    public function getUserByEP($email, $password)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE

                `email` = '$email'

                AND `password` = '".$password."'

                LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    public function getUserByEmail($email)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE

                `email` = '$email'

                LIMIT 1

        ";



        return $this->fetch($this->query($sql))->id;

    }



    public function getUserByNickname($nickname)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE

                `nickname` = '$nickname'

                LIMIT 1

        ";



        return $this->fetch($this->query($sql))->id;

    }



    public function getUserBySteam($steamID)

    {

        $sql = "

            SELECT *

            FROM `users`

            WHERE `steamid` = '$steamID'

            LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    public function getRegCode($code)

    {

        $sql = "

            SELECT *

            FROM `reg_code`

            WHERE

                `code` = '$code'

                LIMIT 1

        ";



        return $this->fetch($this->query($sql));

    }



    public function deleteRegCode($id)

    {

        $sql = "

            DELETE FROM `reg_code`

            WHERE

                `id` = '$id'

                LIMIT 1

        ";



        return $this->query($sql);

    }

    

    public function userExist($email)

    {

        $query = "SELECT COUNT(*) FROM `users` WHERE `email` = '$email' LIMIT 1; ";

        

        return $this->fetch($this->query($query),'row')[0];

    }

    

    public function recoveryHashExist($hash, $email = false)

    {

        $query = "SELECT COUNT(*) FROM `recovery` WHERE `hash` = '$hash' ".( ($email)? "AND `email` = '$email' " : "" )." LIMIT 1; ";

        

        return $this->fetch($this->query($query),'row')[0];

    }

    

    public function createRecoveryCode($email, $hash)

    {

        $time = time();

        

        $query = "INSERT INTO `recovery`(`time`, `email`, `hash`) VALUES ('$time', '$email', '$hash' )";

        

        return $this->query($query);

    }

    

    public function resetPassword($email, $newPassword)

    {

        $newPassword = md5($newPassword);

        $query = array();

        $query[] = "UPDATE `users` SET `password` = '$newPassword' WHERE `email` = '$email' LIMIT 1; ";

        $query[] = "DELETE FROM `recovery` WHERE `email` = '$email' LIMIT 1; ";

        

        return $this->multiQuery(implode('',$query), true);

    }

    

    public function deleteOldRecovery()

    {

        $time= time()-86400;

        

        $query = "DELETE FROM `recovery` WHERE `time` < '$time' ;";

        

        return $this->query($query);

    }



    public function getUsersOnline()

    {

        $sql = "

            SELECT `id`, `nickname`

            FROM `users`

            WHERE `dateLast` >= '".(time()-300)."'

        ";



        return $this->query($sql);

    }



    public function getServers()

    {

        return $this->getAll($this->query("SELECT * FROM `servers`;"));

    }



 public function postcomment($comment,$postid)



    {

       $cur_user_id = getSession('user', false);

	   $username=Request::getParam('user')->nickname;

    $time = date('Y-m-d H:i:s');

         $query = "INSERT INTO `wp_comments`(`comment_post_ID`, `comment_author`, `comment_content`,`comment_approved`,`comment_date`,`comment_date_gmt`)

		 VALUES ('$postid', '$username', '$comment', '1','$time','$time')";



        

        return $this->query($query);



    }

}



/* End of file */