<div class="box">
    <h1><?php echo $this->news->name.' (ID:'.$this->news->id.')'; ?></h1>
    <div id="status"><?php echo getNotice(); ?></div>

    <?php
    echo '<form action="'.url('admin', 'lang_news', $this->news->id).'" method="post" enctype="multipart/form-data">'
        .'<input type="hidden" id="lnid" name="lnid" value="0">'
        .'<div><input id="name" class="w500" type="text" name="name" placeholder="{L:LANG_NEWS_NAME}">'
        //.' <input id="lang" class="w80" type="text" name="lang" placeholder="{L:LANG_NEWS_LANG}"></div>'
        .'<div>{L:LANG_NEWS_TEXT}:</div>'
        .'<div><textarea id="text" class="w800 h400" name="text"></textarea></div>'
        //.'<div>{L:LANG_NEWS_COVER}:</div>'
        //.'<div><input type="file" name="cover"></div>'
        .'<div>{L:LANG_NEWS_IMAGE}:</div>'
        .'<div><input type="file" name="image"></div>'
        .'<br/><input class="btn" type="submit" value="{L:LANG_NEWS_SAVE}">'
        .'</form>';

    echo '<div><legend>{L:LANG_NEWS_LIST}:</legend></div>';
    echo '<div id="news_list" class="drop_list">';
        if ($this->list) {
            foreach ($this->list as $value) {
                echo '<div id="n_'.$value->id.'">'
                    .'<span>'.$value->lang.' ('.$value->name.')</span>';
                    if (Request::getRole() == 'admin') {
                        echo ' - <a onclick="'.ajaxLoad(url('admin', 'act_lang_news'), 'act_lang_news', 'act:edit|lnid:'.$value->id).'">{L:LANG_NEWS_EDIT}</a>';
                        if (Request::getParam('user')->id == 1)
                            echo ' | <a onclick="'.ajaxLoad(url('admin', 'act_lang_news'), 'act_lang_news', 'act:delete|lnid:'.$value->id).'">{L:LANG_NEWS_DELETE}</a>';
                    }
                echo '</div>';
            }
        }
    echo '</div>';

    echo '<div><legend>{L:LANG_NEWS_PREVIEW}:</legend></div>';
    echo '<div id="preview"></div>';
    ?>
</div>