<div id="body_center_content" class="margin_bottom_100">
    <div style="width: 100%; overflow: hidden;">
        <div class="login_left_column">
            <div class="text_rounded_background padding_15">
                <div class="enter_h3">Восстановление пароля</div>

                <span>На указанный Вами e-mail будет отправлено письмо с ссылкой для восстановления пароля.</span>
                <br/><br/><span>Перейдя по ссылке вы сможете сбросить текущий пароль и ввести новый.</span>

                <form action="/auth/recovery" method="post" style="margin-top:30px;">
                    <div class="enter_field">
                        <label class="label_100Pr">Эл. почта</label>
                        <input type="text" name="email" class="input_100Pr required"/>
                    </div>

                    <button type="submit" class="newbutton submit_changes_control" style="margin: 10px 0 10px 0;"><img />Восстановить пароль</button>
                </form>
            </div>
        </div>
    </div>
</div>