 <link rel="stylesheet" href="/public/js/jq_slider/jquery-ui.css">

<div id="body_center_content_comm">
	<div id="body_center_left_column_comm">
		

		<div class="site_part">
			<div class="site_part_container">
				<!-- <div class="site_part_h4">КОМИССИОНКА</div>-->
				<?php echo $block_cat_comission; ?>    
			</div>
                       
                        
		</div>
		<!-- menu button -->
                        <div class="block_menu">
                            <a href="/market/comissionka"><img src="/public/images/comiss/kom.png" onmouseover="this.src='/public/images/comiss/kom_act.png';" onmouseout="this.src='/public/images/comiss/kom.png';"></a>
       <img src="/public/images/market/rinok.png" onmouseover="this.src='/public/images/market/rinok_activ.png';" onmouseout="this.src='/public/images/market/rinok.png';">
    
<!--<a href="/market/gift_product"><img src="/public/images/market/change.png" onmouseover="this.src='/public/images/market/change_active.png';" onmouseout="this.src='/public/images/market/change.png';"> </a>-->
       <!--<img src="/public/images/market/game.png" onmouseover="this.src='/public/images/market/game_active.png';" onmouseout="this.src='/public/images/market/game.png';">-->
       <img src="/public/images/market/bazar.png" onmouseover="this.src='/public/images/market/bazar_activ.png';" onmouseout="this.src='/public/images/market/bazar.png';">
       <!--<img src="/public/images/market/torg.png" onmouseover="this.src='/public/images/market/torg_activ.png';" onmouseout="this.src='/public/images/market/torg.png';">-->
   
                        </div>
        </div>
    <!-- ########################################################################################################### -->



	<div id="body_center_tovar">
            <div id="flag"><a href="/market/comissionka"><img src="/public/images/market/flag.png"></a></div>
            <?php echo $block_gift_change_tovar; 
          
          
function link_bar($page, $pages_count)
{
for ($j = 1; $j <= $pages_count; $j++)
{
    
// Вывод ссылки
if ($j == $page) {
echo '<a style="color: #FF6347;" href="/market/comissionka/'.$page.'">'
        . '<li style="background-color:#bc9de; border:1px solid #863de9; border-radius:10px; color:white">'.$j.'</li></a> ';
} else {

echo "<a style='color: #FF6347;' href='/market/comissionka/$page' ><li style='display:table; margin-right:5px;
     width:20px;height:20px;padding-top:1px;float:left; position:relative;
     background: #ba9ce7; border:1px solid #863de9; border-radius:16px; color:white'>$j</li></a>";
}
// Выводим разделитель после ссылки, кроме последней
// например, вставить "|" между ссылками
if ($j != $pages_count) echo ' ';
$page=$page+6;
}
return true;
}
if ($page > $pages_count){ $page = $pages_count;}
$per=6;
$pages_count = ceil($block_count_tovar / $per); // Количество страниц

echo "<div style='clear:both; position:relative;margin-top:20px; display:inline-block; width:100%;text-align:center'>
    <ul style='position: relative;margin: 0 auto;width:50%;'>";
link_bar(0, $pages_count);
echo "</ul></div>";

                                    include("./core/pagination.php"); 
                                ?>                          
        </div>



	<div id="body_center_right_column">
            <div id="add_advert">
               <a href="/market/addtovargift"><img src="/public/images/market/add.png" onmouseover="this.src='/public/images/market/add_active.png';" onmouseout="this.src='/public/images/market/add.png';"></a>
            </div>
            <div id="filtr">
                 <form method="post" class="form_filtr" action="/market/filtr/">
                    <h4 class="header_filtr"> <input type="submit" value="ПОИСК ТОВАРОВ" style="font-weight: 700;width:80%;color:#4C07BB;"></h4><ul class="radio-group"> <br>
                        <input type="radio" name="bye_sale" checked="checked" value="b" id="bye"><label for="bye">
                                        <i><i></i></i>
                                        <span>Куплю</span>
                                    </label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="bye_sale"      id="sale"        value="s"><label for="sale">
                                        <i><i></i></i>
                                        <span>Продам</span>
                                    </label>  <br>
                        <div class="hr"></div><br>
                       <select name="id_cat" required style="color: #787578;font-size: 10pt;font-weight: bold;">
                           <option value="">-- выбрать категорию--</option>
                           <?php
                           echo  $block_cat_option; 
                           ?>
                       </select>
                        <div class="hr"></div><br>
                        
                        <div class="in_fil"> 
                            <span >Цена (грн.):</span>
                        </div>
                        <br>
                        <p>

  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
 
<div id="slider"></div>
                        <span>от</span>  &nbsp;<input type="text" class="r_border" id="ot" name="ot">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>до</span> &nbsp;<input type="text" class="r_border" id="do" name="do"> 
                        <br> 
                        <div class="hr"></div><br>
                        <div class="in_fil"> 
                            <span>Состояние товара:</span><br>
                        </div> 
                            <li>
                                    <input id="choice-a" type="radio" checked="checked" name="stan" value="n">
                                    <label for="choice-a">
                                        <i><i></i></i>
                                        <span>новый</span>
                                    </label>
                            </li>
                            <li>
                                    <input id="choice-b" type="radio" name="stan" value="g">
                                    <label for="choice-b">
                                        <i><i></i></i>
                                         <span>б/у в отличном состоянии</span>
                                    </label>
                            </li>
                            <li>
                                    <input id="choice-c" type="radio" name="stan" value="u">
                                    <label for="choice-c">
                                        <i><i></i></i>
                                        <span> б/у</span>
                                    </label>
                            </li>
                    <div class="hr"></div><br>
                    <div class="in_fil"> 
                        <span >Пол ребенка:</span><br>
                    </div>
                        <li>
                            <input id="choice-d" type="radio" checked="checked" name="sex" value="b">
                                    <label for="choice-d">
                                        <i><i></i></i>
                                        <span>мальчик</span>
                                    </label>
                            </li>
                            <li>
                                    <input id="choice-e" type="radio" name="sex" value="g">
                                    <label for="choice-e">
                                        <i><i></i></i>
                                         <span>девочка</span>
                                    </label>
                            </li>
                            <li>
                                    <input id="choice-f" type="radio" name="sex" value="u">
                                    <label for="choice-f">
                                        <i><i></i></i>
                                        <span> для обоих полов</span>
                                    </label>
                            </li>
                    </ul>
                   <a href='/market/comissionka' ><img src="/public/images/comiss/reset.png"></a>
                    </form>
                </div>
                <div class="middle_right_block_adv">
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- NewBlock300x250 -->
                            <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-7523142643336947" data-ad-slot="6347754113"></ins>
                            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>



                    <div class="middle_right_block_adv">
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- NewBlock300x250 -->
                            <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-7523142643336947" data-ad-slot="6347754113"></ins>
                            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
                    </div>

                    </div>

    </div>
 <script src="/public/js/jquery.js"></script>
 <script src="/public/js/jq_slider/jquery-ui.js"></script>
 <!--<link rel="stylesheet" href="/resources/demos/style.css"> -->
  <script>
  $(function() {
    $( "#slider" ).slider({
      range: true,
      min: 0,
      max: 10000,
      values: [ 1, 10000 ],
      slide: function( event, ui ) {
        $( "#ot" ).val( ui.values[ 0 ]);
        $( "#do" ).val( ui.values[ 1 ]);
      }
    });
    $( "#ot" ).val($( "#slider" ).slider( "values", 0 ));
    $( "#do" ).val($( "#slider" ).slider( "values", 1 )); 
  });
  
  
  </script>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

