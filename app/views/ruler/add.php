<div id="body_center_content">
	<div id="body_center_left_column">
		<?php EchoArticleCategoriesHTML(); ?>

		<div class="site_part">
            <div class="site_part_container" >
                <?php include './app/views/common/site_parts_menu.php'; ?>
            </div>
		</div>
		<div id="block_bottom_flower"></div>
		
		<!--<a href="#" title="Добрые дела" class="dev"><div id="good_deal"></div></a>-->
		
		<div class="site_part dev">
			<div class="site_part_container" >
				<div class="site_part_h3">Новое на форуме</div>
				<!--
				<ul id="acordion_forum">
					<li class="line"></li>
					<li><div><a href="#">Насморк не беда<span>Sophi</span></a></div></li>
					<li class="line"></li>
					<li><div><a href="#">Детские игры<span>Olga108</span></a></div></li>
				</ul>
				-->
				
				<a href="#"><div class="button button_in_block_align_left">Все темы форума</div></a>
			</div>
		</div>
		<div id="block_bottom_flower"></div>
	</div>	



	<div id="body_center_middle_column">
		<div class="middle_content_slider"> 
			<div class="blueberry">
				<ul class="slides">
					<li>
						<div class="middle_content_slider_slide">
							<img src="/public/images/news/news1.jpg" width="633" height="250" />
							<!--
							<div class="middle_content_slider_slide_h3">Новость</div>
							<div class="middle_content_slider_slide_text">
								<h3 style="text-align:center;">В Херсоне стартовал медосмотр школьников</h3>
								<p>продлится до 20.07.15</p>
							</div>
							-->
						</div>
					</li>
					
					<li>
						<div class="middle_content_slider_slide">
							<img src="/public/images/news/news2.jpg" width="633" height="250" />
							<!--
							<div class="middle_content_slider_slide_h3" >Новость</div>
							<div class="middle_content_slider_slide_text">
								<h3 style="text-align:center;">Юбиляры Херсона</h3>
								<p>В этом году зданию планетария исполняется 135 лет</p>
							</div>
							-->
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="middle_content_article text_rounded_background margin_top_15">
			<div class="middle_content_inner_block">
				<div class="middle_content_article_header_h3">Линейка для форума</div>
				<form action="/ruler" method="POST" class="form-ruler">
					 <fieldset>
						<legend>Ваша линейка</legend>
						<img src="/db/img/ruler/results/<?php echo $id; ?>.jpg" />
					</fieldset>
					<fieldset>
						<legend>Код для вставки на сайт:</legend>
						<textarea><a href="http://karapuz.life"><img src="http://karapuz.life/db/img/ruler/results/<?php echo $id; ?>.jpg" border="0" alt="Линейки для форума и блога karapuz.life" /></a></textarea>
					</fieldset>
					<fieldset>
						<legend>Код для вставки на форум:</legend>
						<textarea>[URL=http://karapuz.life][IMG]http://karapuz.life/db/img/ruler/results/<?php echo $id; ?>.jpg[/IMG][/URL]</textarea>
					</fieldset>
				</form>
			</div>
		</div>
	</div>	



	<div id="body_center_right_column">
		<?php EchoAdvertisementBlockHTML(); ?>		
		<div id="interview_block">
			<iframe src="https://docs.google.com/forms/d/1W1zRMHEMicLbGQlES0UTHxzRLk4oqhnq5C2TcwSTFfA/viewform?embedded=true" scrolling="no" width="296" height="520" frameborder="0" marginheight="0" marginwidth="0">Загрузка...</iframe>
		</div>
		<?php EchoAdvertisementBlockHTML(); ?>
	</div>
</div>