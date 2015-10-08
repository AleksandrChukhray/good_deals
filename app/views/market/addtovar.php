
<script type="text/javascript">
    $(function () {
        $('#file_upload').uploadify({
            'swf': '/core/uploadify/uploadify.swf',
            'uploader': '/core/uploadify/uploadify.php',
            // Your options here
            'onUploadSuccess': function (file, data, response) {
                $('#ad_tov').append("<input type='hidden' name='imgs[]' value='" + data + "'>");
                $('#preload').append("<img src='" + data + "'>");
            }
        });
    });
</script>

<div id="adve_block">
    <h2 id="adve">Подать объявление</h2>
    <span id="red_star">Поля с <span style="color:red">*</span>- обязательны для заполнения</span><br>

</div>
<form id="ad_tov" method="post" action="/market/new_tovar/" enctype="multipart/form-data" >

    <div id="left_add">
        <span class="headadd">Объявление</span><br>
        <div class="hr"></div><br>
        <input type="radio" id="sell" name="sel_bay" value="s" required><label for="sell" ><i><i></i></i></label><span class="headadd">Продам</span>
        <input type="radio" id="bay" name="sel_bay" value="b" required><label for="bay" style="margin-left: 30%"><i><i></i></i></label><span class="headadd">Куплю</span><br>
        <label for="zagolov">Заголовок <span style="color:red">*</span></label><input type="text" name="zag" id="zag" required><br>
        <label for="for_cat">Категория <span style="color:red">*</span></label><select class="for_cat" name="for_cat" required><option value="">Выбрать категорию</option><?php echo $block_cat_option; ?> </select><br>
        <label for="desc_sh">Короткое описание <span style="color:red">*</span></label><textarea name="desc_sh" id="desc_sh" required></textarea><br>
        <label for="full_sh">Полное описание <span style="color:red">*</span></label><textarea name="full_sh" id="full_sh" required></textarea><br>
        <label for="cost">Цена, грн. </label><input type="text" name="cost" id="cost" onkeypress="return OnlyNum(event)" ><br><br>
        <label >Состояние товара:</label><br>
        <div id="groups">
            <input type="radio" name="face" id="newss" checked="checked" value="n"><label for="newss"><i><i></i></i></label><span class="headadd">новый</span><br><br>
            <input type="radio" name="face" id="old_good" value="g"><label for="old_good"><i><i></i></i></label><span class="headadd">б/у в отличном состоянии</span><br><br>
            <input type="radio" name="face" id="old" value="u"><label for="old"><i><i></i></i></label><span class="headadd">б/у </span><br><br>

            <label >Пол ребенка:</label><br>

            <input type="radio" name="sex" id="boy" checked="checked" value="b"><label for="boy" ><i><i></i></i></label><span class="headadd">мальчик</span><br><br>
            <input type="radio" name="sex" id="girl" value="g"><label for="girl"><i><i></i></i></label><span class="headadd">девочка</span><br><br>
            <input type="radio" name="sex" id="uni" value="u"><label for="uni"><i><i></i></i></label><span class="headadd">для обоих полов</span><br><br>
        </div>
        <br>
        <div class="hr"></div>
        <br>
        <div id="add_img">
            <div class="col-md-12">
                <div  style="width: 100%;text-align: center;">Добавить фото <br><span style="font-size: 12px !important;color: #333333;"> (для добавления нескольких фото, <br> в открывшемся окне выберите сразу несколько)</span></div>
                <div style="text-align: center; overflow: hidden;">
                    
<div style="color: #8d5a98; font-size: 20px;"><img src="/public/images/comiss/descover.png"></div>
<input type="file" name="AddImageEdt[]" id="files" accept="image/*" multiple style="margin-top: -50px; margin-left:-410px; -moz-opacity: 0; filter: alpha(opacity=0); opacity: 0; font-size: 150px; height: 100px;">
</div>
                 
                <output id="list"></output>           
            </div> 
          
        </div>
    </div>
    <div id="right_add">
        <span class="headadd">Контакты</span><br>
        <div class="hr"></div> <br>
        <label for="cont_face">Контактное лицо <span style="color:red">*</span></label><input type="text" name="cont_face" id="cont_face" value="<?php echo $_SESSION['auth']['firstname']; ?>" required><br>
        <label for="cont_email">Email <span style="color:red">*</span></label><input type="email" name="cont_email" id="cont_email" value="<?php echo $_SESSION['auth']['email']; ?>" required><br>
        <label for="cont_tel">Телефон <span style="color:red">*</span></label><input type="text" name="cont_tel" id="cont_tel" value="<?php echo $_SESSION['auth']['tel']; ?>"onkeypress="return OnlyNum(event)"  required><br>
        <label for="cont_skype">Skype </label><input type="text" name="cont_skype" id="cont_skype" ><br>
        <label for="cont_face">Город <span style="color:red">*</span></label><input type="text" name="cont_city" id="cont_city"  required><br>
        <br>
        <div id="groups" style="padding:15px 30px 15px 30px; background-color: rgba(223, 192, 255, 0.6); text-align: left !important; border-radius:6px;">
            <span style="  color: #3a0144 !important;font-weight: bold !important;">Период подачи объявления</span><br>
            <div class="hr"></div><br>
            <div style="float:left;"> <input type="radio" value="unreg" name="termin" id="unreg" checked="checked"><label for="unreg"><i><i></i></i></label><span class="headadd">1 месяц</span><br> 
                <input type="radio" value="2reg" name="termin" id="2reg"  <?php if (!isset($_SESSION['auth'])) {
    echo "disabled";
} ?>  ><label for="2reg"><i><i></i></i></label><span class="headadd">2 месяца <?php if (!isset($_SESSION['auth'])) {
    echo "<span style='font-size:8px !important;'> (для зарегистрированых)</span>";
} ?></span><br>
            </div>
            <div > <input type="radio" value="3reg" name="termin" id="3reg"  <?php if (!isset($_SESSION['auth'])) {
    echo "disabled";
} ?>  ><label for="3reg"><i><i></i></i></label><span class="headadd">3 месяца <?php if (!isset($_SESSION['auth'])) {
    echo "<span style='font-size:8px !important;'> (для зарегистрированых)</span>";
} ?></span><br>
                <input type="radio" value="reg"  name="termin" id="reg"   <?php if (!isset($_SESSION['auth'])) {
    echo "disabled";
} ?>  ><label for="reg"><i><i></i></i></label><span class="headadd">6 месяцев <?php if (!isset($_SESSION['auth'])) {
    echo "<span style='font-size:8px !important;'> (для зарегистрированых)</span>";
} ?></span>
            </div>
        </div>
        <img src="/public/images/market/girl.png" id="more_girl"><br>
        <input type="image"  src="/public/images/market/send_add.png" ><br>
        <a href="/market" style="color:grey;text-decoration:underline;margin-right: 25%">отмена</a>
    </div>
</form>


    <script>
    function OnlyNum(e)
    {
        var keynum;
        var keychar;
        var numcheck;
        var return2;
        if (window.event) // IE
        {
            keynum = e.keyCode;
        }
        else if (e.which) // Netscape/Firefox/Opera
        {
            keynum = e.which;
        }
        keychar = String.fromCharCode(keynum);
        if (keynum < 48 || keynum > 57) { // разрешаем только ввод цифр
            return2 = false;
            if (keynum == 8)
                return2 = true; // разрешаем нажатие клавиши backspace
            
        }
        else
            return2 = true;
        return return2;
    }
</script>
<script>
    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (e) {
                    // Render thumbnail.
                    var span = document.createElement('span');
                    span.innerHTML = ['<img class="thumb" src="', e.target.result,
                        '" title="', theFile.name, '"/>'].join('');
                    document.getElementById('list').insertBefore(span, null);
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }

    document.getElementById('files').addEventListener('change', handleFileSelect, false);
    
   
</script>