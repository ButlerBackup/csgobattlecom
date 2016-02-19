<div class="box">
    <h1>{L:NEWS_TITLE}</h1>
    <div id="status"></div>

    <?php
    if (Request::getRole() == 'admin') {
        echo '<div class="formRow">'
            .'<input type="hidden" id="nid" value="0">'
            .'<div class="formRowTitle w350"><input class="w350" type="text" id="name" placeholder="{L:NEWS_NAME}"></div>'
            .'<div class="formRowField m380"><div class="btn" onclick="'.ajaxLoad(url('admin', 'add_news'), 'add_news', '#name!|#nid!').'">{L:NEWS_ADD}</div></div>'
        .'</div>';
    }
    ?>

    <div><legend>{L:NEWS_LIST}:</legend></div>
    <?php
    echo '<div id="news_list" class="drop_list">';
        if ($this->list) {
            foreach ($this->list as $value) {
                if ($value->status == 1)
                    $status = '<span class="c_green">{L:NEWS_SHOWN}</span>';
                else
                    $status = '<span class="c_red">{L:NEWS_HIDDEN}</span>';

                echo '<div id="n_'.$value->id.'">'
                    .'<a href="'.url('admin', 'lang_news', $value->id).'">'.$value->name.' (ID:'.$value->id.')</a>'
                    .' ('.$status.')';
                    if (Request::getRole() == 'admin') {
                        echo ' - <a onclick="'.ajaxLoad(url('admin', 'act_news'), 'act_news', 'act:approve|id:'.$value->id).'">{L:NEWS_APPROVE_'.$value->status.'}</a>';
                        if (Request::getParam('user')->id == 1)
                            echo ' | <a onclick="'.ajaxLoad(url('admin', 'act_news'), 'act_news', 'act:delete|id:'.$value->id).'">{L:NEWS_DELETE}</a>';
                    }
                echo '</div>';
            }
        }
    echo '</div>';

    ?>
</div>