<?php
    header("Content-Type:text/html;charset=UTF-8");
    session_start();
    
    define('PATH_SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
    
    require_once "../../config.php";
    require_once '../../connection.php';
    require_once '../../core/global.php';

    $redirect_uri = 'http://karapuz.life/app/common/vk_login_callback.php'; // Адрес сайта
    $url = 'http://oauth.vk.com/authorize';

    $params = array(
        'client_id'     => vk_app_id,
        'redirect_uri'  => $redirect_uri,
        'response_type' => 'code'
    );

    if (isset($_GET['code'])) {
        //$result = false;
        $params = array(
            'client_id' => vk_app_id,
            'client_secret' => vk_app_secret,
            'code' => $_GET['code'],
            'redirect_uri' => $redirect_uri
        );

        //$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        $requesturl = 'https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params));
        $ctx = stream_context_create(['http'=>['timeout'=>3]]); // таймаут в секундах
        $maxAttempts = 5;  // макс кол-во попыток
        $attempt = 0; // тут будет храниться кол-во совершенных попыток 
        while(!($content=@file_get_contents($requesturl, false, $ctx)) && ++$attempt<$maxAttempts); // тут магия
        //echo $content; // полученный контент        
        $token = json_decode($content, true);
        
        //var_dump($token);
        
        if (isset($token['access_token'])) {
            if (empty($token['email'])) {
                return AddAlertMessage('danger', 'У пользователя ВКонтакте не задан E-mail. Регистрация отменена.', '/auth/login');
            }   
            
            $vUniversalID = 'vk_'.$token['user_id'];
            $accessTokenStr = $token['access_token'];
            $userEmail = $token['email'];
            
            // Поиск пользователя в бд, и если не существует, то создание нового
            $sql = "select ID ".
                   "from Users ".
                   "where (UniversalID = '$vUniversalID');";
            $rec = GetMainConnection()->query($sql)->fetch();

            if (empty($rec['ID'])) {
                $sql = "select ID from Users where (Email = '$userEmail');";
                $checkuser = GetMainConnection()->query($sql)->fetch();

                if (!empty($checkuser['ID'])) {
                    return AddAlertMessage('danger', 'Пользователь с эл. почтой: "'.$userEmail.'" уже зарегистрирован на сайте.', '/');
                }
                
                $params = array(
                    'uids'         => $token['user_id'],
                    //'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                    'fields'       => 'uid,first_name,last_name',
                    'access_token' => $token['access_token']
                );

                //$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

                $requesturl = 'https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params));
                $ctx = stream_context_create(['http'=>['timeout'=>3]]); // таймаут в секундах
                $maxAttempts = 5;  // макс кол-во попыток
                $attempt = 0; // тут будет храниться кол-во совершенных попыток 
                while(!($content=@file_get_contents($requesturl, false, $ctx)) && ++$attempt<$maxAttempts); // тут магия
                //echo $content; // полученный контент        
                $userInfo = json_decode($content, true);


                if (isset($userInfo['response'][0]['uid'])) {
                    $userInfo = $userInfo['response'][0];
                    //$result = true;

                    $sql = "insert into Users(UniversalType, UniversalID, AccessToken, UserName, Email, EmailConfirmed, RememberMe) ".
                           "values(3, '$vUniversalID', '$accessTokenStr', '$userEmail', '$userEmail', 1, 1) ".
                           "on duplicate key update ".
                           "UniversalID = '$vUniversalID';";
                    GetMainConnection()->exec($sql);      

                    $UserID = GetMainConnection()->lastInsertId();

                    $sql = "insert into UserData(UserID, FirstName, LastName) ".
                           "values($UserID, '".$userInfo['first_name']."', '".$userInfo['last_name']."') ".
                           "on duplicate key update ".
                           "UserID = $UserID;";
                    GetMainConnection()->exec($sql);
                } else {
                    return AddAlertMessage('danger', 'Ошибка получения данных, необходимых для регистации пользователя.', '/auth/login');
                }
            }
            
            // Если дошли без ошибок, то делаем попытку входа с использованием данных из нашей бд.
            if (LoginUsingUniversalID($vUniversalID)) {
                if (empty($_SESSION['login_redirect'])) {
                    return AddAlertMessage('success', 'Добро пожаловать!', '/');
                } else {
                    $vRedirect = $_SESSION['login_redirect'];
                    unset($_SESSION['login_redirect']);
                    Redirect($vRedirect);
                }            
            } else {
                return AddAlertMessage('danger', 'Ошибка при регистрации!', '/');
            }
        } else {
            return AddAlertMessage('danger', 'Ошибка при регистации.', '/auth/login');
        }

        /*var_dump($userInfo);
        if ($result) {
            echo "Социальный ID пользователя: " . $userInfo['uid'] . '<br />';
            echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
            echo "Ссылка на профиль пользователя: " . $userInfo['screen_name'] . '<br />';
            echo "Пол пользователя: " . $userInfo['sex'] . '<br />';
            echo "День Рождения: " . $userInfo['bdate'] . '<br />';
            echo "День Рождения: " . $userInfo['email'] . '<br />';
            echo '<img src="' . $userInfo['photo_big'] . '" />'; echo "<br />";
        }*/
    }
?>
