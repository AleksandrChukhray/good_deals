<div class="content_full_width">
	<div class="ask_question">
		<div class="header">
			<label>Задать вопрос</label> <span class="descr">Поля, отмеченные <span class="red">*</span> обязательны к заполнению.</span>
		</div>
		<form action="" method="POST" enctype="multipart/form-data">

		<div class="content_left">
			<div class="raw">
				<label for="setCatName">Раздел:<span class="red"> *</span></label>
				<select class="form_left" name="setCatName" id="category" required>
					<option value="" hidden >Выберите - </option>
	 				<?php foreach ($category as $c) {
						if ((int)Tools::getValue('cn') === (int)$c['id']) { ?>
							<option value="<?php echo $c['id']; ?>" selected ><?php echo $c['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>';
				<?php	}
					}
				?>
				</select>
			</div>
			<div class="raw">
				<label for="setExpName">Эксперт:</label>
				<select class="form_left" name="setExpName" id="expert">
					<?php if((int)Tools::getValue('ex') == 0) echo '<option selected hidden> </option>';?>
					<?php foreach ($expert as $ex) { ?>
						<?php if ((int)Tools::getValue('ex') == (int)$ex['UserID']) {;?>
							<option value="<?php echo $ex['UserID'];?>" class="<?php echo $ex['id'];?>" selected ><?php echo $ex['FirstName'].' '.$ex['LastName'];?></option>
						<?php } else { ?>
							<option value="<?php echo $ex['UserID'];?>" class="<?php echo $ex['id'];?>"><?php echo $ex['FirstName'].' '.$ex['LastName'];?></option>
				<?php 	} 
					} 
				?>	
				</select>
			</div>
			<div class="raw">
				<label for="title">Заголовок:</label>
				<input class="form_text" type="text" name="title" ></span>
			</div>
			<div class="raw">
				<label for="body">Текст:<span class="red"> *</span></label>
				<textarea class="form_text" rows="7" name="body" required ></textarea>
			</div>
		</div>
		
		<div class="content_right">
			<div class="title">Контакты</div>
			<div class="cont_ent">
				<div class="raw">
					<label for="name">Имя:<span class="red"> *</span></label>
					<input class="form_text" type="text" name="name" required></span>
				</div>
				<div class="raw">
					<label for="mail">E-mail:<span class="red"> *</span></label>
					<input class="form_text" type="email" name="mail" required></span>
				</div>
				
				<input type="checkbox" class="chk_bx" id="sendRequ"  name="sendRequ" >
				<label for="sendRequ" class="right_chk">Прислать ответ на почту</label>
				
				<div class="raw">
					<label for="captcha_code">Решите пример:<span class="red"> *</span></label>
					<input id="captcha_code" class="form_text" type="text" name="captcha_code" required >
				</div>
				<div class="captcha">
			        <?php echo Securimage::getCaptchaHtml() ?>
			    </div>

			</div>
		</div>
		<div class="content_foot">
			<input type="submit" value="Отправить" /><br />
			<input type="reset" value="Отменить"/>


		</div>
		</form>
	</div>
</div>
