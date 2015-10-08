$(document).ready(function(){
    $("form button.submit_changes_control").click(function (event) {
        SubmitChangesButton(this, event);
    });

    $("form input.delete_image_control").click(function (event) {
        return DeleteImage(this, event);
    });
    $("form button.delete_image_control").click(function (event) {
        return DeleteImage(this, event);
    });
    
    $('.btn-file :file').on('click', function(event) {
        if (ValidateForm() === false) {
            event.preventDefault();            
            return false;
        } else {
            return true;
        }        
    });

    $('.btn-file :file').on('change', function() {
        var input = $(this);
        var filename = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [filename]);
    });

    $('.btn-file :file').on('fileselect', function(event, filename) {
        $(this).parent().closest('div').addClass("action_loader");
        this.form.submit();
        return true;
    });  
    
    $('select').filter('.required:visible').on('change', function() {
        $(this).css("backgroundColor", "#FFFFFF");
    });
    
    $('input,textarea').filter('.required:visible').on('input', function() {
        $(this).css("backgroundColor", "#FFFFFF");
    });
});

function SubmitChangesButton(AButton, AEvent)
{
    if ((AButton.type === "submit")) {    
        if (ValidateForm() === false) {
            AEvent.preventDefault();            
            return false;
        } else {
            // для предотвращения повторного выполнения SubmitForm после повторного нажатия на любую из кнопок submit, пока еще обрабатывается предыдущее нажатие
            $("form button.submit_changes_control, form button.submit_control").click(function (event) {
                event.preventDefault();
                return false;
            });            
        }

        AButton.className += " main_action_loader"; // Добавить новый класс для кнопки (для отображения картинки с прогресс gif)
        
        AButton.form.submit();
        return true;
    }
}

function ValidateForm() {
    var IsValid = true;
    //$('input,textarea,select').filter('[required]:visible').each(function(index, domElement){
    $('input,textarea,select').filter('.required:visible').each(function(index, domElement){
        if ($(domElement).val().trim() == '') {
            // antiquewhite or cornsilk
            $(domElement).css("backgroundColor", "#FAEBD7");
            //$(domElement).css("backgroundColor", "#FFF8DC");
            
            if (IsValid == true) {
                $(domElement).focus();                
            }
            
            IsValid = false;
        } else {
            $(domElement).css("backgroundColor", "#FFFFFF");
        }        
    });
    
    return IsValid;
}

function IsCyrillic(e) {
    // http://www.w3schools.com/charsets/ref_utf_cyrillic.asp
    var evt = (e) ? e : window.event;
    var key = (evt.keyCode) ? evt.keyCode : evt.which;
    if (key !== null) {
        key = parseInt(key, 10);
        if (((key >= 1024) && (key <= 1279)) || (key === 32)) {
            return true;
        }
    }
    return false;
}

function IsNumeric(e, CurrentText) {
    var evt = (e) ? e : window.event;
    var key = (evt.keyCode) ? evt.keyCode : evt.which;
    if (key !== null) {
        key = parseInt(key, 10);
        if (key < 48 || key > 57) {

            // prevent input second point
            var vIdx = CurrentText.indexOf('.', 0);
            if (vIdx !== -1) { 
                if (key === 44 || key === 46) {
                    return false;
                }
            } 

            if (!jsIsUserFriendlyChar(key)) {
                return false;
            }
        }
        else {
            if (evt.shiftKey) {
                return false;
            }
        }
    }
    return true;
}

// Function to check for user friendly keys  
function jsIsUserFriendlyChar(val) {
    // Backspace, Tab, Enter, Insert, and Delete  
    if (val === 8 || val === 9 || val === 13 || val === 44 || val === 45 || val === 46) {
        return true;
    }

    // Ctrl, Alt, CapsLock, Home, End, and Arrows  
    if ((val > 16 && val < 21) || (val > 34 && val < 41)) {
        return true;
    }

    if (val === 190 || val === 110) {  //Check dot key code should be allowed
        return true;
    }

    // The rest  
    return false;
}

function DeleteImage(AInput, AEvent)
{
    AInput.blur(); // для потери фокуса
    return confirm("Удалить изображение?");
}