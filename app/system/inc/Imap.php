<?php

/**
 * IMAP
 */
class Imap
{
    static private $mbox = false;
    static private $htmlmsg = false;
    static private $plainmsg = false;
    static private $charset = false;
    static private $attachments = array();
    static private $messages = array();

    /**
     * @param string $host
     * @param $login
     * @param $password
     */
    static public function open($host = '{wemark.net:143/imap/notls}', $login, $password)
    {
        return self::$mbox = imap_open($host, $login, $password);
    }

    /**
     * @return array
     */
    static public function getMail()
    {
        return self::$messages;
    }

    /**
     * @param string $search
     */
    static public function search($search = 'UNSEEN')
    {
        $data = imap_search(self::$mbox, $search);
        $messages = array();

        if ($data !== false) {
            foreach ($data as $i) {
                $headerArr = imap_headerinfo(self::$mbox, $i);
                self::getMsg($i);

                $messages[] = array(
                    'from' => $headerArr->sender[0]->mailbox . "@" . $headerArr->sender[0]->host,
                    'to' => $headerArr->to[0]->mailbox . "@" . $headerArr->to[0]->host,
                    'date' => $headerArr->date,
                    'size' => $headerArr->Size,
                    'charset' => self::$charset,
                    'name' => self::decode($headerArr->sender[0]->personal),
                    'subject' => self::decode($headerArr->subject),
                    'plain' => self::$plainmsg,
                    'html' => self::$htmlmsg,
                    'attach' => self::$attachments
                );

                imap_setflag_full(self::$mbox, $i, "\\Seen");
            }

            self::$messages = $messages;
            unset($messages);
        }
    }

    /**
     * @param $enc
     * @return string
     */
    static private function decode($enc)
    {
        $parts = imap_mime_header_decode($enc);
        $str = '';
        for ($p = 0; $p < count($parts); $p++) {
            $ch = $parts[$p]->charset;
            $part = $parts[$p]->text;

            if ($ch !== 'default')
                $str .= mb_convert_encoding($part, 'UTF-8', $ch);
            else
                $str .= $part;
        }
        return $str;
    }

    /**
     * @param $mid
     */
    static private function getMsg($mid)
    {
        self::$htmlmsg = false;
        self::$plainmsg = false;
        self::$charset = false;
        self::$attachments = array();

        $s = imap_fetchstructure(self::$mbox, $mid);

        if (!$s->parts) {
            self::getPart($mid, $s, 0);
        } else {
            foreach ($s->parts as $part => $p)
                self::getPart($mid, $p, $part + 1);
        }
    }

    /**
     * @param $mid
     * @param $p
     * @param $part
     */
    static private function getPart($mid, $p, $part)
    {
        $data = ($part) ? imap_fetchbody(self::$mbox, $mid, $part) : imap_body(self::$mbox, $mid);
        if ($p->encoding == 4)
            $data = quoted_printable_decode($data);
        elseif ($p->encoding == 3)
            $data = base64_decode($data);

        $params = array();
        if ($p->parameters)
            foreach ($p->parameters as $x)
                $params[strtolower($x->attribute)] = $x->value;

        if ($p->dparameters)
            foreach ($p->dparameters as $x)
                $params[strtolower($x->attribute)] = $x->value;

        if ($params['filename'] || $params['name']) {
            $filename = ($params['filename']) ? $params['filename'] : $params['name'];
            if (self::$attachments[$filename])
                self::$attachments[time() . '_' . $filename] = $data;
            else
                self::$attachments[$filename] = $data;
        } elseif ($p->type == 0 && $data) {
            if (strtolower($p->subtype) == 'plain')
                self::$plainmsg .= trim($data) . "\n\n";
            else
                self::$htmlmsg .= $data . "<br><br>";
            self::$charset = $params['charset'];
        } elseif ($p->type == 2 && $data) {
            self::$plainmsg .= trim($data) . "\n\n";
        }

        if ($p->parts) {
            foreach ($p->parts as $partno => $p2)
                self::getpart($mid, $p2, $part . '.' . ($partno + 1));
        }
    }
}
/* End of file */