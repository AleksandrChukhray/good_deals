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
       < 
                        </div>
        </div>
    <!-- ########################################################################################################### -->



	<div id="body_center_tovar">
            <div id="flag"><a href="/market/comissionka"><img src="/public/images/market/flag.png"></a></div>
            
        </div>



	<div id="body_center_right_column">
           
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

 <script src="http://code.jquery.com/jquery-latest.js"></script>

<script> 
             var n = noty({
      layout: 'center',
    theme: 'relax', // or 'relax'
    type: 'alert',
    text: '<img src="/public/images/comiss/acc.png"><br>\n\
<span style="color:#743586; font-weight:bold">Товар успешно добавлен в каталог! </span><br><br>\n\
<div id="look_my"><a href="/market/<?php echo "cat-$_POST[for_cat]/cardtovargift-$proba[0]";?>">Посмотреть<br> свое объявление</a></div>\n\
<div id="go_more"><a href="/market/addtovargift/">Подать еще <br> объявление</a></div>\n\
 <div style="width:30%;  height: 5px;clear: both;"></div>', // can be html or string
    dismissQueue: true, // If you want to use queue feature set this true
    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
    animation: {
        open: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceInLeft'
        close: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceOutLeft'
        easing: 'swing',
        speed: 500 // opening & closing animation speed
    },
    timeout: false, // delay for closing event. Set false for sticky notifications
    force: false, // adds notification to the beginning of queue when set to true
    modal: false,
    maxVisible: 5, // you can set max visible notification for dismissQueue true option,
    killer: false, // for close all notifications before show
    closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
    /*buttons: [
		{addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {

				// this = button element
				// $noty = $noty element

				$noty.close();
				noty({text: 'You clicked "Ok" button', type: 'success'});
			}
		},
		{addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
				$noty.close();
				noty({text: 'You clicked "Cancel" button', type: 'error'});
			}
		}
	],*/
    callback: {
        onShow: function() {},
        afterShow: function() {},
        onClose: function() {},
        afterClose: function() {},
        onCloseClick: function() {},
    },
   
});</script>