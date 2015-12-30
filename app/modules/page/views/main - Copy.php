 <h1>NEWS</h1>

<?php

if ($this->newsList) {

    echo '<h1>{L:MAIN_TITLE}</h1>';

    echo '<div>';

    while ($news = mysqli_fetch_object($this->newsList))

    {

        $length = mb_strlen($news->text);

        if ($length > 300)

            $text = mb_substr($news->text, 0, 300).'...';

        else

            $text = $news->text;



        echo '<div class="news">'

            .'<div class="lnName"><a href="{URL:main/read/'.$news->id.'}">'.$news->name.'</a> <span>/ '.printTime($news->time, "m.d.Y").'</span></div>'

            .'<div>'.$text.'</div>'



            .'<div class="lnInfo">'

            .'<div class="lnNote">'

            //.'{L:MAIN_DATE}: <span>'.printTime($news->time, "m.d.Y").'</span>'

            //.'<span class="views-icon" style="margin-left: 50px;">'.$news->views.'</span>'

            //.'<span class="comm-icon" style="margin-left: 50px;">'.$news->comments.'</span>'

            .'</div>';

        if (is_file(_SYSDIR_.'public/news/'.$news->id.'.png'))

            echo '<div><img src="'._SITEDIR_.'public/news/'.$news->id.'.png" height="300"></div>';

        echo '</div>'

            .'</div>';

    }

    echo '</div>';

    echo '<div class="pagin">'.Pagination::printPagination().'</div>';

} else {

    echo '<h1>'.$this->news->name.'</h1>';

    echo '<div class="news">'

        .'{L:MAIN_DATE}: <span>'.printTime($this->news->time, "m.d.Y").'</span>'

        .'<div>'.$this->news->text.'</div>';

    if (is_file(_SYSDIR_.'public/news/'.$this->news->id.'.png'))

        echo '<div><img src="'._SITEDIR_.'public/news/'.$this->news->id.'.png" height="300"></div>';

    echo '</div>';

}