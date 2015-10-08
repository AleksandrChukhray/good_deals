<?php
class AuthController extends Controller {
    public function __construct($context){
        parent::__construct($context);
        
        if (isset($_SESSION['auth']) && $this->path['action'] != 'logout' && $this->path['action'] != 'contactus') {
            return AddAlertMessage('info', 'Вы уже авторизированны', '/');
        }
    }
    
    public function indexAction(){
        Redirect('/auth/login');
    }
    
    public function registrationAction() {
        $ErrorMessage = '';
        $vEmail = '';
        $vFirstName = '';
        $vLastName = '';
        
        if (filter_input(INPUT_POST, 'registration_btn') !== NULL) {
            $vEmail = POSTStrAsSQLStr('email');
            $vFirstName = POSTStrAsSQLStr('firstname');
            $vLastName = POSTStrAsSQLStr('lastname');

            if (empty($ErrorMessage)) {
                if (!preg_match("/[\x{0400}-\x{04FF}\x]{1,32}/u", $vFirstName)) {
                    $ErrorMessage = 'Имя пользователя должно содержать только символы кириллицы.';
                }
            }
            
            if (empty($ErrorMessage)) {
                if ($_POST['password'] != $_POST['password2']) {
                    //return AddAlertMessage('danger', 'Пароли не совпадают', '/auth/registration');
                    $ErrorMessage = 'Пароль и подтверждение пароля не совпадают.';
                }
            }
            
            if (empty($ErrorMessage)) {
                $vSecurimage = new Securimage();
                if (!$vSecurimage->check($_POST['CaptchaCodeEdt']) == true) {
                    $ErrorMessage = 'Вам нужно решить пример правильно.';
                }
            }            
            
                
            if (empty($ErrorMessage)) {
                $sql = "select ID from Users where (Email = '$vEmail');";
                $user = $this->db->query($sql)->fetch();

                if (!empty($user['ID'])) {
                    //return AddAlertMessage('danger', 'Такой e-mail уже зарегистрирован', '/auth/registration');
                    $ErrorMessage = "Пользователь с такой эл. почтой уже существует.";
                    $vEmail = '';
                } else {
                    $vUniversalID = getGUID();

                    /*$this->db->prepare('INSERT INTO Users (PasswordHash, UserName, PhoneNumber, Email, EmailConfirmed, UniversalType, UniversalID) '
                            . 'VALUES (:ph, :un, :pn, :e, 1, 1, :uid)')->
                            execute(array('ph' => EncryptPassword(Tools::getValue('password')), 'un' => Tools::getValue('email'), 'pn' => Tools::getValue('phone'), 'e' => Tools::getValue('email'), 'uid' => $vUniversalID));
                    $id = $this->db->lastInsertId();
                    $this->db->prepare('INSERT INTO UserData (UserID, FirstName, LastName) VALUES (:ui, :fn, :ln)')->execute(array('ui' => $id, 'fn' => Tools::getValue('firstname'), 'ln' => Tools::getValue('lastname')));
                    */    

                    $sql = "insert into Users(UniversalType, UniversalID, UserName, Email, EmailConfirmed, PasswordHash) ".
                           "values(1, '$vUniversalID', '$vEmail', '$vEmail', 1, '".EncryptPassword($_POST['password'])."') ".
                           "on duplicate key update ".
                           "UniversalID = '$vUniversalID';";
                    $this->db->exec($sql);      

                    $UserID = $this->db->lastInsertId();

                    $sql = "insert into UserData(UserID, FirstName, LastName) ".
                           "values($UserID, '$vFirstName', '$vLastName') ".
                           "on duplicate key update ".
                           "UserID = $UserID;";
                    $this->db->exec($sql);


                    if (LoginUsingUniversalID($vUniversalID)) {
                        return AddAlertMessage('success', 'Добро пожаловать!', '/');
                    } else {
                        return AddAlertMessage('danger', 'Ошибка при регистрации!', '/');
                    }
                }
            }
        }
        
        if (!empty($ErrorMessage)) {
            AddAlertMessage('danger', $ErrorMessage);
        }
        
        $this->view->setVars(array(
            'Email' => $vEmail,
            'FirstName' => $vFirstName,
            'LastName' => $vLastName
        ));

        $this->view->breadcrumbs = array(array('url' => '/auth/registration', 'title' => 'Регистрация'));
        
        $this->view->meta = array(
            'meta_title' => 'Регистрация пользователя',
            'meta_description' => 'Регистрация пользователя',
            'meta_keywords' => ''
        );
        
        $this->view->generate();
    }
    
    public function loginAction(){
        if (Tools::isPost()) {
            $email = Tools::getValue('email');
            $password = Tools::getValue('password');
            
            $sql = "select ID, UniversalID, PasswordHash from Users where (UniversalType = 1) and (email = '$email');";
            $user = GetMainConnection()->query($sql)->fetch();
           
            if (!empty($user['ID'])) {
                if (VerifyPassword($password, $user['PasswordHash'])) {
                    unset($password);
                    
                    $sql = "update Users ".
                           "set RememberMe = '".POSTBoolAsSQLStr('RememberMeEdt')."' ".
                           "where (ID = ".$user['ID'].");";
                    GetMainConnection()->exec($sql);                
                    
                    if (LoginUsingUniversalID($user['UniversalID'])) {
                        if (empty($_SESSION['login_redirect'])) {
                            return AddAlertMessage('success', 'Добро пожаловать!', '/');
                        } else {
                            $vRedirect = $_SESSION['login_redirect'];
                            unset($_SESSION['login_redirect']);
                            Redirect($vRedirect);
                        }
                    }
                } else {
                    unset($password);
                    AddAlertMessage('danger', 'Неверный e-mail или пароль.');
                }
            } else {
                unset($password);
                AddAlertMessage('danger', 'E-mail не найден.');                        
            }
        }
        
        // https://developers.facebook.com/docs/php/gettingstarted/5.0.0
        // https://developers.facebook.com/docs/php/Facebook/5.0.0
        // http://25labs.com/tutorial-integrate-facebook-connect-to-your-website-using-php-sdk-v-3-x-x-which-uses-graph-api/
        require_once PATH_SITE_ROOT . 'core/facebook-php-sdk-v4-5.0-dev/src/Facebook/autoload.php';
        
        $facebook = new Facebook\Facebook([
            'app_id' => facebook_app_id,
            'app_secret' => facebook_app_secret,
            'default_graph_version' => facebook_graph_version
            ]);
        
        $helper = $facebook->getRedirectLoginHelper();
        $permissions = ['email']; // optional
        $FB_LoginUrl = $helper->getLoginUrl('http://karapuz.life/app/common/facebook_login_callback.php', $permissions);

        
        $VK_LoginUrl = 'https://oauth.vk.com/authorize?client_id='.vk_app_id.'&scope=offline,email&redirect_uri='.
                urlencode('http://karapuz.life/app/common/vk_login_callback.php').'&response_type=code';
        
        $this->view->setVars(array(
            'FB_LoginUrl' => $FB_LoginUrl,
            'VK_LoginUrl' => $VK_LoginUrl
        ));            
        
        $this->view->breadcrumbs = array(array('url' => '/auth/login', 'title' => 'Вход на сайт'));
        
        $this->view->meta = array(
            'meta_title' => 'Войти на сайт',
            'meta_description' => 'Войти на сайт',
            'meta_keywords' => ''
        );
        
        $this->view->generate();
    }
    
    public function logoutAction(){
        $logoutUrl = '/';
        
        /*https://www.sammyk.me/access-token-handling-best-practices-in-facebook-php-sdk-v4*/
        /*
        if (isset($_SESSION['auth']) && 
            isset($_SESSION['auth']['type']) && 
            isset($_SESSION['auth']['AccessToken'])
           ) {
            
            switch ($_SESSION['auth']['type']) {
                case '2':
                    require_once PATH_SITE_ROOT . 'core/facebook-php-sdk-v4-5.0-dev/src/Facebook/autoload.php';

                    $facebook = new Facebook\Facebook([
                        'app_id' => facebook_app_id,
                        'app_secret' => facebook_app_secret,
                        'default_graph_version' => facebook_graph_version
                        ]);

                    $helper = $facebook->getRedirectLoginHelper();
                    $logoutUrl = $helper->getLogoutUrl($_SESSION['auth']['AccessToken'], 'http://karapuz.life/');
                    break;

                case '3':
                    //$logoutUrl = 'https://login.vk.com/?act=openapi&oauth=1&aid='.vk_app_id.'location=karapuz.life"&do_logout=1&token='.$_SESSION['auth']['AccessToken'];
                    break;
            }
        }*/
        
        unset($_SESSION['auth']);
        
        SetCookie("unvusrid", "", time()-3600, "/"); // удалить cookie установив ему время жизни -1 день
        /*setcookie ("fbsr_".facebook_app_id, "", time() - 3600);
        setcookie ("fbm_".facebook_app_id, "", time() - 3600);
        
        if (isset($_SESSION['fb_' . FB_APP_ID . '_code'])) {
            unset ($_SESSION['fb_' . FB_APP_ID . '_code']);
        }
        if (isset($_SESSION['fb_' . FB_APP_ID . '_access_token'])) {
            unset ($_SESSION['fb_' . FB_APP_ID . '_access_token']);
        }
        if (isset($_SESSION['fb_' . FB_APP_ID . '_user_id'])) {
            unset ($_SESSION['fb_' . FB_APP_ID . '_user_id']);
        } */       
        
        AddAlertMessage('info', 'До свидания!', $logoutUrl);
    }
    
    // restore password
    public function recoveryAction() {
        if (Tools::isPost()) {
            $email = POSTStrAsSQLStr('email');
            $sql = "select ID, PasswordHash ".
                   "from Users ".
                   "where (Email = '$email');";
            $user = $this->db->query($sql)->fetch();

            if ((!empty($email)) && (!empty($user['ID']))) {
                $content = array(
                    'title' => 'Восстановление пароля',
                    'template' => 'user-password-recovery',
                    'data' => array('[year]' => date("Y",time()), 
                                    //'[url]' => 'http://karapuz.life/auth/password/?u='.$user->ID.'&hash='.md5($user->ID.$user->Email)
                                    '[url]' => URL.'auth/resetpassword?m='.Encrypt_Blowfish($email).'&h='.Encrypt_Blowfish($user['PasswordHash'])
                                   )
                );
                $vSendResult = SendEmailSMTP('', '', $email, $content);

                if ($vSendResult !== true) {
                    return AddAlertMessage('danger', 'Ошибка при отправке письма!', '/');
                } else {
                    return AddAlertMessage('success', 'Инструкция по восстановлению пароля отправлена на почту.', '/');
                }
            } else {
                return AddAlertMessage('danger', 'Указанный E-mail не найден.', '/auth/recovery');
            }
        }
            
        $this->view->breadcrumbs = array(array('url' => '/auth/recovery', 'title' => 'Восстановление пароля'));
        
        $this->view->meta = array(
            'meta_title' => 'Восстановление пароля',
            'meta_description' => 'Восстановление пароля',
            'meta_keywords' => ''
        );
        
        $this->view->generate();
    }

    public function resetpasswordAction() {
        if (!Tools::isPost()) {
            // если открыли форму ссылкой из письма
            $Email = GETAsStrOrDef('m', '');
            $PasswordHash = GETAsStrOrDef('h', '');

            if (empty($Email) || empty($PasswordHash)) {
                return AddAlertMessage('danger', 'Неверный запрос на восстановление пароля!', '/');
            }
            
            $Email = empty($Email) ? '' : Decrypt_Blowfish($Email);
            $PasswordHash = empty($PasswordHash) ? '' : Decrypt_Blowfish($PasswordHash);
            
            $sql = "select PasswordHash ".
                   "from Users ".
                   "where (Email = '$Email');";
            $user = $this->db->query($sql)->fetch();
            
            if ($PasswordHash != $user['PasswordHash']) {
                return AddAlertMessage('danger', 'Неверный код восстановления пароля!', '/');
            }
            
            $this->view->setVars(array(
                'Email' => $Email,
                'EncryptedEmail' => GETAsStrOrDef('m', ''),
                'EncryptedPasswordHash' => GETAsStrOrDef('h', '')
            ));            
        } else {
            $NewPassword = POSTStrAsSQLStr('password');
            // если нажали на кнопку "Изменить пароль"
            if ($NewPassword != POSTStrAsSQLStr('confirmpassword')) {
                // проверка на всякий случай, но основная работа будет в validation.js ($('#ResetPasswordBtn').click(function(){) 
                return AddAlertMessage('danger', 'Пароли не совпадают', '/auth/resetpassword?m='.$_POST['EncryptedEmail'].'&h='.$_POST['EncryptedPasswordHash']);
            }            

            $vEmail = Decrypt_Blowfish(POSTStrAsSQLStr('EncryptedEmail'));
            $sql = "update Users set PasswordHash = '".EncryptPassword($NewPassword)."' where Email = '$vEmail';";
            $this->db->exec($sql);
            
            return AddAlertMessage('success', 'Пароль успешно изменен!', '/');
        }

        $this->view->breadcrumbs = array(array('url' => '/auth/password', 'title' => 'Изменение пароля'));
        
        $this->view->meta = array(
            'meta_title' => 'Изменение пароля',
            'meta_description' => 'Изменение пароля',
            'meta_keywords' => ''
        );

        $this->view->generate();
    }


    /* Contact us */
    public function contactusAction(){
        $email = Tools::getValue('email');                
        $name = Tools::getValue('name');
        $subject = Tools::getValue('subject');
        $question = Tools::getValue('question');
        if (Empty($name)) {
            $name = $email;                        
        }

        if (Tools::isPost()) {
            if (isset($_SESSION['auth'])) {
                $vUserID = $_SESSION['auth']['id'];
                $vUnknownUserGUID = "null";
            } else {
                $vUserID = "null";
                $vUnknownUserGUID = "'".(string)GetUnknownUserGUID()."'";
            }

            $RecordID = DBInsertRecord($this->db, "ContactUs");
            $sql = "update ContactUs ".
                   "set StateID = 1, ".
                       "UserID = $vUserID, ".
                       "UnknownUserGUID = $vUnknownUserGUID, ".
                       "CreateDate = '".GetLocalDateTimeAsSQLStr()."', ".
                       "UserName = '$name', ".
                       "UserEmail = '$email', ".
                       "MessageSubject = '$subject', ".
                       "MessageText = '$question' ".
                   "where (ID = $RecordID);";
            $this->db->exec($sql);

            $content = array(
                'title' => $subject,
                'template' => 'contactus',
                'data' => array('[year]' => date("Y",time()), 
                                '[name]' => $name, 
                                '[email]' => $email, 
                                '[question]' => $question,
                                '[ticketlink]' => URL."admincp/index.php?mod=ContactUsItem&id=".$RecordID
                               )
            );

            //$vSendResult = Mailer::send('info@karapuz.life', $content, $subject, null, $email);
            $vSendResult = SendEmailSMTP($email, $name, 'contactus@karapuz.life', $content, null, SMTP_CC);

            if ($vSendResult !== true) {
                return AddAlertMessage('danger', 'Ошибка при отправке письма!', '/');
            } else {
                return AddAlertMessage('success', 'Ваше сообщение было отправлено!', '/');
            }
        }

        $this->view->breadcrumbs = array(array('url' => '/auth/contactus', 'title' => 'Свяжитесь с нами'));
        
        $this->view->meta = array(
            'meta_title' => 'Свяжитесь с нами',
            'meta_description' => 'Свяжитесь с нами',
            'meta_keywords' => ''
        );
        
        $this->view->generate();
    }
}