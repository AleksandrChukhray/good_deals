<div id="body_center_content" class="margin_bottom_100">
	<div style="width: 100%; overflow: hidden;">
		<div class="registration_left_column">
			<div class="text_rounded_background padding_15">
				<div id="reg">
					<div class="enter_h3">Регистрация</div>
				
					<form action="/auth/registration" method="post" id="jform">
						<div class="reg_field">
							<label class="label_100Pr">Имя<span style="color:#fe5c45;">*</span> (только кириллица: рус., укр.)</label>
                            <input type="text" id="FirstNameEdt" name="firstname" class="input_100Pr required" value="<?php echo $FirstName; ?>"/>
                            <span class="hint">Вам нужно ввести имя (используйте киррилицу)</span>
						</div>
						
						<div class="reg_field">
							<label class="label_100Pr">Фамилия</label>
                            <input type="text" name="lastname" class="input_100Pr" value="<?php echo $LastName; ?>" />
						</div>

                        <div class="reg_field">
                            <label class="label_100Pr">Эл. почта (используется как логин для входа)<span style="color:#fe5c45;">*</span></label>
                            <input type="text" id="EmailEdt" name="email" maxlength="80" class="input_100Pr required" value="<?php echo $Email; ?>"/>
							<span class="hint">Вам нужно ввести<br> эл. почту</span>
						</div>

						<div class="reg_field">
							<label class="label_100Pr">Пароль<span style="color:#fe5c45;">*</span></label>
                            <input id="password" type="password" name="password" class="input_100Pr required" />
							<span class="hint">Вам нужно ввести<br> пароль</span>
						</div>
						
						<div class="reg_field">
							<label class="label_100Pr">Подтверждение пароля<span style="color:#fe5c45;">*</span></label>
                            <input id="confirmpassword" type="password" name="password2" class="input_100Pr required" />
							<span class="hint">Вам нужно ввести пароль еще раз</span>
						</div>

						<!--
						<div class="reg_field">
							<label class="enter_label">Логин<span style="color:#fe5c45;">*</span> </label><input type="text" name="login" class="enter_input" />
							<span class="hint">Вам нужно ввести<br> логин</span>
						</div>-->
						
						<div class="reg_field">
                            <label class="label_100Pr">Защита от роботов, решите пример<span style="color:#fe5c45;">*</span></label>
                            <div class="block_captcha">
                                <div class="block_captcha_img">
                                    <div class="block_captcha_img_box"></div>
                                    <?php echo Securimage::getCaptchaHtml() ?>
                                </div>
                                <div class="block_captcha_equal">=</div>
                                <input class="block_captcha_answer required" id="CaptchaCodeEdt" name="CaptchaCodeEdt" type="text" maxlength="2" required />
                            </div>
                        </div>
                        
                        <button id="registration_btn" name="registration_btn" type="submit" class="newbutton" style="margin: 10px 0 10px 0;">Зарегистрироваться</button>
					</form>	 
				</div>
			</div>
		</div>
		
		<div class="registration_right_column">
			<!--<div class="reg_comment">
				На указанный вами email будет выслано письмо для подтверждения регистрации на сайте. Для завершения регистрации следуйте инструкциям в письме. Если вы не получили письмо, проверьте папку Спам.
			</div>	-->
			<div class="reg_girl"></div>	
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#FirstNameEdt').on("keypress", function (e) {
            return IsCyrillic(e);
        });
        
        $('input,textarea').filter('.required:visible').on('input', function() {
            $(this).css("backgroundColor", "#FFFFFF");
        });        
    });
</script>