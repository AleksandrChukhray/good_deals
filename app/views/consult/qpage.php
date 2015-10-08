<div class="three_column_content">
	<div class="left_column">
		<a href="/consult/"><label class="left_label">Консультации</label></a>

		<div class="qube_small" >
			<?php foreach ($category as $c) : if (($c['id']) == ($qpage['cid'])) { ?>
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
							<a  href="/consult/q-<?php echo $q['id'];?>"><?php echo Tools::cutString($q['answer'], 35); ?></a>
						<?php if(++$i == 5) break; }?>
					<?php endforeach; ?>
			</div>
		</div>


	</div>
	<div class="qpage_middle">
		<div class="qinfo">
			<div class="qtitle"><label>Вопрос: <?php echo $qdate->title; ?></label></div>
		</div>	
	
		<div class="qcontent">

<!-- Дискуссии  start-->			
			<div class="qdisquz">
				<div class="qbody">
					<img class = "qimg" src="../../public/images/consult/ask.png">
					<label><?php echo $disquzz[name]; ?> <span><?php echo $disquzz['questiondate']; ?></span></label><br/>
					<p><?php echo $disquzz['body']; ?></p>
				</div>
				<div class="al"><img src="../../public/images/consult/after_l.png"></div>
				<?php if ($disquzz['isAnswer'] == 0 ) { ?>
					<div class="abody">
						<a href="/consult/cn-<?php echo $disquzz['cid']; ?>/ex-<?php echo $disquzz['uid']; ?>"><img class="aimg" src="<?php echo (empty($disquzz['Avatar']))?(URL.'/public/images/consult/default.png'):(URL.$disquzz['Avatar']); ?>"></a>
						<label><a href="/consult/cn-<?php echo $disquzz['cid']; ?>/ex-<?php echo $disquzz['uid']; ?>"><?php echo $disquzz['FirstName'].' '.$disquzz['LastName']; ?></a> <span><?php echo $disquzz['answerdate']; ?></span></label> <br/>
						<label> <span><a href="/consult/cn-<?php echo $disquzz['cid']; ?>"><?php echo $disquzz['cname']; ?></a></span></label><br/>
						<p><?php echo $disquzz['answer']; ?></p>
						<div class="ar"></div>
					</div>		
					<div class="ar"><img src="../../public/images/consult/after_r.png"></div>	
				<?php } ?>
				<?php foreach($disquz as $d) :?>
					<div class="qbody">
						<img class = "qimg" src="../../public/images/consult/ask.png">
						<label><?php echo $d['name']; ?> <span><?php echo $d['questiondate']; ?></span></label><br/>
						<p><?php echo $d['body']; ?></p>
					</div>
					<div class="al"><img src="../../public/images/consult/after_l.png"></div>
					<?php if ($d['isAnswer'] == 0) { ?>
						<div class="abody">
							<a href="/consult/cn-<?php echo $d['cid']; ?>/ex-<?php echo $d['uid']; ?>"><img class="aimg" src="<?php echo (empty($d['Avatar']))?(URL.'/public/images/consult/default.png'):(URL.$d['Avatar']); ?>"></a>
							<label><a href="/consult/cn-<?php echo $d['cid']; ?>/ex-<?php echo $d['uid']; ?>"><?php echo $d['FirstName'].' '.$d['LastName']; ?></a> <span><?php echo $d['answerdate']; ?></span></label> <br/>
							<label> <span><a href="/consult/cn-<?php echo $d['cid']; ?>"><?php echo $d['cname']; ?></a></span></label><br/>
							<p><?php echo $d['answer']; ?></p>
							<div class="ar"></div>
						</div>		
						<div class="ar"><img src="../../public/images/consult/after_r.png"></div>	
				<?php } endforeach; ?>
					<?php if ($qdate->isAnswer == 0) {?>
						<?php if ($_SESSION['auth']['id'] == $qdate->uid && time() < strtotime($qdate->answerdate)+3600) { ?>
							<form action="" method="POST" enctype="multipart/form-data">
								<div class="qp_raw">
									<label for="body">Редактировать ответ:<span class="red"> *</span></label>
									<textarea class="qp_text" rows="7" name="body" required ><?php echo $qdate->answer;?></textarea>
								</div>
								<div class="qp_foot">
									<input type="submit" value="Отправить" name="dis_ed"/>
									<a href="<?php echo '/consult/cn-'.$qdate->cid.'/ex-'.$qdate->uid; ?>">Отменить</a>
								</div>
							</form>

						<?php }
					 } else {?>
						<?php if ($_SESSION['auth']['id'] == $qdate->uid) { ?>
							<form action="" method="POST" enctype="multipart/form-data">
								<div class="qp_raw">
									<label for="body">Oтвет:<span class="red"> *</span></label>
									<textarea class="qp_text" rows="7" name="body" required ></textarea>
								</div>
								<div class="qp_foot">
									<input type="submit" value="Отправить" name="dis_ad" />
									<a href="<?php echo '/consult/cn-'.$qdate->cid.'/ex-'.$qdate->uid; ?>">Отменить</a>
								</div>
							</form>
				<?php		
				  		} 
				  	} 
				?>
			</div>
			
<!-- Дискуссии end-->	
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
        });
		</script>
