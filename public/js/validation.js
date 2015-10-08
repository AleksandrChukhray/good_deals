$(document).ready(function(){
	var jVal = {
		'forEmpty' : function(){
			$('#reg').append('<div id="nameInfo" class="required_hint"></div>');
			var nameInfo = $('#nameInfo');
			var elements = $('.reg_field .label_100Pr span');
			var counter = 0;
			
			elements.each(function(){
                var vInput = $(this).closest('.reg_field').find('input');
				if ((vInput !== null) && (vInput.val().length < 1)) {
                    vInput.css("backgroundColor", "#FAEBD7");
					counter++;
                }
			});
			
			if (counter > 0){
				jVal.errors = true;
				nameInfo.removeClass('required_hint').addClass('required_hint_yes').html('Все поля,<br> помеченные *,<br> должны быть заполнены!').show();
				setTimeout(function(){$('#nameInfo').remove();}, 3000);
			}
			else {
				var vPassword = $('#password');
				var vConfirmPassword = $('#confirmpassword');
				
				if (vPassword.val() !== vConfirmPassword.val()){
					jVal.errors = true;
					nameInfo.removeClass('required_hint').addClass('required_hint_yes').html('Пароль и подтверждение пароля не совпадают!').show();
					setTimeout(function(){$('#nameInfo').remove();}, 6000);
				} 
				else {
					jVal.errors = false;
				}
			}
		}
	};

	$('#registration_btn').click(function(){
		jVal.forEmpty();

		if (jVal.errors){
			return false;
		} else {
            var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            if (!pattern.test($('#EmailEdt').val())) {
                var nameInfo = $('#nameInfo');
				nameInfo.removeClass('required_hint').addClass('required_hint_yes').html('Введите корректный адрес эл. почты!').show();
				setTimeout(function(){$('#nameInfo').remove();}, 3000);
                return false;
            }            
        }
        
        return true;
	});
    
	$('#ResetPasswordBtn').click(function(){
        var vPassword = $('#password');
        var vConfirmPassword = $('#confirmpassword');

        if (vPassword.val() !== vConfirmPassword.val()) {
            $.alert("Пароль и подтверждение пароля не совпадают!", {type:"danger", title:"Ошибка!"});
            return false;
        } 
        else {
            return true;
        }
	});
    
});