<?php
/**
 * PAGINATION
 */

class Pagination
{
    static public $page = 1;
    static public $countPage = 0;
    static public $start = 0;
    static public $end = 20;
    static public $allRecords = 0;

    /**
     * @param int $page
     * @param int $end
     * @param bool $allRecords
     */
    static public function calculate($page, $end = 20, $allRecords = false)
    {
        self::$page = intval($page);
        self::$countPage = ceil($allRecords / $end);
        self::$end = $end;
        self::$allRecords = $allRecords;

        if (self::$page <= 1 OR self::$page > self::$countPage) {
            self::$page = 1;
            self::$start = 0;
        } else
            self::$start = (self::$page-1) * self::$end;
    }

    /**
     * @param int $count
     * @param string $separate
     * @param string $partUrl
     * @return null|string
     */
    static public function printPagination($count = 2, $separate = 'span', $partUrl = '')
    {
        $html = null;

        if (self::$page > 1)
            $html .= '<'.$separate.'><a href="?'.$partUrl.'page='.(self::$page-1).'" title="'.Lang::translate('PAGINATION_PREV').'">'.Lang::translate('PAGINATION_PREV').'</a></'.$separate.'>';

        if (self::$page > $count)
            $from = self::$page - $count;
        else
            $from = 1;

        if (self::$countPage - self::$page >= $count)
            $to = self::$page + $count;
        else
            $to = self::$countPage;

        if (self::$page > $count+1)
            $html .= '<'.$separate.'><a href="?'.$partUrl.'page=1">1</a></'.$separate.'>';
        if (self::$page > $count+2)
            $html .= '<'.$separate.' class="interspace">...</'.$separate.'>';

        for ($from; $from <= $to; $from++)
        {
            if (self::$page == $from)
                $html .= '<'.$separate.' class="active">'.$from.'</'.$separate.'>';
            else
                $html .= '<'.$separate.'><a href="?'.$partUrl.'page='.$from.'">'.$from.'</a></'.$separate.'>';
        }

        if (self::$page + $count < self::$countPage-1)
            $html .= '<'.$separate.' class="interspace">...</'.$separate.'>';
        if (self::$page + $count < self::$countPage)
            $html .= '<'.$separate.'><a href="?'.$partUrl.'page='.self::$countPage.'">'.self::$countPage.'</a></'.$separate.'>';

        if (self::$page < self::$countPage)
            $html .= '<'.$separate.'><a href="?'.$partUrl.'page='.(self::$page+1).'" title="'.Lang::translate('PAGINATION_NEXT').'">'.Lang::translate('PAGINATION_NEXT').'</a></'.$separate.'>';

        return $html;
    }


    static public function ajaxPagination($count = 2, $separate = 'span', $data = array())
    {
        $html = null;

        if (self::$page > 1)
            $html .= '<'.$separate.'><a href="'.$data['href'].'" onclick="'.ajaxLoad($data['url'], $data['permit'], $data['fields'].'page:'.(self::$page-1)).'" title="'.Lang::translate('PAGINATION_PREV').'">'.Lang::translate('PAGINATION_PREV').'</a></'.$separate.'>';

        if (self::$page > $count)
            $from = self::$page - $count;
        else
            $from = 1;

        if (self::$countPage - self::$page >= $count)
            $to = self::$page + $count;
        else
            $to = self::$countPage;

        if (self::$page > $count+1)
            $html .= '<'.$separate.'><a href="'.$data['href'].'" onclick="'.ajaxLoad($data['url'], $data['permit'], $data['fields'].'page:1').'">1</a></'.$separate.'>';
        if (self::$page > $count+2)
            $html .= '<'.$separate.'><a class="interspace">...</a></'.$separate.'>';

        for ($from; $from <= $to; $from++)
        {
            if (self::$page == $from)
                $html .= '<'.$separate.'><a class="active">'.$from.'</a></'.$separate.'>';
            else
                $html .= '<'.$separate.'><a href="'.$data['href'].'" onclick="'.ajaxLoad($data['url'], $data['permit'], $data['fields'].'page:'.$from).'">'.$from.'</a></'.$separate.'>';
        }

        if (self::$page + $count < self::$countPage-1)
            $html .= '<'.$separate.'><a class="interspace">...</a></'.$separate.'>';
        if (self::$page + $count < self::$countPage)
            $html .= '<'.$separate.'><a href="'.$data['href'].'" onclick="'.ajaxLoad($data['url'], $data['permit'], $data['fields'].'page:'.self::$countPage).'">'.self::$countPage.'</a></'.$separate.'>';

        if (self::$page < self::$countPage)
            $html .= '<'.$separate.'><a href="'.$data['href'].'" onclick="'.ajaxLoad($data['url'], $data['permit'], $data['fields'].'page:'.(self::$page+1)).'" title="'.Lang::translate('PAGINATION_NEXT').'">'.Lang::translate('PAGINATION_NEXT').'</a></'.$separate.'>';

        return $html;
    }
}
/* End of file */