<div class="three_column_content">
	<div class="left_column">
		<a href="/consult/"><label class="left_label">Консультации</label></a>

		<div class="qube_small" >
			<?php foreach ($category as $c) : if (($c['id']) == ($experts['id'])) { ?>
					<a href="/consult/cn-<?php echo $c['id']; ?>" class="visit"><img src="<?php echo URL.$c['small_img']; ?>"></a>
				<?php } else { ?>
					<a href="/consult/cn-<?php echo $c['id']; ?>"><img src="<?php echo URL.$c['small_img']; ?>"></a>
				<?php } endforeach; ?>
		</div>
		<img class="back_girl" src="../../public/images/consult/girl.png" />
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
	
	<div class="middle_column" id="mid-col">
		<label class="mid_label"><?php echo $experts['alt_name']; ?></label>
		<?php if (count($expertsuser) > 0) : ?>
			<?php foreach ($expertsuser as $u) :?>
			<div class="experts">
			        	<img class="tab_photo" src="<?php echo (empty($expert['Avatar']))?(URL.'public/images/consult/default.png'):(URL.$expertuser['Avatar']); ?>">
			        	<img class="photo_border" src="../../public/images/consult/ramka.png">
			        <div class="exp_content">
					<a href="/consult/cn-<?php echo $u['id']; ?>/ex-<?php echo $u['UserID']; ?>"><?php echo $u['FirstName'].' '.$u['LastName']; ?></a><br/>
					<?php echo $u['name']; ?><br />
					Последняя консультация
				</div>
				<div class="exp_btn">
				<a href="/consult/ask/?cn=<?php echo $u['id']."&ex=".$u['UserID']; ?>" class="button">Задать вопрос</a>
					<a href="/consult/cn-<?php echo $u['id']; ?>/ex-<?php echo $u['UserID']; ?>/?a=1">Ответы эксперта</a>
				</div>
			</div>
			<?php endforeach; ?>
		<div id="black" style="margin: auto;"></div>
			<?php else : ?>
				<p class="clear">В данной категории экспертов нет.</p>
		<?php endif; ?>
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
					
					<select name="setCatName" id="category-2">
						<option selected hidden > </option>
						<?php foreach ($category as $c) :?>
							<option value="<?php echo $c['id']; ?>"><?php echo $c['alt_name']; ?></option>
						<?php endforeach; ?>
					</select>
	
					<label>Имя эксперта</label>
					<select name="setExpName" id="expert-2">
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
            $('#black').smartpaginator({ totalrecords: <?php echo count($expertsuser); ?>, recordsperpage: 5, datacontainer: 'mid-col', dataelement: '.experts', length: 5, next: '>', prev: '<', first: '<<', last: '>>', theme: 'black' });
        });
		</script>
