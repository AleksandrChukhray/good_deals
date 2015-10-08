<div id="body_center_content" class="margin_bottom_100">
	<div style="width: 100%; overflow: hidden;">
		<div class="login_left_column">
			<div class="text_rounded_background padding_15">
				<div class="enter_h3">Изменение пароля</div>

				<form action="/auth/resetpassword" method="post" style="margin-top:30px;">
                    <input type="hidden" name="EncryptedEmail" value="<?php echo $EncryptedEmail; ?>">
                    <input type="hidden" name="EncryptedPasswordHash" value="<?php echo $EncryptedPasswordHash; ?>">
                    
                    <div class="enter_field">
                        <label class="label_100Pr">Эл. почта</label>
                        <input type="text" name="email" class="input_100Pr" disabled value="<?php echo $Email; ?>"/>
                    </div>

                    <div class="enter_field">
						<label class="label_100Pr">Новый пароль</label>
                        <input type="password" id="password" name="password" class="input_100Pr" required />
					</div>
                    
                    <div class="enter_field">
                        <label class="label_100Pr">Подтверждение пароля</label>
                        <input type="password" id="confirmpassword" name="confirmpassword" class="input_100Pr" required />
                    </div>                    

					<button id="ResetPasswordBtn" type="submit" class="newbutton" style="margin: 10px 0 10px 0;">Изменить пароль</button>
				</form>
			</div>
		</div>
	</div>
</div>
