<?php if (isset($pagination) && $pagination['total_pages'] > 1) { 
    //$vURL = GetPOSTorGETValue('_url');  
    //$vURLWithPage = $vURL.'/?page=';
    $vURL = GetURLPath();
    //$vURLWithPage = $vURL.'?page=';
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
    }

    echo '<div class="mypagination"><ul style="display: inline; padding: 0;">';
    // <li class="first"><a href="< ?php echo ($pagination['current'] == 1) ? '#' : $vURLWithPage.'1'; ? >"><<</a></li>
    if ($pagination['current'] == 1) { 
        echo '<li class="prev isdisabled"><a>< Пред.</a></li>';
    } else {
        echo '<li class="prev"><a href="'.$vURLWithPage.($pagination['current']-1).'">< Пред.</a></li>';
    }
    
    
    if ($pagination['total_pages'] <= 7) { 
        for ($i = 1; $i <= $pagination['total_pages']; $i++) {
            $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
            echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.$i.'">'.$i.'</a></li>';
        };
    } else {
        if ($pagination['current'] <= 4) {
            for ($i = 1; $i <= 5; $i++) {
                $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
                echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.$i.'">'.$i.'</a></li>';
            };
            echo '<li style="font-weight: normal;">...</li>';
            
            $ActiveClass = (($pagination['total_pages'] == $pagination['current']) ? "active" : "normal");
            echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.$pagination['total_pages'].'">'.$pagination['total_pages'].'</a></li>';
        } elseif ($pagination['current'] >= $pagination['total_pages']-3) {
            $ActiveClass = ((1 == $pagination['current']) ? "active" : "normal");
            echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.'1'.'">1</a></li>';
            echo '<li style="font-weight: normal;">...</li>';
            
            for ($i = $pagination['total_pages']-4; $i <= $pagination['total_pages']; $i++) {
                $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
                echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.$i.'">'.$i.'</a></li>';
            };
        } else {
            $ActiveClass = ((1 == $pagination['current']) ? "active" : "normal");
            echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.'1'.'">1</a></li>';
            echo '<li style="font-weight: normal;">...</li>';
            
            for ($i = $pagination['current']-2; $i <= $pagination['current']+2; $i++) {
                $ActiveClass = (($i == $pagination['current']) ? "active" : "normal");
                echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.$i.'">'.$i.'</a></li>';
            };
            
            echo '<li style="font-weight: normal;">...</li>';
            $ActiveClass = (($pagination['total_pages'] == $pagination['current']) ? "active" : "normal");
            echo '<li class="'.$ActiveClass.'"><a href="'.$vURLWithPage.$pagination['total_pages'].'">'.$pagination['total_pages'].'</a></li>';
        };
    };

    if ($pagination['current'] >= $pagination['total_pages']) { 
        echo '<li class="next isdisabled"><a>След. ></a></li>';
    } else {
        echo '<li class="next"><a href="'.$vURLWithPage.($pagination['current']+1).'">След. ></a></li>';
    }
    //<li class="first"><a href="< ?php echo ($pagination['current'] >= $pagination['total_pages']) ? '#' : $vURLWithPage.$pagination['total_pages']; ? >">>></a></li>

    echo '</ul></div>';
}; 
?>