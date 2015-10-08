<div id="body_center_content">
<?php
//	print_r ($category);
?>
	<div class="content_column">
		<div class="block_left" style="">
			<a href="/consult/"><label class="left_label">Консультации</label></a>
			<label class="think_label">
				<p>В этом разделе Вы можете <br/>
				получить консультацию <br/>
				специалиста по любому<br/>
				интересующему Вас вопросу.</p>
			</label>
			<img src="../../public/images/consult/girl.png" />
		</div>
		
		<div class="qube_big">
		<?php foreach ($category as $c) :?>
			<a href="/consult/cn-<?php echo $c['id']; ?>"><img src="<?php echo URL.$c['img']; ?>"></a>
		<?php endforeach; ?>
		</div>
		
		<div class="con_tab">
			<?php foreach ($category as $c) :?>
			    <input id="tab<?php echo $c['id']; ?>" type="radio" name="tabs" <?php if($c['id'] == 1) echo 'checked' ?>>
			    <label for="tab<?php echo $c['id']; ?>" title="<?php echo $c['name']; ?>"><?php echo $c['alt_name']; ?></label>

			    <section id="content<?php echo $c['id']; ?>">
			    <?php $ExpertCount = 0; 
			    	foreach ($expert as $ex) :
					    if ($c['id'] == $ex['id']) {
						  	$ExpertCount = $ExpertCount + 1;
							if ($ExpertCount > 5) break;
				?>
				        <div class="tab_c1">
				        	<img class="tab_photo" src="<?php echo (empty($expert['Avatar']))?(URL.'public/images/consult/default.png'):(URL.$expertuser['Avatar']); ?>">
				        	<img class="photo_border" src="../../public/images/consult/ramka.png">
					    	<div class="tab_content">
								<a href="/consult/cn-<?php echo $ex['id']; ?>/ex-<?php echo $ex['UserID']; ?>"><?php echo $ex['FirstName'].' '.$ex['LastName']; ?></a><br/>
								<?php echo $ex['name']; ?><br />
								Последняя консультация - 
							</div>
						  <div class="tab_btn">
							<a href="/consult/ask/?cn=<?php echo $ex['id']."&ex=".$ex['UserID']; ?>" class="button">Задать вопрос</a>
						  	<a href="/consult/cn-<?php echo $ex['id']; ?>/ex-<?php echo $ex['UserID']; ?>/?a=1">Ответы эксперта</a>
						  </div>
				        </div>
				<?php	} 
				 	endforeach; 
				 	if ($ExpertCount == 0) echo "В этом разделе еще нет экспертов."; 
				 ?>
			    </section> 
			<?php endforeach; ?>			
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
				
				<select name="setCatName" id="category-1">
					<option selected hidden > </option>
					<?php foreach ($category as $c) :?>
						<option value="<?php echo $c['id']; ?>"><?php echo $c['alt_name']; ?></option>
					<?php endforeach; ?>
				</select>

				<label>Имя эксперта</label>
				<select name="setExpName" id="expert-1">
					<option selected hidden> </option>
					<?php foreach ($expert as $ex) :?>
						<option value="<?php echo $ex['UserID'];?>" class="<?php echo $ex['id'];?>"><?php echo $ex['FirstName'].' '.$ex['LastName'];?></option>
					<?php endforeach; ?>	
				</select>
				
				<label>Посмотреть</label><br /><br /><br />
				<input type="radio" name="srcSpec" id="allquest" value="0" checked /><label for="allquest" >Вопросы</label>
				<input type="radio" name="srcSpec" id="allansw" value="1" /><label for="allansw" >Ответы</label>
				<input type="submit" name="subSpec" value="Найти">

		</form>
		</div>
		
		<div class="last_quest" >
			<div class="lque_ttl" style="">Последние вопросы</div>
			<div class="lque_bod" style="">
					<?php $i=0; foreach ($questions as $q) :?>
						<?php if(!empty($q['title'])) { ?>
							<a href="/consult/q-<?php echo $q['id'];?>"><?php echo Tools::cutString($q['title'], 35); ?></a>
						<?php if(++$i == 5) break; }?>
					<?php endforeach; ?>
			</div>
		</div>
		
		<div class="last_answ">
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
	
</div>
