<div id="body_center_content" class="margin_bottom_100">
	<div style="width: 100%; overflow: hidden;">
		<div class="login_left_column">
			<div class="text_rounded_background padding_15">
				<div class="enter_h3">Вход на сайт</div>

                <div style="position: relative;">
                    <div class="loginform_email" style="float: left; width: 285px; padding-right: 35px; border-right: 1px solid #e8deea;">
                        <form action="/auth/login" method="POST" style="margin-top: 16px;">
                            <div class="enter_field">
                                <label class="label_100Pr">Эл. почта</label>
                                <input type="text" name="email" class="input_100Pr" />
                            </div>

                            <div class="enter_field">
                                <label class="label_100Pr">Пароль</label>
                                <input type="password" name="password" class="input_100Pr" />
                            </div>

                            <div class="enter_field">
                                <button type="submit" class="newbutton float_left">Войти</button>

                                <div class="MyCheckBox float_left" style="margin: 8px 0 0 60px;">
                                    <input id="RememberMeEdt" name="RememberMeEdt" type="checkbox" checked="checked"/>
                                    <label for="RememberMeEdt">Запомнить меня</label>
                                </div>
                            </div>
                        </form>

                        <div style="line-height:30px; margin-top:20px; float: left;">
                            <a class="normal_link" href="/auth/registration">Зарегистрироваться</a>
                            <br><a class="normal_link" href="/auth/recovery">Восстановить пароль</a>
                        </div>                        
                    </div>
                    
                    <div style="position: absolute; top: 66px; left: 296px; padding: 6px 10px 6px 10px; background: white; border-radius: 22px; border: 1px solid #e8deea;">или</div>

                    <div class="loginform_social" style="float: left; width: 210px; padding-left: 35px;">
                        <label class="label_100Pr" style="margin-bottom: 10px;">Войти как пользователь</label>
                        <a class="newbutton social_link" style="margin-bottom: 20px;" href="<?php echo htmlspecialchars($FB_LoginUrl); ?>"><img width="36" height="36" src="<?php echo URL."public/images/fb_true.png"; ?>" alt=""/><span>Facebook</span></a>
                        <a class="newbutton social_link social_color2" href="<?php echo htmlspecialchars($VK_LoginUrl); ?>"><img width="36" height="36" src="<?php echo URL."public/images/vk_true.png"; ?>" alt=""/><span>ВКонтакте</span></a>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>