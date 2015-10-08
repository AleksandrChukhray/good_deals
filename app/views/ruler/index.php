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
				<div class="middle_content_article_header_h3">Линейки для форума</div>
				<form action="/ruler/add" method="POST" class="form-ruler">
					 <fieldset class="backgrounds">
						<legend>Фон</legend>
						<?php foreach ($images['ruler'] as $k=>$r) : ?>
						<label><input type="radio" name="background" value="<?php echo $r; ?>" <?php echo ($k == 0) ? 'checked="checked"' : ''; ?> /> <img src="/<?php echo DIR_DBIMAGES.'ruler/templates/'.$r; ?>" /></label>
						<?php endforeach; ?>
					</fieldset>
					<fieldset class="sliders">
						<legend>Ползунок</legend>
						<?php foreach ($images['slider'] as $k=>$s) : ?>
						<label><input type="radio" name="slider" value="<?php echo $s; ?>" <?php echo ($k == 0) ? 'checked="checked"' : ''; ?> /> <img src="/<?php echo DIR_DBIMAGES.'ruler/templates/'.$s; ?>" /></label>
						<?php endforeach; ?>
					</fieldset>
					<fieldset>
						<legend>Дата начала события</legend>
						<p><input type="text" name="date_start" required /></p>
					</fieldset>
					<fieldset>
						<legend>Текст</legend>
						<p><input type="text" name="text" required /></p>
					</fieldset>
					<fieldset>
						<legend>Цвет текста</legend>
						<p><input type="text" name="color" value="#000000" /></p>
					</fieldset>
					<fieldset>
						<legend>Отсчет в</legend>
						<p>
							<select name="counter" required>
								<option value="d">днях</option>
								<option value="m">месяцах</option>
								<option value="md">месяцах, днях</option>
								<option value="y">годах</option>
								<option selected="selected" value="ymd">годах, месяцах, днях</option>
							</select>
						</p>
					</fieldset>
					<fieldset>
						<legend>Длина линейки</legend>
						<p>
							<select name="length" required>
								<option value="month">месяц</option>
								<option value="nine_month">9 месяцев</option>
								<option selected="selected" value="year">год</option>
							  </select>
						</p>
					</fieldset>
					
					<input type="submit" value="Создать линейку" class="button" />
				</form>

				<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.6.2/spectrum.min.css" />
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/i18n/jquery-ui-i18n.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.6.2/spectrum.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.6.2/i18n/jquery.spectrum-ru.min.js"></script>
				<script>
					$.datepicker.setDefaults($.datepicker.regional['ru']);
					$('input[name=date_start]').datepicker({
						dateFormat: 'yy-mm-dd',
						changeMonth: true,
						changeYear: true,
					});
					$('input[name=color]').spectrum({
						allowEmpty: true,
						clickoutFiresChange: true,
						preferredFormat: 'hex',
						showInitial: true,
						showButtons: false,
						showInput: true,
					});
				</script>
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