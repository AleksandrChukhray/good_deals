<div class="three_column_content">
	<div class="left_column">
		<a href="/consult/"><label class="left_label">Консультации</label></a>

		<div class="qube_small" >
			<?php foreach ($category as $c) : ?>
				<?php if ($c['id'] == Tools::getValue('c')) { ?>
					<a href="/consult/cn-<?php echo $c['id']; ?>" class="visit"><img src="<?php echo URL.$c['small_img']; ?>"></a>
				<?php } else { ?>
					<a href="/consult/cn-<?php echo $c['id']; ?>"><img src="<?php echo URL.$c['small_img']; ?>"></a>
				<?php } endforeach; ?>
		</div>
		
		<img class="back_girl" src="<?php echo URL.'public/images/consult/girl.png'; ?>" />
		
		<div class="con_last_ans">
			<div class="lans_ttl" style="">Свежие ответы</div>
				<div class="lans_bod">

					<?php foreach ($questions as $q) :?>
						<?php if(!empty($q['answer'])) { ?>
							<a href="/consult/q-<?php echo $q['id'];?>"><?php echo Tools::cutString($q['answer'], 35); ?></a>
						<?php if(++$i == 5) break; }?>
					<?php endforeach; ?>
				
				</div>
		</div>


	</div>
	
	<div class="mid_column">
		<label class="mid_label">Вы искали: <?php echo Tools::getValue('w'); ?></label>


				<a href="/consult/ask/?cn=<?php echo Tools::getValue('c');?>" class="button">Задать вопрос</a>

			<div class="srch_tab">
							
				    <input id="tab1" type="radio" name="tabs" checked value="tab1">
				    <label for="tab1" title="Все вопросы">Все вопросы</label>

				    <input id="tab2" type="radio" name="tabs" <?php if(Tools::getValue('a') === '1') echo 'checked' ?>>
				    <label for="tab2" title="С ответами">С ответами</label>
				 
				    <input id="tab3" type="radio" name="tabs">
				    <label for="tab3" title="Без ответов">Без ответов</label>
				    
				    <section id="content1">
				    <?php if (count($search) > 0) : ?>
						<?php foreach ($search as $q) :?>
					        <div class="question">
					        	<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" /></a>
					        	<div class="tab_cont">
									<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><?php echo $q['title']; ?></a><br/>
									<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
									<p><?php echo $q['body']; ?></p>
									<?php if (!empty($q['answer'])) { ?>
										<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Есть ответ(-ы)</a>
									<?php } else {?>
										<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Пока нет ответа</a>
									<?php } ?>
									<div class="empty"></div>
								</div>
					        </div>
						<?php endforeach; ?>
						<div id="black" style="margin: auto;">
						<?php else : ?>
							<p class="clear">По Вашеми запросу <?php echo Tools::getValue('w'); ?> ничего не найдено.</p>
						<?php endif; ?>
				    </section>  
				    
				    <section id="content2">
				    <?php if (count($total[0]) > 0) : ?>
						<?php foreach ($search as $q) :?>
								<?php if(!empty($q['answer'])) { ?>
								<div class="question">
							    	<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" /></a>
							        <div class="tab_cont">
									 	<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><?php echo $q['title']; ?></a><br/>
									 	<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
										<p><?php echo $q['body']; ?></p>
										<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Есть ответ(-ы)</a>
									</div>
								</div>
						<?php } endforeach; ?>
						<div id="black2" style="margin: auto;">
						<?php else : ?>
							<p class="clear">По запросу "<?php echo Tools::getValue('w'); ?>" ответов не найдено.</p>
						<?php endif; ?>
				    </section> 
				    
				    <section id="content3">
				    <?php if (count($total[1]) > 0) : ?>
				 		<?php foreach ($search as $q) : ?>
							<?php if(empty($q['answer'])) { ?>
							<div class="question">
						    	<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" /></a>
						    	<div class="tab_cont">
									<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><?php echo $q['title']; ?></a><br/>
									<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
									<p><?php echo $q['body']; ?></p>
									<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Пока нет ответа</a>
								</div>
							</div>
						<?php } endforeach; ?>
						<div id="black3" style="margin: auto;">
					<?php else : ?>
						<p class="clear">По запросу "<?php echo Tools::getValue('w'); ?>" вопросов не найдено.</p>
					<?php endif; ?>
				    </section> 
		
		</div>
	</div>
	
	<div class="body_right_column">
		<div class="search_questions">
			<form action="" name="queSrc" id="" method="post">

				<div class="block_header">Поиск вопросов</div>
				
				<label>Специализация</label>
				<select name="setCatName" id="category">
					<option disabled selected hidden> </option>
					<?php foreach ($category as $c) :?>
						<option value="<?php echo $c['id']; ?>"><?php echo $c['alt_name']; ?></option>
					<?php endforeach; ?>
				</select>
				
				<label>Ключевое слово</label>
				<input type="text" name="searchText" id="searchText"></input>
				
				<label>Статус вопроса</label><br /><br /><br />
				<input type="radio" name="srcQue" id="allQue" checked /><label for="allQue" >Все</label>
				<input type="radio" name="srcQue" id="onlyAns"  value="1" /><label for="onlyAns" >Получен ответ</label>
				
				<input type="submit" name="subQue" value="Найти">
			</form>
		</div>
		
		<div class="search_answeres">
			<form action="" name="specSrc" id="specSrc" method="post">
				<div class="block_header">Выбор специалиста</div>
					<label>Область консультирования</label>
					
					<select name="setCatName" id="category-5">
						<option selected hidden > </option>
						<?php foreach ($category as $c) :?>
							<option value="<?php echo $c['id']; ?>"><?php echo $c['alt_name']; ?></option>
						<?php endforeach; ?>
					</select>
	
					<label>Имя эксперта</label>
					<select name="setExpName" id="expert-5">
						<option selected hidden> </option>
						<?php foreach ($expert as $ex) :?>
							<option value="<?php echo $ex['UserID'];?>" class="<?php echo $ex['id'];?>"><?php echo $ex['FirstName'].' '.$ex['LastName'];?></option>
						<?php endforeach; ?>	
					</select>
					
					<label>Посмотреть</label><br /><br /><br />
					<input type="radio" name="srcSpec" id="allquest" checked /><label for="allquest" >Вопросы</label>
					<input type="radio" name="srcSpec" id="allansw"  value="1" /><label for="allansw" >Ответы</label>
					<input type="submit" name="subSpec" value="Найти">
	
			</form>
		</div>
		<div class="con_last_que" >
			<div class="lque_ttl" style="">Последние вопросы</div>
			<div class="lque_bod" style="">
				<?php $i=0; foreach ($questions as $q) :?>
					<?php if(!empty($q['title'])) { ?>
						<a href="/consult/q-<?php echo $q['id'];?>"><?php echo Tools::cutString($q['title'], 35); ?></a>
					<?php if(++$i == 5) break; }?>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</div>
		<script type="text/javascript">
        $(document).ready(function () {
            $('#black').smartpaginator({ totalrecords: <?php echo count($search); ?>, recordsperpage: 5, datacontainer: 'content1', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
            $('#black2').smartpaginator({ totalrecords: <?php echo $total[0]; ?>, recordsperpage: 5, datacontainer: 'content2', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
            $('#black3').smartpaginator({ totalrecords: <?php echo $total[1]; ?>, recordsperpage: 5, datacontainer: 'content3', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
        });
		</script>
