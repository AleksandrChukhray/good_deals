<?php if ($_SESSION['auth']['id'] != $expertuser['UserID']) { ?>
<div class="three_column_content">
	<div class="left_column">
		<a href="/consult/"><label class="left_label">Консультации</label></a>

		<div class="qube_small" >
			<?php foreach ($category as $c) : if (($c['id']) == ($expertuser['id'])) { ?>
					<a href="/consult/cn-<?php echo $c['id']; ?>" class="visit"><img src="<?php echo URL.$c['small_img']; ?>"></a>
				<?php } else { ?>
					<a href="/consult/cn-<?php echo $c['id']; ?>"><img src="<?php echo URL.$c['small_img']; ?>"></a>
				<?php } endforeach; ?>
		</div>
		<img class="back_girl" src="<?php echo URL.'public/images/consult/girl.png'; ?>" />
		<div class="con_last_ans">
			<div class="lans_ttl" style="">Свежие ответы</div>
			<div class="lans_bod" style="">
					<?php $i=0; foreach ($questions as $q) :?>
						<?php if(!empty($q['answer'])) { ?>
							<a href="/consult/q-<?php echo $q['id'];?>"><?php echo Tools::cutString($q['answer'], 35); ?></a>
						<?php if(++$i == 5) break; }?>
					<?php endforeach; ?>
			</div>
		</div>


	</div>
<?php //print_r(count($counta)); ?>	
	<div class="middle_column">
		<div class="expert_info">
			<img class="expert_photo" src="<?php if (empty($expertuser['Avatar'])) { ?>../../../public/images/consult/default.png<?php } else { echo URL.$expertuser['Avatar']; } ?>">
			<label class="expert_name"><?php echo $expertuser['FirstName']." ".$expertuser['LastName']; ?></label>
			<label class="expert_spec"><?php echo $expertuser['name']; ?></label>
			<label class="expert_descr"><p><?php echo $expertuser['Description']; ?></p></label>
			<div class="expert_under">


				
				<input type="radio" id="quest_count" class="quest_count" name="tabs" checked >
				<label for="quest_count" class="expert_quest_count" ><?php echo $count['plq']; ?></label>
				
				<input type="radio" id="answ_count" class="answ_count" name="tabs" <?php if(Tools::getValue('a') == 1) echo 'checked' ?>>
				<label for="answ_count" class="expert_answ_count"><?php echo $count['pla']; ?> </label>
				
				<a href="/consult/ask/?cn=<?php echo $expertuser['id']."&ex=".$expertuser['UserID']; ?>" class="button">Задать вопрос</a>

				<section id="without_answer">
				    <?php if ($count['cqu'] > 0) : ?>
					<?php foreach ($questions as $q) :?>
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
					<div id="black" style="margin: auto;"></div>
					<?php else : ?>
						<p class="clear">По Вашеми запросу <?php echo Tools::getValue('w'); ?> ничего не найдено.</p>
					<?php endif; ?>
				</section>
		
				<section id="with_answer">
				    <?php if ($count['can'] > 0) : ?>
					<?php foreach ($questions as $q) :
						if (!empty($q['answer'])) { ?>
					<div class="question">
			        	<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" /></a>
						<div class="tab_cont">
							<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><?php echo $q['title']; ?></a><br/>
							<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
							<p><?php echo $q['body']; ?></p>
								<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Есть ответ(-ы)</a>
						</div>
					</div>
					<?php } ?>
					<?php endforeach; ?>
					<div id="black1" style="margin: auto;"></div>
					<?php else : ?>
						<p class="clear">По Вашеми запросу <?php echo Tools::getValue('w'); ?> ничего не найдено.</p>
					<?php endif; ?>
				</section>
			</div>
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
					
					<select name="setCatName" id="category-3">
						<option selected hidden > </option>
						<?php foreach ($category as $c) :?>
							<option value="<?php echo $c['id']; ?>"><?php echo $c['alt_name']; ?></option>
						<?php endforeach; ?>
					</select>
	
					<label>Имя эксперта</label>
					<select name="setExpName" id="expert-3">
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
            $('#black').smartpaginator({ totalrecords: <?php echo $count['cqu']; ?>, recordsperpage: 5, datacontainer: 'without_answer', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
            $('#black1').smartpaginator({ totalrecords: <?php echo $count['can']; ?>, recordsperpage: 5, datacontainer: 'with_answer', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
        });
		</script>
<?php } else { ?>

<div class="three_column_content">
	<div class="left_column">
		<label class="left_label">Консультации</label>

		<div class="qube_ico" >
			<?php foreach ($category as $c) : if (($c['id']) == ($expertuser['id'])) { ?>
					<img src="<?php echo URL.$c['small_img']; ?>" class="visit">
				<?php } endforeach; ?>
		</div>
		<div class="countes">
				<label class="qCount" ><?php echo $count['waw']; ?></label>
				<label class="aCount"><?php echo $count['pla']; ?> </label>
		</div>
		<label class="think_label">
				<p>Личных <strong><?php echo $count['waw']; ?></strong> <br/>
				а так же, <strong><?php echo $count['cns']; ?></strong> в раздел<br/>
				ожидают Вашего ответа.<br />
				</p>
		</label>

		<img class="back_girl_private" src="<?php echo URL.'public/images/consult/girl.png'; ?>" />


	</div>
	<div class="middle_column">
		<div class="expert_info">
			<img class="expert_photo" src="<?php if (empty($expertuser['Avatar'])) { ?>../../../public/images/consult/default.png<?php } else { echo URL.$expertuser['Avatar']; } ?>">
			<label class="expert_name"><?php echo $expertuser['FirstName']." ".$expertuser['LastName']; ?></label>
			<label class="expert_spec"><?php echo $expertuser['name']; ?></label>
			<label class="expert_descr"><p><?php echo $expertuser['Description']; ?></p></label>
			<div class="expert_under">
			</div>
		</div>
			<div class="srch_tab">
							
				    <input id="tab1" type="radio" name="tabs" checked value="tab1">
				    <label for="tab1" title="Все вопросы">Личные вопросы</label>

				    <input id="tab2" type="radio" name="tabs">
				    <label for="tab2" title="С ответами">Вопросы в раздел</label>
				 
				    <input id="tab3" type="radio" name="tabs">
				    <label for="tab3" title="Без ответов">Архив ответов</label>
				    
				    <section id="content1">
				    	<strong><?php echo $count['waw']; ?></strong>
						<?php foreach ($getAllQuest as $q) :
							if ($q['isAnswer'] == 1 && $q['uid'] == $_SESSION['auth']['id']) { ?>
								
								<div class="question">
						        	<img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" />
									<div class="tab_cont">
										<?php echo $q['title']; ?><br/>
										<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
										<p><?php echo $q['body']; ?></p>
										<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Ответить</a>
									</div>
								</div>
								
							<?php } 
						endforeach; 
						
						?>
						<div id="black00" style="margin: auto;"></div>
				    </section>  
				    
				    <section id="content2">
				    	<strong><?php echo $count['cns']; ?></strong>
						<?php foreach ($getAllQuest as $q) :
							if ($q['isAnswer'] == 1 && $q['uid'] == 0) { ?>
						<div class="question">
				        	<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" /></a>
							<div class="tab_cont">
								<a href="/consult/q-<?php echo $q['id']; ?>" class="ttl" ><?php echo $q['title']; ?></a><br/>
								<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
								<p><?php echo $q['body']; ?></p>
									<a href="/consult/q-<?php echo $q['id']; ?>" class="have_answ">Ответить</a>
							</div>
						</div>
						<?php
						 } 
						 
						 endforeach;
						 
						?>
						<div id="black01" style="margin: auto;"></div>
				    </section> 
				    
				    <section id="content3">
				    <strong><?php echo $count['pla']; ?></strong> 
						<?php foreach ($getAllQuest as $q) :
							if ($q['isAnswer'] == 0 && $q['uid'] == $_SESSION['auth']['id']) { ?>
						<div class="question">
				        	<img class="tab_img" src="<?php echo URL.'public/images/consult/ask.png'; ?>" />
							<div class="tab_cont">
								<?php echo $q['title']; ?><br/>
								<span><?php echo $q['name']; ?> <?php echo $q['questiondate']; ?></span>
								<p><?php echo $q['answer']; ?></p><br />
							<?php if (time() < strtotime($q['answerdate'])+6000) {?>	
							<a href="/consult/cn-<?php echo $_SESSION['auth']['cat'].'/ex-'.$_SESSION['auth']['id'].'/?del='.$q['id']; ?>" class="del"><img />Удалить</a>
							<a href="/consult/q-<?php echo $q['id']; ?>" class="edit" ><img />Редактировать</a>
							<?php } ?>
							</div>
						</div>
						<?php } endforeach; ?>
						<div id="black02" style="margin: auto;"></div>
				    </section> 
		
		</div>
		
	</div>
	<div class="body_right_column">
		<div class="con_last_ans">
			<div class="lans_ttl" style="">Последние ответы</div>
			<div class="lans_bod" style=""></div>
		</div>

		<div class="con_last_que" >
			<div class="lque_ttl" style="">Последние вопросы</div>
			<div class="lque_bod" style=""></div>
		</div>

	</div>
</div>
		<script type="text/javascript">
        $(document).ready(function () {
            $('#black00').smartpaginator({ totalrecords: <?php echo $count['waw']; ?>, recordsperpage: 5, datacontainer: 'content1', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
            $('#black01').smartpaginator({ totalrecords: <?php echo $count['pla']; ?>, recordsperpage: 5, datacontainer: 'content2', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
            $('#black02').smartpaginator({ totalrecords: <?php echo $count['pla']; ?>, recordsperpage: 5, datacontainer: 'content3', dataelement: '.question', length: 5, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black' });
        });
		</script>

<?php } ?>