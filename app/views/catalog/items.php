<div id="body_center_content">
    <div class="twocolumnslayout_right_fixed_column">
        <?php EchoAdvertisementBlockHTML(); ?>
        <?php EchoInterestingBlockHTML(); ?>
        <?php EchoAdvertisementBlockHTML(); ?>
    </div>

	
    <div class="twocolumnslayout_left_relative_column">
        <div class="catalog_block margin_bottom_15" style="padding-left: 15px;">
            <div class="text_rounded_background margin_bottom_15" style="position: relative;">
                <div class="header">Каталог организаций - <span><?php echo $SubCategoryName; ?></span></div>
                
                <div class="sort_line" style="padding-left: 15px; margin-top: 20px;">
                    <select id="RegionSel" name="RegionSel" class="myselect" onchange="if (this.value) window.location.href=this.value" style="width: 200px;">
                        <?php 
                            $baseurl = "/catalog/p-".$id.'?reg=';
                            echo '<option value="'.$baseurl.'0">--- Все области ---</option>';
                            foreach ($regions as $r) {
                                if ($SelectedRegionID === $r['ID']) {
                                    echo '<option value="'.$baseurl.$r['ID'].'" selected>'.$r['Name'].'</option>';
                                } else {
                                    echo '<option value="'.$baseurl.$r['ID'].'">'.$r['Name'].'</option>';
                                }
                            }
                        ?>
                    </select>
                        
                    <select id="LocalitySel" name="LocalitySel" class="myselect" onchange="if (this.value) window.location.href=this.value" style="width: 215px;">
                        <?php 
                            $baseurl = "/catalog/p-".$id.'?reg='.$SelectedRegionID.'&loc=';
                            echo '<option value="'.$baseurl.'0">--- Все нас. пункты ---</option>';
                            foreach ($localities as $r) {
                                if ($SelectedLocalityID === $r['ID']) {
                                    echo '<option value="'.$baseurl.$r['ID'].'" selected>'.$r['Name'].'</option>';
                                } else {
                                    echo '<option value="'.$baseurl.$r['ID'].'">'.$r['Name'].'</option>';
                                }
                            }
                        ?>
                    </select>
                    
                    <div id="body_search" style="top:64px;">
                        <form action="" method="post">
                            <input name="SearchText" placeholder="Поиск..." value="<?php echo $SearchText ?>">
                            <button type="submit"><img src="<?php echo URL; ?>public/images/cat_search_but.png" alt="" /></button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="theme_block text_rounded_background">
                <table width="100%">
                    <tbody>   			
                        <?php 
                            if (count($items) == 0) {
                                echo "<tr><td>Нет данных.</td></tr>";
                            } else {
                                $i = (($pagination['current']-1)*$pagination['perpage'])+1;
                                foreach ($items as $k=>$a) { ?>
                                    <tr onclick="window.location='/catalog/i-<?php echo $a['ID']; ?>'">
                                        <td class="blck1"><?php echo $i; ?></td>
                                        <td class="blck2"><?php echo "<span>".$a["name"]."</span><br/>".$a["adress"]; ?></td>
                                        <td class="blck3"><?php echo empty($a["RegionName"]) ? "" : $a["RegionName"].",<br/>".$a["Locality"]; ?></td>
                                        <td class="blck4"><?php echo $a["kont_tell"]; ?></td>
                                        <td class="blck6"><?php GetRaitingHTML($a['TotalRating']); ?></td>
                                        <td class="blck5"><img src="<?php echo URL; ?>public/images/comment.png"><?php echo $a["CountComments"];?></td>
                                    </tr>
                                <?php 
                                    $i++;
                                } 
                            }
                        ?>
                    </tbody>
                </table>
                
                <?php 
                    if ($pagination['total_pages'] > 1) {
                        echo '<div style="min-height: 40px; padding: 15px;">';
                            include("./core/pagination.php"); 
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>