<div class="box">

    <?php

    $n = 0;

    while ($list = mysqli_fetch_object($this->list))

    {

        $n++;

        echo '<div>';

            echo $n.') <img src="'.getAvatar($list->id, 's').'" alt=""> <a href="'.url($list->id).'">'.$list->nickname.'</a>';

        echo '</div>';

    }

    ?>

</div>