<?php
    require_once 'pagination.php';
    require_once 'urlify/URLify.php'; // https://github.com/jbroadway/urlify
    require_once 'class.upload/class.upload.php';
 	require_once 'captcha/securimage.php';


    function Redirect($AUrl, $Anchor = '') {
        if (empty($Anchor)) {
            echo '<script type="text/javascript">window.location = "'.$AUrl.'"</script>';
        } else {
            echo '<script type="text/javascript">window.location = "'.$AUrl.'#'.$Anchor.'"</script>';
        }
        die();
    }

    function CreateDBConnection($AContext, $ADBContextName, $AHost, $APort, $ADatabase, $ACharset, $AUser, $APassword) {
        if (!isset($AContext->$ADBContextName)) {
            $AContext->$ADBContextName = GetConnection($AHost, $APort, $ADatabase, $ACharset, $AUser, $APassword);
        }
    }

    function GetConnection($AHost, $APort, $ADatabase, $ACharset, $AUser, $APassword) {
        try {
            $dsn = 'mysql:host='.$AHost.';port='.$APort.';dbname='.$ADatabase.';charset='.$ACharset;
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            return new PDO($dsn, $AUser, $APassword, $opt);
        }
        catch (PDOException $e){
            die('Ошибка при подключении к базе данных "'.$ADatabase.'": ' . $e->getMessage());
        }
    }
    
    function GetMainConnection() {
        global $MainConnection;
        
        if (!isset($MainConnection)) {
            $MainConnection = GetConnection(DB_HOST, DB_PORT, DB_NAME, DB_CHARSET, DB_USER, DB_PASSWORD);
        }
        
        return $MainConnection;
    }
    
    function DBInsertRecord($AConnection, $ATableName) {
        // http://www.w3schools.com/php/php_mysql_insert_lastid.asp

        if (!isset($AConnection)) {
            $AConnection = GetMainConnection();
        }
        
        $sql = "insert into $ATableName() values();";
        try {
            $AConnection->exec($sql); // use exec() because no results are returned
            return (string)$AConnection->lastInsertId();
        }
        catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
            return "0";
        }        
    }

    function GETAsStr($AVarName) {
        return (string)filter_input(INPUT_GET, $AVarName);
    }

    function GETAsStrOrDef($AVarName, $ADefValue) {
        $Result = (string)filter_input(INPUT_GET, $AVarName);
        return empty($Result) ? $ADefValue : $Result;
    }
    
    function ClearSQLStr($value)
    {
        //$search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        //$replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        
        $search = array("'", '"');
        $replace = array("\'", '\"');
        $str = str_replace($search, $replace, $value);
		//return trim(strip_tags($str));        
        return $str;
    }    
    
    function POSTStrAsSQLStr($AVarName) {
        return ClearSQLStr((string)filter_input(INPUT_POST, $AVarName));
    }

    function POSTBoolAsSQLStr($AVarName) {
        if (filter_input(INPUT_POST, $AVarName) !== NULL) {
            return "1";
        } else {
            return "0";
        }
    }

    function IsFileWasUploaded($AVarName) {
        // http://php.net/manual/en/features.file-upload.post-method.php
        $Result = false;
        
        if (!empty($_FILES)) {
            if (isset($_FILES[$AVarName])) {// !== NULL) {
                if ($_FILES[$AVarName]["error"] === UPLOAD_ERR_OK) {
                    $Result = true; // файл был успешно загружен
                }
            }
        }
        
        return $Result;
    }
    
    function MakeDirForWrite($APath) {
        if (is_dir($APath) == 0) {
            mkdir($APath, 0777, true);
        }            
        
        return $APath;
    }    

    function AddError($AErrorList, $AError) {
        if (empty($AErrorList)) {
            return $AError;
        } else {
            return $AErrorList."<br>".$AError;
        }        
    }    

    /*Пример использования и описание
    list($ErrorMsg, $FileRelativeURL) = 
        UploadImage('AddImageEdt', // Input Name (example $_FILES['AddImageEdt'] -> 'AddImageEdt')
                    DIR_DBIMAGES.'authors/', // SubDir - папка для загрузки файла (относительно корня сайта)
                    'm_'.$RecordID, // Имя файла без расширения (с этим именем файл будет сохранен в папке)
                    200, // Max_image_x - максимальная ширина картинки (меньше можно, иначе будет уменьшено с сохранением пропорции картинки)
                    266  // Max_image_y - максимальная высота картинки (меньше можно, иначе будет уменьшено с сохранением пропорции картинки)
                   );
     * $AFixedSourceFileName - задается имя файла, при этом $AFileInputName должен быть пустым, (пример - $tmp_name = $_FILES["AddImageEdt"]["tmp_name"][$key];) 
     * $ANewFileExt - меняет расширение файла
     * 
    Функция возвращает в массив 2 параметра:
       1. $ErrorMsg - пустая строка если ошибки небыло или текст ошибки
       2. $FileRelativeURL - в случае успеха содержит относительный путь к файлу и имя загруженного файла с расширение (пример: 'db/img/authors/m_41.png')
                             Для получения полного пути к файлу используем константу 'URL' из config.php (пример: <img src='.URL.$FileRelativeURL.'>)
    */
    function UploadImage($AFileInputName, $ASubDir, $ANewFileName, $AMax_image_x, $AMax_image_y, $AWatermark_position = '', $AWatermark_FileRelativeURL = '', $AFixedSourceFileName = '', $ANewFileExt = '') {
        $vError = "";
        $vFileRelativeURL = "";
        
        if (empty($AFixedSourceFileName)) {
            $ImgUploader = new Upload($_FILES[$AFileInputName], "ru_RU"); 
        } else {
            $ImgUploader = new Upload($AFixedSourceFileName, "ru_RU"); 
        }
        
        if ($ImgUploader->uploaded) {
            $ImgUploader->file_overwrite = true;
            //$ImgUploader->file_new_name_body = $ANewFileName;
            $ImgUploader->file_new_name_body = URLify::GetLatinSafeFileName($ANewFileName);
            
            if (!empty($ANewFileExt)) {
                $ImgUploader->file_new_name_ext = $ANewFileExt;
            }
            
            if ((!empty($AWatermark_position)) && ($AWatermark_position != '00') && (!empty($AWatermark_FileRelativeURL))) {
                $ImgUploader->image_watermark_position = $AWatermark_position;
                $ImgUploader->image_watermark = PATH_SITE_ROOT.$AWatermark_FileRelativeURL;
            }

            if (($AMax_image_x != 0) && ($AMax_image_y != 0)) {
                if (($ImgUploader->image_src_x > $AMax_image_x) || ($ImgUploader->image_src_y > $AMax_image_y)) {
                    $ImgUploader->image_resize = true;
                    $ImgUploader->image_x = $AMax_image_x;
                    $ImgUploader->image_y = $AMax_image_y;
                    $ImgUploader->image_ratio = true;                    
                } else {
                    $ImgUploader->image_resize = false;
                    $ImgUploader->image_x = $ImgUploader->image_src_x;
                    $ImgUploader->image_y = $ImgUploader->image_src_y;
                    $ImgUploader->image_ratio = false;                    
                }
            }

            //$ImgUploader->image_convert = gif;
            //$ImgUploader->image_ratio_y = true;

            $UploadDir = MakeDirForWrite(PATH_SITE_ROOT.$ASubDir);
            
            $ImgUploader->Process($UploadDir);
            if ($ImgUploader->processed) {
                $vFileRelativeURL = $ASubDir.$ANewFileName.".".$ImgUploader->file_dst_name_ext;
                $ImgUploader->Clean();                
            } else {
                $vError = $ImgUploader->error;
            } 
        } else {
            $vError = $ImgUploader->error;
        }
        
        return array($vError, $vFileRelativeURL);
    }    
  
    function CopyImageWithResize($AFileInputName, $ASubDir, $ANewFileName, $AMax_image_x, $AMax_image_y, $AWatermark_position = '', $AWatermark_FileRelativeURL = '') {
        $vError = "";
        $vFileRelativeURL = "";
        
        $ImgUploader = new Upload($AFileInputName, "ru_RU"); 
        if ($ImgUploader->uploaded) {
            $ImgUploader->file_overwrite = true;
            //$ImgUploader->file_new_name_body = $ANewFileName;
            $ImgUploader->file_new_name_body = URLify::GetLatinSafeFileName($ANewFileName);
            
            if ((!empty($AWatermark_position)) && ($AWatermark_position != '00') && (!empty($AWatermark_FileRelativeURL))) {
                $ImgUploader->image_watermark_position = $AWatermark_position;
                $ImgUploader->image_watermark = PATH_SITE_ROOT.$AWatermark_FileRelativeURL;
            }

            if (($ImgUploader->image_src_x > $AMax_image_x) || ($ImgUploader->image_src_y > $AMax_image_y)) {
                $ImgUploader->image_resize = true;
                $ImgUploader->image_x = $AMax_image_x;
                $ImgUploader->image_y = $AMax_image_y;
                $ImgUploader->image_ratio = true;                    
            } else {
                $ImgUploader->image_resize = false;
                $ImgUploader->image_x = $ImgUploader->image_src_x;
                $ImgUploader->image_y = $ImgUploader->image_src_y;
                $ImgUploader->image_ratio = false;                    
            }

            //$ImgUploader->image_convert = gif;
            //$ImgUploader->image_ratio_y = true;

            $UploadDir = MakeDirForWrite(PATH_SITE_ROOT.$ASubDir);
            
            $ImgUploader->Process($UploadDir);
            if ($ImgUploader->processed) {
                $vFileRelativeURL = $ASubDir.$ANewFileName.".".$ImgUploader->file_src_name_ext; //file_dst_name_ext
                //$ImgUploader->Clean(); // В этой функции не нужно удалять исходный файл                
            } else {
                $vError = $ImgUploader->error;
            } 
        } else {
            $vError = $ImgUploader->error;
        }
        
        return array($vError, $vFileRelativeURL);
    }
    
    function UploadAnyFile($AFileInputName, $ASubDir, $ANewFileName = '', $AClearSourceFile = true, $AFixedSourceFileName = '', $ANewFileExt = '') {
        $vError = "";
        $vUploadedFilePath = "";
        $vUploadedFileName = "";
        
        if (empty($AFixedSourceFileName)) {
            $FileUploader = new Upload($_FILES[$AFileInputName], "ru_RU"); 
        } else {
            $FileUploader = new Upload($AFixedSourceFileName, "ru_RU"); 
        }
        
        if ($FileUploader->uploaded) {
            $FileUploader->file_overwrite = true;
            if (!empty($ANewFileName)) {
                $FileUploader->file_new_name_body = $ANewFileName;
            } else {
                $FileUploader->file_new_name_body = $FileUploader->file_src_name_body;
            }
            if (!empty($ANewFileExt)) {
                $FileUploader->file_new_name_ext = $ANewFileExt;
            }
            
            $FileUploader->file_new_name_body = URLify::GetLatinSafeFileName($FileUploader->file_new_name_body);
            $UploadDir = MakeDirForWrite(PATH_SITE_ROOT.$ASubDir);
            
            $FileUploader->Process($UploadDir);
            if ($FileUploader->processed) {
                $vUploadedFilePath = $ASubDir;
                $vUploadedFileName = $FileUploader->file_dst_name_body.".".$FileUploader->file_dst_name_ext;
                
                if ($AClearSourceFile == true) {
                    $FileUploader->Clean();
                }
            } else {
                $vError = $FileUploader->error;
            } 
        } else {
            $vError = $FileUploader->error;
        }
        
        return array($vError, $vUploadedFilePath, $vUploadedFileName);
    }
    
    function GetImageUpdateStr($ADBFieldName, $ANewValue, $ASeparator) {
        if (empty($ANewValue)) {
            return "";
        } else {
            if (strcasecmp($ANewValue, "null") == 0) {
                return $ADBFieldName." = null".$ASeparator." ";
            } else {
                return $ADBFieldName." = '$ANewValue'".$ASeparator." ";
            }
        }
    }

    function DeleteRelativeToRootFile($AFileRelativeURL) {
        if (!empty($AFileRelativeURL)) {
            if (file_exists(DIR_ROOT_ABSOLUTE.$AFileRelativeURL)) {
                unlink(DIR_ROOT_ABSOLUTE.$AFileRelativeURL);
            }
        }
    }

    function ChangeFileName($AOldFilePathAndName, $ANewFileNameWithoutExtension) {
        $FileInfo = pathinfo($AOldFilePathAndName);
        return $FileInfo['dirname']."/".$ANewFileNameWithoutExtension.".".$FileInfo['extension'];
    }
    
    function EncryptPassword($APassword) {
        // http://php.net/manual/ru/function.password-hash.php
   
		$options = [
            'cost' => 11,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];
        return password_hash($APassword, PASSWORD_BCRYPT, $options);
    }
    
    function VerifyPassword($APassword, $AHash) {
        // http://php.net/manual/ru/function.password-verify.php
        return (password_verify($APassword, $AHash));
    }  
    
    function GetLocalDateTime($TimeZone = 'Europe/Kiev') {
        $now = new DateTime('now', new DateTimeZone($TimeZone));
        return $now;
    }
    function GetLocalDateTimeAsSQLStr($TimeZone = 'Europe/Kiev') {
        $now = new DateTime('now', new DateTimeZone($TimeZone));
        return $now->format('Y-m-d H:i:s');
    }
    function GetLocalDateAsSQLStr($TimeZone = 'Europe/Kiev') {
        $now = new DateTime('now', new DateTimeZone($TimeZone));
        return $now->format('Y-m-d');
    }
    
    function GetDefaultRecordsPerPage() {
        if (defined('DEFAULT_RECORDS_PER_PAGE')) {
            return DEFAULT_RECORDS_PER_PAGE;
        } else {
            return 10;
        }
    }
    
    function GetSQLLimitStr($Page, $RecordsPerPage = 0) {
        if ($RecordsPerPage == 0) {
            $RecordsPerPage = GetDefaultRecordsPerPage();
        }
        
        return "limit ".((($Page > 0) ? $Page-1 : 0)*$RecordsPerPage).", ".$RecordsPerPage;
    }
    
    /**
    * Return url args
    * @excluded - remove var from args (array)
    * @asArray - return data as array (bool)
    */
    function GetURLArguments($excluded = false, $asArray = false){
        $parts = parse_url((string)filter_input(INPUT_SERVER, 'REQUEST_URI'));
        //$parts = parse_url($_SERVER['REQUEST_URI']);

        if (!isset($parts['query'])) {
            return '';
        }
        
        $args = null;
        parse_str($parts['query'], $args);

        if ($asArray) {
            return $args;
        } else {
            $counter = 1;
            $line = '';
            $count = count($args);
            foreach ($args as $k=>$arg){
                if (is_array($excluded) && in_array($k, $excluded)) {
                    continue;                
                }

                $line .= (($counter <= $count && $counter > 1) ? '&' : '');
                $line .= $k.'='.$arg;
                $counter++;
            }
            return $line;
        }
    }
    
    function GetURLPath(){
        return parse_url((string)filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH);
    }
    
    /**
    * Get a value from $_POST / $_GET
    * @key - Value key (string)
    * @default - default value (optional)
    */
    function GetPOSTorGETValue($key, $default = false){
        if (!isset($key) || empty($key) || !is_string($key)) {
            return false;        
        }
        
        $Val = filter_input(INPUT_POST, $key);
        if ($Val !== NULL) {
            $ret = $Val;
        } else {
            $Val = filter_input(INPUT_GET, $key);
            if ($Val !== NULL) {
                $ret = $Val;
            } else {
                $ret = $default;
            }            
        }
        
        if (is_string($ret) === true) {
            $ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
        }
        
        return !is_string($ret)? $ret : stripslashes($ret);
    }    

    function getGUID(){
        /*if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        } else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return $uuid;
        }*/
        
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;        
    }    
    
    function GetUserID() {
        if (isset($_SESSION['auth']) && $_SESSION['auth']['id'] != null) {
            return (int)$_SESSION['auth']['id'];
        } else {
            return 0;
        }
    }
    
    function GetUnknownUserGUID() {
        $CookieName = "unkusrguid";
        if (isset($_COOKIE[$CookieName])) {
            return $_COOKIE[$CookieName];
        } else {
            $vResult = getGUID();
            SetCookie($CookieName, $vResult, time()+(3600*24*365*10), "/"); // время жизни 10 лет
            return $vResult;
        }
    }

    // $AType : danger, success, warning or info
    function AddAlertMessage($AType, $AText, $ARedirectURL = '', $ATitle = '') {
        $_SESSION['AlertMsg']['type'] = $AType;
        $_SESSION['AlertMsg']['text'] = $AText;
        $_SESSION['AlertMsg']['title'] = $ATitle;
        
        if (!empty($ARedirectURL)) {
            Redirect($ARedirectURL);
        }        
    }
    
    function ShowAlertIfMessageExists() {
        if (isset($_SESSION['AlertMsg'])) {
            $vType = $_SESSION['AlertMsg']['type']; // danger, success, warning or info
            $vText = $_SESSION['AlertMsg']['text']; 
            $vTitle = $_SESSION['AlertMsg']['title']; 
            unset($_SESSION['AlertMsg']);
            
            switch ($vType) {
                case 'danger':
                    $vTitle = (empty($vTitle) ? 'Ошибка!' : $vTitle);
                    break;
                
                case 'success':
                    $vTitle = (empty($vTitle) ? 'Успех!' : $vTitle);
                    break;

                case 'warning':
                    $vTitle = (empty($vTitle) ? 'Предупреждение!' : $vTitle);
                    break;

                case 'info':
                    $vTitle = (empty($vTitle) ? 'Информация!' : $vTitle);
                    break;

                default :
                    return;
            }
            
            echo '<script> $(document).ready(function(){ $.alert("'.$vText.'", {type:"'.$vType.'", title:"'.$vTitle.'"}); }); </script>';
        }
    }
    
    function GetRaitingHTML($ARaiting, $AClass = '') {
        if ($ARaiting >= 0) {
            $t_r = (int)$ARaiting+1;
            $t_r_fl = ($ARaiting - (int)$ARaiting)*100;
           
            $vClass = (empty($AClass)) ? '' : 'class="'.$AClass.'"';
            for ($r=1; $r <= 5; $r++) {
                $vID = (empty($AClass)) ? '' : 'id="'.$AClass.'-'.$r.'"';
                
                if ($r <= $ARaiting) { 
                    echo '<img '.$vID.' '.$vClass.' src="/public/images/rating/rating+full.png"/>';
                } else {
                    if ($r == $t_r) {  
                        if ($t_r_fl >= 51 && $t_r_fl <= 99) { 
                            echo '<img '.$vID.' '.$vClass.' src="/public/images/rating/rating+75.png"/>';
                        } else if ($t_r_fl > 26 && $t_r_fl <= 50) {
                            echo '<img '.$vID.' '.$vClass.' src="/public/images/rating/rating+50.png"/>';
                        } else if ($t_r_fl >= 1 && $t_r_fl <= 25) {
                            echo '<img '.$vID.' '.$vClass.' src="/public/images/rating/rating+25.png"/>';
                        } else {
                            echo '<img '.$vID.' '.$vClass.' src="/public/images/rating/rating.png"/>';
                        }
                    } else {
                        echo '<img '.$vID.' '.$vClass.' src="/public/images/rating/rating.png"/>';
                    }  
                }  
            }  
        }
    }
    
    function SetTokenForPreventDoubleSubmit() {
        $_SESSION['token'] = md5(session_id().time());
    }
    
    function CanSubmit_CheckTokenForPreventDoubleSubmit() {
        // http://stackoverflow.com/questions/2133964/how-to-prevent-multiple-inserts-when-submitting-a-form-in-php
        if (isset($_SESSION['token'])) {
            if (isset($_POST['token'])) {
                if ($_POST['token'] != $_SESSION['token']) {
                    // double submit
                    return false;
                }
            }
        }
        
        return true;
    }

    /*function SendEmail($from, $to, $content, $attach = null) {
        require_once 'PHPMailer/class.phpmailer.php';
        $mailer = new PHPMailer();
        $mailer->CharSet = 'utf-8';
        $mailer->AddReplyTo($from, '');
        $mailer->SetFrom($from, '');
        //$mailer->AddEmbeddedImage(Tools::config('dir', 'images').'mail_logo.png', 'logo', 'logo.png', 'base64', 'application/octet-stream');

        // add addresses
        if (is_array($to)) {
            foreach ($to as $t) {
                $mailer->AddAddress($t);
            }
        } else {
            $mailer->AddAddress($to);
        }

        // add subject
        $mailer->Subject = $content['title'];

        // add content
        if (!is_array($content)) {
            $mailer->MsgHTML($content);
        } else {
            $serializeData = array();
            foreach ($content['data'] as $k=>$d) {
                //$serializeData['['.$k.']'] = $d;
                $serializeData[$k] = $d;
            }

            $template = $content['template'];
            $mailer->MsgHTML(strtr(file_get_contents(URL.'public/mails/'.$template.'.html'), $serializeData));
        }

        // add attachments
        if ($attach != null) {
            foreach ($attach as $a) {
                $mailer->AddAttachment($a['path'], $a['name']);
            }
        }

        $sendFunc = 'Send';

        $answer = (!$mailer->$sendFunc()) ? $mailer->ErrorInfo : true;

        $mailer->ClearAddresses();
        $mailer->ClearAttachments();

        return $answer;
    }*/
    
    // $fromname по умолчанию = 'Карапуз';
    function SendEmailSMTP($from, $fromname, $to, $content, $attach = null, $CCEmail = '', $BCCEmail = '') {
        require_once 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->setLanguage('ru');
        $mail->CharSet = 'utf-8';
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        //$mail->Timeout = 10;
        $mail->Host = SMTP_HOST;                              // Specify main SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = SMTP_USERNAME;                      // SMTP username
        $mail->Password = SMTP_PASSWORD;                      // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = (int)SMTP_PORT;                         // TCP port to connect to

        //$mail->From = $from;
        //$mail->FromName = 'Mailer';
        //$mail->SetFrom($from, (empty($fromname) ? 'Карапуз' : $fromname));
        $mail->SetFrom('sending@karapuz.life', (empty($fromname) ? 'Карапуз' : $fromname));
        $mail->AddReplyTo((empty($from) ? 'sending@karapuz.life' : $from), (empty($fromname) ? 'Карапуз' : $fromname));
        //$mail->AddEmbeddedImage(Tools::config('dir', 'images').'mail_logo.png', 'logo', 'logo.png', 'base64', 'application/octet-stream');

        //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        //$mail->addAddress($to);               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        
        //cc: — (от англ. carbon copy). Содержит имена и адреса вторичных получателей письма, к которым направляется копия.
        if (!empty($CCEmail)) {
            $mail->addCC($CCEmail);
        }
        
        /* bcc: — (от англ. blind carbon copy). Содержит имена и адреса получателей письма, 
         * чьи адреса не следует показывать другим получателям. Это поле обычно обрабатывается 
         * почтовым сервером (и приводит к появлению нескольких разных сообщений, у которых bcc 
         * содержит только того получателя, кому фактически адресовано письмо). 
         * Каждый из получателей не будет видеть в этом поле других получателей из поля bcc.*/
        if (!empty($BCCEmail)) {
            $mail->addBCC($BCCEmail);
        }
        
        // add addresses
        if (is_array($to)) {
            foreach ($to as $t) {
                $mail->AddAddress($t);
            }
        } else {
            $mail->AddAddress($to);
        }
        
        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = $content['title'];

        // add content
        if (!is_array($content)) {
            $mail->MsgHTML($content);
        } else {
            $serializeData = array();
            foreach ($content['data'] as $k=>$d) {
                //$serializeData['['.$k.']'] = $d;
                $serializeData[$k] = $d;
            }

            $template = $content['template'];
            $mail->MsgHTML(strtr(file_get_contents(URL.'public/mails/'.$template.'.html'), $serializeData));
        }

        // add attachments
        if ($attach != null) {
            foreach ($attach as $a) {
                $mail->AddAttachment($a['path'], $a['name']);
            }
        }
        /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

        $answer = (!$mail->send()) ? $mail->ErrorInfo : true;
        $mail->ClearAddresses();
        $mail->ClearAttachments();

        return $answer;
    }    

    /* usage example
    $string = Encrypt_Blowfish('hello world', 'abc123');
    echo 'ENCRYPTED: ' . $string . "\n";
    echo 'DECRYPTED: ' . Decrypt_Blowfish($string, 'abc123');*/
    function Decrypt_Blowfish($data, $key = '') {
        $password = empty($key) ? DEF_BLOWFISHGUID : $key;        
        $iv=pack("H*" , substr($data,0,16));
        $x =pack("H*" , substr($data,16)); 
        $res = mcrypt_decrypt(MCRYPT_BLOWFISH, $password, $x , MCRYPT_MODE_CBC, $iv);
        $res = trim($res);
        return $res;
    }

    function Encrypt_Blowfish($data, $key = '') {
        $password = empty($key) ? DEF_BLOWFISHGUID : $key;        
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_BLOWFISH, $password, trim($data), MCRYPT_MODE_CBC, $iv);
        return bin2hex($iv . $crypttext);
    }
    
    // загрузка картинок товара
    function UploadTovarImage($AFileInputName, 
            $APath, $ANewFileName, $AMax_image_x, $AMax_image_y,
            $AWatermark_position = '', $AWatermark_FileRelativeURL = '', $AFixedSourceFileName = '', $ANewFileExt = '') {
        $vError = "";
        $vFileRelativeURL = "";
        
        if (empty($AFixedSourceFileName)) {
            $ImgUploader = new Upload($_FILES[$AFileInputName], "ru_RU"); 
        } else {
            $ImgUploader = new Upload($AFixedSourceFileName, "ru_RU"); 
        }
        
        if ($ImgUploader->uploaded) {
            $ImgUploader->file_overwrite = true;
            //$ImgUploader->file_new_name_body = $ANewFileName;
            $ImgUploader->file_new_name_body = URLify::GetLatinSafeFileName($ANewFileName);
            
            if (!empty($ANewFileExt)) {
                $ImgUploader->file_new_name_ext = $ANewFileExt;
            }
            
            if ((!empty($AWatermark_position)) && ($AWatermark_position != '00') && (!empty($AWatermark_FileRelativeURL))) {
                $ImgUploader->image_watermark_position = $AWatermark_position;
                $ImgUploader->image_watermark = DIR_DBIMAGES.'comission/'.$AWatermark_FileRelativeURL;
            }

            if (($AMax_image_x != 0) && ($AMax_image_y != 0)) {
                if (($ImgUploader->image_src_x > $AMax_image_x) || ($ImgUploader->image_src_y > $AMax_image_y)) {
                    $ImgUploader->image_resize = true;
                    $ImgUploader->image_x = $AMax_image_x;
                    $ImgUploader->image_y = $AMax_image_y;
                    $ImgUploader->image_ratio = true;                    
                } else {
                    $ImgUploader->image_resize = false;
                    $ImgUploader->image_x = $ImgUploader->image_src_x;
                    $ImgUploader->image_y = $ImgUploader->image_src_y;
                    $ImgUploader->image_ratio = false;                    
                }
            }

            //$ImgUploader->image_convert = gif;
            //$ImgUploader->image_ratio_y = true;

            $UploadDir = MakeDirForWrite($APath);
            
            $ImgUploader->Process($UploadDir);
            if ($ImgUploader->processed) {
                $vFileRelativeURL = $APath.$ANewFileName.".".$ImgUploader->file_dst_name_ext;
                $ImgUploader->Clean();                
            } else {
                $vError = $ImgUploader->error;
            } 
        } else {
            $vError = $ImgUploader->error;
        }
        
        return array($vError, $vFileRelativeURL);
    }
    
    function GetLoaderImageHTML($ALoaderFileName = 'loader1.gif') {
        return '<img class="display_none" src="'.URL.'public/images/'.$ALoaderFileName.'" alt="" />';
    }
    
    function GetImageLoadOrPreviewBoxHTML($ImageRelativeURL, $BoxWidth, $BoxHeight, $LoadComment, $LoadInputName, 
                                          $ImgPreviewWidth = 0, $ImgPreviewHeight = 0, $ShowCopyURLBtn = true, 
                                          $ShowDeleteBtn = true, $LoadMultiple = false, $ALoaderFileName = 'loader1.gif') 
    {
        if (empty($ImageRelativeURL)) {
            echo '<div class="btn btn-default btn-file add_image" style="width: '.$BoxWidth.'px; height: '.$BoxHeight.'px;"><img class="display_none" src="'.URL.'public/images/'.$ALoaderFileName.'">'.$LoadComment.' <input type="file" name="'.$LoadInputName.'" accept="image/*"'.(($LoadMultiple == true) ? " multiple" : "").'></div>';
        } else {
            $PreviewStyle = "";
            if (($ImgPreviewWidth != 0) && ($ImgPreviewHeight != 0)) {
                $PreviewStyle = 'style="width: '.$ImgPreviewWidth.'px; height: '.$ImgPreviewHeight.'px;"';
            }
                
            echo '<div class="image_box" style="width: '.$BoxWidth.'px; height: '.$BoxHeight.'px;">'.
                    '<div class="image_box_options">'.
                        (($ShowDeleteBtn == true) ? '<button type="submit" class="BtnAsImg_Right delete_image_control" name="ImgDelBtn[main]" value="'.$ImageRelativeURL.'" title="Удалить изображение"><img src="'.URL.'public/images/delete24x24.png" alt=""></button>' : '').
                        (($ShowCopyURLBtn == true) ? '<button class="BtnAsImg_Right copy_url_control" value="'.URL.$ImageRelativeURL.'" title="Копировать URL изображения"><img src="'.URL.'public/images/copy24x24.png" alt=""></button>' : '').
                    '</div>'.
                    '<div class="image_box_preview">'.
                        '<img '.$PreviewStyle.' src="'.GetFileURLWithFileDate($ImageRelativeURL).'">'.
                    '</div>'.
                 '</div>';
        }
    }

    function GetFilemtime($AFileRelativeURL) {
        return (file_exists(DIR_ROOT_ABSOLUTE.$AFileRelativeURL) ? filemtime(DIR_ROOT_ABSOLUTE.$AFileRelativeURL) : '');
    }
    
    function GetFileURLWithFileDate($AFileRelativeURL) {
        // Эта функция необходима, для создания url к файлу, который будет изменяться, если дата 
        // картинки будет изменена (авто обновление кеша для браузера)
        if (empty($AFileRelativeURL)) {
            return '';
        } else {
            return URL.$AFileRelativeURL.'?'.GetFilemtime($AFileRelativeURL);
        }
    }
    
    function GetFileLoadBoxHTML($BoxWidth, $BoxHeight, $LoadComment, $LoadInputName, $LoadFileFilter = "", $LoadMultiple = false, $ALoaderFileName = 'loader1.gif') 
    {
        echo '<div class="btn btn-default btn-file add_image" style="width: '.$BoxWidth.'px; height: '.$BoxHeight.'px;"><img class="display_none" src="'.URL.'public/images/'.$ALoaderFileName.'">'.$LoadComment.' <input type="file" name="'.$LoadInputName.'" '.((empty($LoadFileFilter))? '' : ' accept="'.$LoadFileFilter.'" ').(($LoadMultiple == true) ? " multiple" : "").'></div>';
            //<div class="btn btn-default btn-file add_image" style="width: 240px; height: 240px;"><img />Добавить документ<input type="file" name="AddFileEdt"></div>
    }
    
    function GetSaveDeleteCloseButtonsHTML($ShowSaveAndStay = true, $ShowDelete = false, $ShowSaveAndClose = true, $ShowClose = true) {
        if ($ShowSaveAndStay == true) {
            echo '<button name="SaveAndStayBtn" type="submit" class="btn btn-primary submit_changes_control">'.GetLoaderImageHTML().'Сохранить</button>';
        }
        
        if ($ShowSaveAndClose == true) {
            echo '<button name="SaveAndCloseBtn" type="submit" class="btn btn-success submit_changes_control">'.GetLoaderImageHTML().'Сохранить и закрыть</button>';
        }

        if ($ShowClose == true) {
            echo '<button name="CloseBtn" type="submit" class="btn btn-warning submit_control">Закрыть</button>';
        }

        if ($ShowDelete == true) {
            echo '<button name="DeleteBtn" type="submit" class="btn btn-default delete_record_control">Удалить запись</button>';
        }
    }    
    
    function GetArticleCommentsHTML($ArticleComments, $AuthorID) {
        $vResult = '';
    
        foreach ($ArticleComments as $a) {
            $UserDesc = '';
            if ($a["UserID"] === '0') {
                $UserDesc = '(гость) ';
            } else if ($a["UserID"] === $AuthorID) {
                $UserDesc = '<b>(автор статьи)</b> ';
            }
                        
            $vResult = $vResult.        
                '<div class="article_comment_item">'.
                    '<div class="article_comment_title">'.
                        '<label class="article_comment_user">'.$a["UserName"].'</label>'.
                        '<label class="article_comment_time">'.$UserDesc.date("d.m.Y, H:i", strtotime($a['CommentDate'])).'</label>'.
                    '</div>'.
                    '<div class="article_comment_text">'.$a["Comment"].'</div>'.
                '</div>';
        }
        
        return $vResult;
    }

    function GetCatalogCommentsHTML($Comments) {
        $vResult = '';
    
        foreach ($Comments as $a) {
            $UserDesc = '';
            if ($a["UserID"] === '0') {
                $UserDesc = '(гость) ';
            }
                        
            $vResult = $vResult.        
                '<div class="comment_item">'.
                    '<div class="comment_title">'.
                        '<label class="comment_user">'.$a["UserName"].'</label>'.
                        '<label class="comment_time">'.$UserDesc.date("d.m.Y, H:i", strtotime($a['CreateDate'])).'</label>'.
                    '</div>'.
                    '<div class="comment_text">'.$a["Text"].'</div>'.
                '</div>';
        }
        
        return $vResult;
    }
    
    function TryToLoginUsingCookie() {
        $CookieName = "unvusrid";

        if (isset($_SESSION['unvusrid'])) {
            // Продлить срок жизни cookies до 60 дней    
            SetCookie($CookieName, Encrypt_Blowfish($_SESSION['unvusrid']), time()+(3600*24*60), "/"); // время жизни 60 дней            
            unset($_SESSION['unvusrid']);
        }
        
        if (isset($_COOKIE[$CookieName]) && (GetUserID() == 0)) {
            LoginUsingUniversalID(Decrypt_Blowfish($_COOKIE[$CookieName]));
        }
    }
    
    function LoginUsingUniversalID($AUniversalID) {
        $vResult = false;
        
        $sql = "select U.ID, U.Email, U.UniversalType, U.AccessDataLastUpdate, U.AccessToken, U.RememberMe, UD.FirstName  ".
               "from Users as U ".
               "left outer join UserData as UD on (U.ID = UD.UserID) ".
               "where (U.UniversalID = '$AUniversalID');";
        $UserData = GetMainConnection()->query($sql)->fetch();
        
        if (!empty($UserData['ID'])) {
            if ($UserData['RememberMe'] === '1') {
                // Продлить срок жизни cookies до 60 дней    
                $_SESSION['unvusrid'] = $AUniversalID;
                // Продлить срок жизни cookies до 60 дней    
                //SetCookie("unvusrid", Encrypt_Blowfish($AUniversalID), time()+(3600*24*60), "/"); // время жизни 60 дней
                
                // Обновление делаем только один раз в день
                /*if (empty($UserData['AccessDataLastUpdate']) || (strtotime($UserData['AccessDataLastUpdate']) < GetLocalDateTime()->modify('-1 day'))) {
                    // Записать дату последнего обновления cookies или AccessToken
                    $sql = "update Users ".
                           "set AccessDataLastUpdate = '".GetLocalDateTimeAsSQLStr()."' ".
                           "where (ID = ".$UserData['ID'].");";
                    GetMainConnection()->exec($sql);                

                    
                    switch ($UserData['UniversalType']) {
                        case '1': // Регистрация через сайт
                            break;

                        case '2': // Регистрация через Facebook
                            // Продлить срок жизни AccessToken на 60 дней
                            // https://developers.facebook.com/docs/facebook-login/access-tokens
                            // 
                            // Best practices for maintaining long loved access tokens over time
                            // https://www.sammyk.me/access-token-handling-best-practices-in-facebook-php-sdk-v4 ()

                            if (!empty($UserData['AccessToken'])) {
                                require_once PATH_SITE_ROOT . 'core/facebook-php-sdk-v4-5.0-dev/src/Facebook/autoload.php';
                                require_once PATH_SITE_ROOT . 'core/facebook-php-sdk-v4-5.0-dev/src/Facebook/Authentication/AccessToken.php';

                                $longLivedAccessToken = new AccessToken($UserData['AccessToken']);
                                try {
                                    // Get a code from a long-lived access token
                                    $code = AccessToken::getCodeFromAccessToken($longLivedAccessToken);
                                    try {
                                         // Get a new long-lived access token from the code
                                        $newLongLivedAccessToken = AccessToken::getAccessTokenFromCode($code);
                                      
                                        // Make calls to Graph using $shortLivedAccessToken
                                        $session = new FacebookSession($newLongLivedAccessToken);

                                        $request = new FacebookRequest($session, 'GET', '/me');
                                        $userData = $request->execute()->getGraphObject()->asArray();
                                        var_dump($userData); 
                                        
                                        
                                        update $sql = "insert into Users(UniversalType, UniversalID, AccessToken, UserName, Email, EmailConfirmed, RememberMe) ".
                                               "values(2, '$vUniversalID', '$accessTokenStr', '$userEmail', '$userEmail', 1, 1) ".
                                               "on duplicate key update ".
                                               "UniversalID = '$vUniversalID';";
                                        GetMainConnection()->exec($sql);
                                    } catch(FacebookSDKException $e) {
                                      //echo 'Error getting a new long-lived access token: ' . $e->getMessage();
                                      //exit;
                                    }                                  
                                } catch(FacebookSDKException $e) {
                                  //echo 'Error getting code: ' . $e->getMessage();
                                  //exit;
                                }
                            }

                            break;
                    }
                }*/
            }        

            RegisterUserInSession($UserData);
            $vResult = true;
        }
        
        unset($AUniversalID);
        unset($UserData);
        return $vResult;
    }
    
    function RegisterUserInSession($AUserData) {
        $_SESSION['auth'] = array(
            'id' => $AUserData['ID'],
            'email' => $AUserData['Email'],
            'firstname' => $AUserData['FirstName'],
            'type' => $AUserData['UniversalType'],
            'AccessToken' => $AUserData['AccessToken']
        );
    }
    
    function EchoKarapuzRecommendedBlockHTML($AMainDivClass = 'karapuz_recommended_block') {
        $sql = "select title, uri, img from recommend where id = recommend_GetRandomID();";
        $rec = GetMainConnection()->query($sql)->fetch();
        
        if (!empty($rec['img'])) { 
            echo 
                '<div id="'.$AMainDivClass.'">'.
                    '<div class="recommended">'.
                        '<div class="recommended_header">Карапуз рекомендует</div>'.
                        '<div id="white_line_3px"></div>'.
                        '<a href="'.$rec['uri'].'">'.
                            '<div class="recommended_text">'.$rec['title'].'</div>'.
                            '<img src="'.GetFileURLWithFileDate($rec['img']).'">'.
                        '</a>'.                    
                    '</div>'.
                    '<div class="recommended_logo"></div>'.
                '</div>';
        }
    }
    
    function EchoInterestingBlockHTML($AMainDivClass = 'middle_right_block') {
        $sql = "select title, body, img, uri from interes where id = interes_GetRandomID();";
        $rec = GetMainConnection()->query($sql)->fetch();
        
        if (!empty($rec['img'])) { 
            echo 
                '<div class="'.$AMainDivClass.'">'.
                    '<div class="newby">'.
                        '<div class="newby_h3">Это интересно</div>'.
                        '<a href="'.$rec['uri'].'">'.
                            '<img src="'.GetFileURLWithFileDate($rec['img']).'" width="266" height="200">'.
                            '<span class="newby_title">'.$rec['title'].'</span>'.
                            '<span class="newby_body">'.$rec['body'].'</span>'.
                        '</a>'.                    
                    '</div>'.
                '</div>';
        }
    }
    
    function EchoInnovationBlockHTML($AMainDivClass = 'middle_right_block') {
        $sql = "select title, body, img, uri from innovation where id = innovation_GetRandomID();";
        $rec = GetMainConnection()->query($sql)->fetch();
        
        if (!empty($rec['img'])) { 
            echo 
                '<div class="'.$AMainDivClass.'">'.
                    '<div class="newby">'.
                        '<div class="newby_h3">Новшества</div>'.
                        '<a href="'.$rec['uri'].'">'.
                            '<img src="'.GetFileURLWithFileDate($rec['img']).'" width="266" height="200">'.
                            '<span class="newby_title">'.$rec['title'].'</span>'.
                            '<span class="newby_body">'.$rec['body'].'</span>'.
                        '</a>'.                    
                    '</div>'.
                '</div>';
        }
    }
    
    function EchoAdvertisementBlockHTML($AMainDivClass = 'middle_right_block_adv') {
    	// NewBlock300x250
        echo 
            '<div class="'.$AMainDivClass.'">'.
                '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>'.
                    '<ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-7523142643336947" data-ad-slot="6347754113"></ins>'.
                '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>'.
            '</div>';
    }
    
	function EchoArticleCategoriesHTML($AMainDivClass = 'site_part') {
        $sql = "SELECT ID, Name FROM ArticleCategories WHERE IsDeleted = 0;";
        $categories = GetMainConnection()->query($sql)->fetchAll();

		$counter = count($categories);
		$vResult = '<ul class="level1 acordion">'.
                       '<li class="line"></li>'.
                       '<li><a href="/category/" class="name">Все статьи</a></li>';
                
        foreach ($categories as $r){
			$counter = $counter-1;
			$vResult = $vResult.
                '<li class="line"></li>'.
                '<li '.(($counter == 0) ? 'class="last"' : '').'>'.
                    '<a href="/articles/c-'.$r['ID'].'/" class="name">'.$r['Name'].'</a>'.
                '</li>';
		}
		
        $vResult = $vResult.'</ul>';		

        echo 
            '<div class="'.$AMainDivClass.'">'.
                '<div class="site_part_container">'.
                '<div class="site_part_h3">Категории статей</div>'.
                    $vResult.
                '</div>'.
            '</div>'.
            '<div id="block_bottom_flower"></div>';  
	}
    
    function InitDebugLog() {
        if (!empty(GETAsStr('log'))) {
            global $DebugLog;
            global $DebugLog_PriorTime;
            $DebugLog_PriorTime = microtime(true);
            $now = DateTime::createFromFormat('U.u', number_format($DebugLog_PriorTime, 6, '.', ''));
            $DebugLog = '<br /><br />'.$now->format("i:s.u").' - Log START';
        }
    }
    
    function AddDebugLog($Text) {
        global $DebugLog;
        if (isset($DebugLog)) {
            global $DebugLog_PriorTime;
            $NowTime = microtime(true);
            $now = DateTime::createFromFormat('U.u', number_format($NowTime, 6, '.', ''));
            $DiffTime = round(($NowTime-$DebugLog_PriorTime), 3);
            $DebugLog = $DebugLog.'<br />'.$now->format("i:s.u").' ('.$DiffTime.') - '.$Text;
            $DebugLog_PriorTime = $NowTime;
        }
    }

    function ShowDebugLog() {
        global $DebugLog;
        if (isset($DebugLog)) {
            global $DebugLog_PriorTime;
            $NowTime = microtime(true);
            $now = DateTime::createFromFormat('U.u', number_format($NowTime, 6, '.', ''));
            $DiffTime = round(($NowTime-$DebugLog_PriorTime), 3);
            echo $DebugLog.
                 '<br />'.$now->format("i:s.u").' ('.$DiffTime.') - Log END'.
                 '<br />Request full time = '.round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]), 3).
                 '<br /><br /><br /><br /><br /><br />';
            
            unset($DebugLog_PriorTime);
            unset($DebugLog);
        }
    }
    
    function AddTaskForSendEmail_UseBody($AFromEmail, $AFromName, $AToEmail, $AToName, $ASubject, $ABody, $APriority = 50, $ABlindCopyToEmail = '', $ACopyToEmail= '') {
        $sql = "insert into Emails_ToSend(RecordGUID, CreateDate, FromEmail, FromName, ToEmail, ToName, CopyToEmail, BlindCopyToEmail, Subject, Body, Priority) ".
               "values('".getGUID()."', '".  GetLocalDateTimeAsSQLStr()."', '$AFromEmail', '$AFromName', '$AToEmail', '$AToName', '$ACopyToEmail', '$ABlindCopyToEmail', '$ASubject', '".base64_encode($ABody)."', $APriority);";
        GetMainConnection()->exec($sql);
        
        CURL_SpeedUp_SendEmail();
    }
    
    function AddTaskForSendEmail_UseTemplate($AFromEmail, $AFromName, $AToEmail, $AToName, $ASubject, $AUseTemplate, $ATemplateParamsArray, $APriority = 50, $ABlindCopyToEmail = '', $ACopyToEmail= '') {
        if (isset($ATemplateParamsArray)) {
            $vTemplateParamsArray = base64_encode(serialize($ATemplateParamsArray));
        } else {
            $vTemplateParamsArray = '';
        }
        
        $sql = "insert into Emails_ToSend(RecordGUID, CreateDate, FromEmail, FromName, ToEmail, ToName, CopyToEmail, BlindCopyToEmail, Subject, Body, UseTemplate, Priority) ".
               "values('".getGUID()."', '".  GetLocalDateTimeAsSQLStr()."', '$AFromEmail', '$AFromName', '$AToEmail', '$AToName', '$ACopyToEmail', '$ABlindCopyToEmail', '$ASubject', '$vTemplateParamsArray', '$AUseTemplate', $APriority);";
        GetMainConnection()->exec($sql);
        $vTemplateParamsArray = '';
        
        CURL_SpeedUp_SendEmail();
        
        //$YourSerializedData = base64_encode(serialize($theHTML));
        //$theHTML = unserialize(base64_decode($YourSerializedData));
    }
    
    function IsNewTaskInstanceCanBeRunning($ATaskName, $APossibleLastActivity) {
        $sql = "select LastActivity, IsBusy ".
               "from TaskActivity ".
               "where (TaskName = '$ATaskName') ".
               "limit 1;";
        $rec = GetMainConnection()->query($sql)->fetch();
        
        if ($rec === FALSE) {
            $sql = "insert into TaskActivity(TaskName, LastActivity, IsBusy) ".
                   "values('$ATaskName', '".GetLocalDateTimeAsSQLStr()."', 1);";
            GetMainConnection()->exec($sql);
            
            return true;
        } else {
            if ($rec['IsBusy'] === '0') {
                $sql = "update TaskActivity ".
                       "set LastActivity = '".GetLocalDateTimeAsSQLStr()."', ".
                           "IsBusy = 1 ".
                       "where (TaskName = '$ATaskName');";
                GetMainConnection()->exec($sql);

                return true;
            } else {
                if (strtotime($rec['LastActivity'] < $APossibleLastActivity)) {
                    $sql = "update TaskActivity ".
                           "set LastActivity = '".GetLocalDateTimeAsSQLStr()."', ".
                               "IsBusy = 1 ".
                           "where (TaskName = '$ATaskName');";
                    GetMainConnection()->exec($sql);

                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    
    function SetTaskActivity($ATaskName, $Active) {
        $sql = "update TaskActivity ".
               "set LastActivity = '".GetLocalDateTimeAsSQLStr()."', ".
                   "IsBusy = ".($Active ? "1" : "0")." ".
               "where (TaskName = '$ATaskName');";
        GetMainConnection()->exec($sql);
    }    
    
    function CURL_SpeedUp_SendEmail() {
        // Вызываем URL из командной строки curl на хостинге, для ускорения отправки письма 
        // (т.к. CRON по отправке работает раз в 5 минут)
        // https://segment.com/blog/how-to-make-async-requests-in-php/

        $url = URL."app/common/task_emails_sending.php";
        $cmd = "curl -X POST -H 'Content-Type: application/json' ";
        //$cmd.= " -d '";// . $payload . "' " . "'" . $url . "'";
        $cmd.=  "'" . $url . "'";

        //if (!$this->debug()) {
          $cmd .= " > /dev/null 2>&1 &";
        //}

        exec($cmd, $output, $exit);
        return $exit == 0;
    }
    
    /* Document Types
     * 1 - Статьи
    */
    function Emails_AddNotifyRecipient($ADocumentType, $ADocumentID, $AEmail) {
        if (!empty($AEmail)) {
            $sql = "insert into Emails_NotifyRecipients(RecordGUID, DocumentType, DocumentID, Email) ".
                   "values(UUID(), $ADocumentType, $ADocumentID, lower('$AEmail')) ".
                   "on duplicate key update ".
                   "DocumentType = $ADocumentType;";
            GetMainConnection()->exec($sql);
        }
    }
    
    function Emails_DeleteNotifyRecipient($ADocumentType, $ADocumentID, $AEmail) {
        if (!empty($AEmail)) {
            $sql = "delete from Emails_NotifyRecipients ".
                   "where DocumentType = $ADocumentType ".
                   "and DocumentID = $ADocumentID ".
                   "and lower(Email) = lower('$AEmail');";
            GetMainConnection()->exec($sql);
        }
    }

    function Emails_AddNotify($ADocumentType, $ADocumentID, $ASubject, $ABody, $AOwnerEmail) {
        $sql = "insert into Emails_Notify(RecordGUID, DocumentType, DocumentID, CreateDate, Subject, Body, OwnerEmail) ".
               "values(UUID(), $ADocumentType, $ADocumentID, '".GetLocalDateTimeAsSQLStr()."', '".mb_strimwidth(ClearSQLStr($ASubject), 0, 252, '...')."', '".base64_encode($ABody)."', lower('$AOwnerEmail'));";
        GetMainConnection()->exec($sql);
        
        CURL_SpeedUp_SendEmail();
    }
    
    function Emails_IsNotifyRecipientActive($ADocumentType, $ADocumentID, $AEmail) {
        $sql = "select RecordGUID ".
               "from Emails_NotifyRecipients ".
               "where DocumentType = $ADocumentType ".
               "and DocumentID = $ADocumentID ".
               "and lower(Email) = lower('$AEmail') ".
               "limit 1;";
        $rec = GetMainConnection()->query($sql)->fetch();
        if ($rec === false) {
            return false;
        } else {
            return empty($rec['RecordGUID']) ? false : true;
        }
    }
    
    function Emails_PrepareBodyUseTemplate($ATemplateName, $ATemplateParamsArray) {
        return strtr(file_get_contents(URL.'public/mails/'.$ATemplateName.'.html'), $ATemplateParamsArray);
    }
  
    function EchoAuthorArticleBlockHTML($AAuthorID, $AArticleID) {
        $sql = "select ID, CategoryID, Name ".
               "from Articles ".
               "where AuthorID = $AAuthorID ".
               "and ID <> $AArticleID ".
               "and IsActive = 1 ".
               "and IsDeleted <> 1 ".
               "order by CreateDate desc ".
               "limit 3;";
        $records = GetMainConnection()->query($sql)->fetchAll();
        
        $vResult = '';
    
        foreach ($records as $r) {
            $vResult = $vResult.'<li><a href="/articles/c-'.$r['CategoryID'].'/a-'.$r['ID'].'">'.$r['Name'].'</a></li>';
        }
        
        if (!empty($vResult)) {
            echo 'Другие статьи автора:<br /><ul>'.
                 $vResult.
                 '<li><a href="/search/?author='.$AAuthorID.'">Все статьи автора.</a></li></ul>';
        }
    }
    
?>