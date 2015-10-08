<div id="body_center_content">
    <div class="twocolumnslayout_right_fixed_column">
        <?php EchoAdvertisementBlockHTML(); ?>
        <?php EchoInterestingBlockHTML(); ?>
        <?php EchoAdvertisementBlockHTML(); ?>
    </div>
	
    <div class="twocolumnslayout_left_relative_column">
        <div class="catalog_block margin_bottom_15" style="padding-left: 15px;">
            <!--<div class="header">Каталог организаций</div>-->
            <div style="position: relative; overflow: hidden;">
                <div class="category_item">
                    <div class="category_item_image" style="background:url(<?php echo URL.DIR_IMAGES.'catalog1.png'; ?>) right top no-repeat; height: 146px; margin-top: 33px;"></div>
                    <div class="category_item_header" style="background-color: #79DEE0;">Спорт</div>
                    <div class="category_item_list">
                        <?php 
                            $IsProcessed = false;
                            foreach ($cat_pod as $r) {
                                if ($r["id_cat"] == '4') {
                                    $IsProcessed = true;
                                    echo '<a href="p-'.$r["ID"].'">'.$r["name"].'<span>'.$r["CountItems"].'</span></a>';
                                } else if ($IsProcessed == true) {
                                    break;
                                }
                            }
                        ?>
                    </div>                    
                </div>
                
                <div class="category_item">
                    <div class="category_item_image" style="background:url(<?php echo URL.DIR_IMAGES.'catalog2.png'; ?>) right top no-repeat; height: 159px; margin-top: 33px;"></div>
                    <div class="category_item_header" style="background-color: #EAA266;">Образование</div>
                    <div class="category_item_list">
                        <?php 
                            $IsProcessed = false;
                            foreach ($cat_pod as $r) {
                                if ($r["id_cat"] == '5') {
                                    $IsProcessed = true;
                                    echo '<a href="p-'.$r["ID"].'">'.$r["name"].'<span>'.$r["CountItems"].'</span></a>';
                                } else if ($IsProcessed == true) {
                                    break;
                                }
                            }
                        ?>
                    </div>                    
                </div>

                <div class="category_item">
                    <div class="category_item_image" style="background:url(<?php echo URL.DIR_IMAGES.'catalog3.png'; ?>) right top no-repeat; height: 217px;"></div>
                    <div class="category_item_header" style="background-color: #FEB1D9;">Праздник</div>
                    <div class="category_item_list">
                        <?php 
                            $IsProcessed = false;
                            foreach ($cat_pod as $r) {
                                if ($r["id_cat"] == '6') {
                                    $IsProcessed = true;
                                    echo '<a href="p-'.$r["ID"].'">'.$r["name"].'<span>'.$r["CountItems"].'</span></a>';
                                } else if ($IsProcessed == true) {
                                    break;
                                }
                            }
                        ?>
                    </div>                    
                </div>
                
                <div class="category_item">
                    <div class="category_item_image" style="background:url(<?php echo URL.DIR_IMAGES.'catalog4.png'; ?>) right top no-repeat; height: 165px; margin-top: 23px;"></div>
                    <div class="category_item_header" style="background-color: #A5CA57;">Здоровье</div>
                    <div class="category_item_list">
                        <?php 
                            $IsProcessed = false;
                            foreach ($cat_pod as $r) {
                                if ($r["id_cat"] == '7') {
                                    $IsProcessed = true;
                                    echo '<a href="p-'.$r["ID"].'">'.$r["name"].'<span>'.$r["CountItems"].'</span></a>';
                                } else if ($IsProcessed == true) {
                                    break;
                                }
                            }
                        ?>
                    </div>                    
                </div>

                <div class="category_item">
                    <div class="category_item_image" style="background:url(<?php echo URL.DIR_IMAGES.'catalog5.png'; ?>) right top no-repeat; height: 199px; margin-top: 23px;"></div>
                    <div class="category_item_header" style="background-color: #A5D7FD;">Развитие</div>
                    <div class="category_item_list">
                        <?php 
                            $IsProcessed = false;
                            foreach ($cat_pod as $r) {
                                if ($r["id_cat"] == '8') {
                                    $IsProcessed = true;
                                    echo '<a href="p-'.$r["ID"].'">'.$r["name"].'<span>'.$r["CountItems"].'</span></a>';
                                } else if ($IsProcessed == true) {
                                    break;
                                }
                            }
                        ?>
                    </div>                    
                </div>

                <div class="category_item">
                    <div class="category_item_image" style="background:url(<?php echo URL.DIR_IMAGES.'catalog6.png'; ?>) right top no-repeat; height: 167px; margin-top: 23px;"></div>
                    <div class="category_item_header" style="background-color: #C4BFDE;">Прочее</div>
                    <div class="category_item_list">
                        <?php 
                            $IsProcessed = false;
                            foreach ($cat_pod as $r) {
                                if ($r["id_cat"] == '9') {
                                    $IsProcessed = true;
                                    echo '<a href="p-'.$r["ID"].'">'.$r["name"].'<span>'.$r["CountItems"].'</span></a>';
                                } else if ($IsProcessed == true) {
                                    break;
                                }
                            }
                        ?>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>