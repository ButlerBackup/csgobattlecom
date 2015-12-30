<?php
echo '<h1 class="news-title"><span>{L:MAPS_TITLE}</span></h1>';

echo '<div id="maps_list">';
if ($this->list) {
    while ($list = mysqli_fetch_object($this->list)) {
        $images = '';
        $imagesObj = $this->model->getMapImages($list->id);

        while ($listImg = mysqli_fetch_object($imagesObj)) {
            $images .= '<div id="img_small_'.$listImg->id.'" class="map_img map_img_big"><img src="'.$listImg->img.'"></div>';
        }

        echo '<div class="map_row">'
            .'<div id="map_name_'.$list->id.'" class="map_row_name">'.$list->name.'</div>'
            .'<div id="map_desc_'.$list->id.'">'.$list->description.'</div>'
            .'<div id="map_img_'.$list->id.'">'.$images.'</div>'
            .'<div id="desc_'.$list->id.'"></div>'
            .'</div>';
    }
}
echo '</div>';