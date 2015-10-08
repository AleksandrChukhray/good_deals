<?php if (isset($pagination) && $pagination['total_pages'] > 1) { 
    /*$vURL = GetURLPath();
    $vURLArgs = GetURLArguments(array('page'));
    
    if ((isset($pagination['urladdargs'])) && (!empty($pagination['urladdargs']))) {
        if (empty($vURLArgs)) {
            $vURLArgs = $pagination['urladdargs'];
        } else {
            $vURLArgs = $vURLArgs.'&'.$pagination['urladdargs'];
        }
    }
    
    if (empty($vURLArgs)) {
        $vURLWithPage = $vURL.'?page=';
    } else {
        $vURLWithPage = $vURL.'?'.$vURLArgs.'&page=';
    }*/

    echo '<div class="mypagination"><ul style="display: inline; padding: 0;">';
    // <li class="first"><a href="< ?php echo ($pagination['current'] == 1) ? '#' : $vURLWithPage.'1'; ? >"><<</a></li>
    if ($pagination['current'] == 1) { 
        echo '<li><button class="prev isdisabled">< Пред.</button></li>';
    } else {
        echo '<li><button class="prev" type="submit" name="PagBtn['.($pagination['current']-1).']">< Пред.</button></li>';
    }
    
    
    if ($pagination['total_pages'] <= 7) { 
        for ($i = 1; $i <= $pagination['total_pages']; $i++) {
            $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
            echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn['.$i.']">'.$i.'</button></li>';
        };
    } else {
        if ($pagination['current'] <= 4) {
            for ($i = 1; $i <= 5; $i++) {
                $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
                echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn['.$i.']">'.$i.'</button></li>';
            };
            echo '<li style="font-weight: normal;">...</li>';
            
            $ActiveClass = (($pagination['total_pages'] == $pagination['current']) ? "active" : "normal");
            echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn['.$pagination['total_pages'].']">'.$pagination['total_pages'].'</button></li>';
        } elseif ($pagination['current'] >= $pagination['total_pages']-3) {
            $ActiveClass = ((1 == $pagination['current']) ? "active" : "normal");
            echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn[1]">1</button></li>';
            echo '<li style="font-weight: normal;">...</li>';
            
            for ($i = $pagination['total_pages']-4; $i <= $pagination['total_pages']; $i++) {
                $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
                echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn['.$i.']">'.$i.'</button></li>';
            };
        } else {
            $ActiveClass = ((1 == $pagination['current']) ? "active" : "normal");
            echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn[1]">1</button></li>';
            echo '<li style="font-weight: normal;">...</li>';
            
            for ($i = $pagination['current']-2; $i <= $pagination['current']+2; $i++) {
                $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
                echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn['.$i.']">'.$i.'</button></li>';
            };
            
            echo '<li style="font-weight: normal;">...</li>';
            $ActiveClass = (($pagination['total_pages'] == $pagination['current']) ? "active" : "normal");
            echo '<li><button class="'.$ActiveClass.'" type="submit" name="PagBtn['.$pagination['total_pages'].']">'.$pagination['total_pages'].'</button></li>';
        };
    };

    if ($pagination['current'] >= $pagination['total_pages']) { 
        echo '<li><button class="next isdisabled">След. ></button></li>';
    } else {
        echo '<li><button class="next" type="submit" name="PagBtn['.($pagination['current']+1).']">След. ></button></li>';
    }
    //<li class="first"><a href="< ?php echo ($pagination['current'] >= $pagination['total_pages']) ? '#' : $vURLWithPage.$pagination['total_pages']; ? >">>></a></li>

    echo '</ul></div>';
}; 
?>