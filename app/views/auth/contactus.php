<div id="body_center_content" class="margin_bottom_100">
	<div style="width: 100%; overflow: hidden;">
		<div class="login_left_column">
			<div class="text_rounded_background padding_15">
				<div class="enter_h3">Свяжитесь с нами</div>
			
				<form action="/auth/contactus" method="POST">
					<div class="enter_field">
						<label class="label_100Pr">Ваша эл. почта<span style="color:#fe5c45;">*</span></label>
                        <input type="text" name="email" class="input_100Pr required" value="<?php echo (isset($_SESSION['auth'])) ? $_SESSION['auth']['email'] : ''; ?>"/>
					</div>
			
					<div class="enter_field">
						<label class="label_100Pr">Ваше имя</label>
                        <input type="text" name="name" class="input_100Pr" value="<?php echo (isset($_SESSION['auth'])) ? $_SESSION['auth']['firstname'] : ''; ?>"/>
					</div>

					<div class="enter_field">
                        <label class="label_100Pr">Тема сообщения<span style="color:#fe5c45;">*</span></label>
                        <input type="text" name="subject" class="input_100Pr required" style="max-width: 600px;" />
					</div>
                                    
					<div class="enter_field">
						<label class="label_100Pr">Текст сообщения<span style="color:#fe5c45;">*</span></label>
						<textarea rows="7" name="question" class="input_100Pr required" style="max-width: 600px;"></textarea>
					</div>

					<button type="submit" class="newbutton submit_changes_control" style="margin: 10px 0 10px 0;"><img />Отправить сообщение</button>
				</form>
			</div>
		</div>
	</div>
</div>
