<?php

class ChatModel extends Model

{

    public function getChatMessages($table, $desc = 'DESC', $from = false)

    {

        $sql = "

            SELECT *

            FROM `$table`

        ";



        if ($from)

            $sql .= " WHERE `id` > '$from'";



        $sql .= " ORDER BY `id` $desc";



        if (!$from)

            $sql .= " LIMIT 100";



        return $this->getAll($this->query($sql), 'assoc');

    }





    public function getUserOnline()

    {

        $sql = "

            SELECT nickname,id

           FROM `users`
           WHERE  `dateLast` >= '".(time()-10)."'
           ORDER BY `id` ASC";



       return $this->getAll($this->query($sql), 'assoc');

    }



}



/* End of file */