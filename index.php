<?php 
try {
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
    session_start();
    define('PATH_SITE_ROOT', __DIR__.'/');

    require_once 'config.php';
    require_once 'connection.php';
    require_once 'core/global.php';

    InitDebugLog();
    
    GetUnknownUserGUID(); // Создание GUID для каждого пользователя и сохранение его в cookies (для идентификации незалогинившихся пользователей)
    $context = new stdClass();

    $path = explode('/', GetURLPath());
    if (!empty($path[1]) && (strtolower($path[1]) == 'index.php')) {
        array_splice($path, 1, 1);
    }

    //AddDebugLog('Start block 1');
    // Основная база данных проекта
    $context->db = GetMainConnection();
    //AddDebugLog('Start block 2');

    // Register files
    require_once DIR_CORE.'Model.php';
    require_once DIR_CORE.'View.php';
    require_once DIR_CORE.'Controller.php';
    require_once DIR_CORE.'Tools.php';

    
    //AddDebugLog('Start block 3');
    
    /*if (DIR_MODELS != null && is_dir(DIR_MODELS)) {
        $dir = opendir(DIR_MODELS);

        while (false !== ($file = readdir($dir))) {
            if ($file !== '.' && $file != '..' && $file != '.svn') {
                require_once DIR_MODELS.$file;
            }
        }
        closedir($dir);
    }*/

    //AddDebugLog('Start block 4');
    
    $IsDebug = (strpos($_SERVER['REQUEST_URI'], 'XDEBUG') !== false);
    if ($IsDebug) {
        // in debug mode
        $model = null;
        $controller = 'IndexController';
        $action = 'indexAction';
        $value = null;
        $context->path['controller'] = 'index';
        $context->path['action'] = 'index';
        $context->path['value'] = null;
    } else {
        $model = ucfirst(strtolower($path[1]));
        $controller = (!empty($path[1])) ? $model.'Controller' : 'IndexController';
        $action = (!empty($path[2]) && !preg_match('/[\?\=]+/', $path[2])) ? $path[2].'Action' : 'indexAction';
        $value = (!empty($path[3]) && !preg_match('/[\?\=]+/', $path[3])) ? $path[3] : null;
        $context->path['controller'] = (!empty($path[1])) ? $model : 'index';
        $context->path['action'] = (!empty($path[2]) && !preg_match('/[\?\=]+/', $path[2])) ? $path[2] : 'index';
        $context->path['value'] = (!empty($path[3]) && !preg_match('/[\?\=]+/', $path[3])) ? $path[3] : null;
       
    }        

    $vCanTryToLogin = true; 
    
    // exclusion routing
    switch ($model) {
        case '404': 
            $controller = 'FhfController'; 
            break;

        case 'Articles':
            require_once DIR_MODELS.'Articles.php';
            
            $controller = 'ArticleController';
            $catId=(isset($path[2]) && stripos($path[2],"c-")!==false)?str_replace("c-","",$path[2]):"";
            $artId=(isset($path[3]) && stripos($path[3],"a-")!==false)?str_replace("a-","",$path[3]):"";
            
            if (empty($artId) && !empty($catId)) {
                $controller = 'CategoryController';
                $action = 'indexAction';
                $value = $catId;
            }
            
            if (!empty($artId) && !empty($catId)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'indexAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : $artId;
            }
            
            break;

        case 'Market':
            require_once DIR_MODELS.'Komments.php';
            require_once DIR_MODELS.'Market.php';
            require_once DIR_MODELS.'Tovar.php';
            
            $controller = 'MarketController';
            $catId=(isset($path[2]) && stripos($path[2],"cat-")!==false)?str_replace("cat-","",$path[2]):"";
            $change_catId = (isset($path[2]) && stripos($path[2],"change-")!==false)?str_replace("change-","",$path[2]):"";
            $artId=(isset($path[3]) && stripos($path[3],"cardtovar-")!==false)?str_replace("cardtovar-","",$path[3]):"";
            $artgiftId=(isset($path[3]) && stripos($path[3],"cardtovargift-")!==false)?str_replace("cardtovargift-","",$path[3]):"";
            $pageId=(isset($path[3]) && stripos($path[3],"page-")!==false)?str_replace("page-","",$path[3]):"";
            $union_cat_Id=(isset($path[3]) && stripos($path[3],"union-")!==false)?str_replace("union-","",$path[3]):"";
        
            if (!empty($catId)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'categoryAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : $catId;
            }

            if (!empty($artId)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'cardtovarAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : $artId;
            }
            
           if (!empty($pageId)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'categoryAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : array($catId, $pageId);
            }
            
            if (!empty($union_cat_Id)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'Union_ProductAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : $union_cat_Id;
               
            }
            if (!empty($change_catId)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'Gift_ProductAllAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : array($change_catId, $pageId);
               
            }
            if (!empty($artgiftId)) {
                $action = (in_array($context->path['action'], array('like'))) ? $action : 'cardtovargiftAction';
                $value = (in_array($context->path['action'], array('like'))) ? $value : $artgiftId;
               
            }
            
            break;

        case 'Catalog':
            $controller = 'CatalogController';
            $pod_id=(isset($path[2]) && stripos($path[2],"p-")!==false)?str_replace("p-","",$path[2]):"";
            $itm_id=(isset($path[2]) && stripos($path[2],"i-")!==false)?str_replace("i-","",$path[2]):"";

            if (!empty($pod_id)) {
                $action = 'itemsAction';
                $value = $pod_id;
            } else if (!empty($itm_id)) {
                //$action = (in_array($context->path['action'], array('like'))) ? $action : 'itemAction';
                //$value = (in_array($context->path['action'], array('like'))) ? $value : $itm_id;
                $action = 'itemAction';
                $value = $itm_id;
            }            
            
            break;
            
		case 'Consult':
            require_once DIR_MODELS.'Consult.php';
            
    		$controller = 'ConsultController';
            $catId=(isset($path[2]) && stripos($path[2],"cn-")!==false)?str_replace("cn-","",$path[2]):"";
			$artId=(isset($path[3]) && stripos($path[3],"ex-")!==false)?str_replace("ex-","",$path[3]):"";
			$qId=(isset($path[2]) && stripos($path[2],"q-")!==false)?str_replace("q-","",$path[2]):"";

			if(!empty($catId)){
				$action = (in_array($context->path['action'], array('like'))) ? $action : 'CategoryAction';
				$value = (in_array($context->path['action'], array('like'))) ? $value : $catId;
            }
                        
            if(!empty($artId)){
				$action = (in_array($context->path['action'], array('like'))) ? $action : 'ExpertAction';
				$value = (in_array($context->path['action'], array('like'))) ? $value : $artId;
            }
            
            if(!empty($qId)){
				$action = (in_array($context->path['action'], array('like'))) ? $action : 'QpageAction';
				$value = (in_array($context->path['action'], array('like'))) ? $value : $qId;
			}
			
			break;

        case 'Gooddeals':
            $controller = 'GooddealsController';
            /*$pod_id=(isset($path[2]) && stripos($path[2],"p-")!==false)?str_replace("p-","",$path[2]):"";
            $itm_id=(isset($path[2]) && stripos($path[2],"i-")!==false)?str_replace("i-","",$path[2]):"";

            if (!empty($pod_id)) {
                $action = 'itemsAction';
                $value = $pod_id;
            } else if (!empty($itm_id)) {
                //$action = (in_array($context->path['action'], array('like'))) ? $action : 'itemAction';
                //$value = (in_array($context->path['action'], array('like'))) ? $value : $itm_id;
                $action = 'itemAction';
                $value = $itm_id;
            }*/           
            
            break;

        case 'Auth': 
            $controller = 'AuthController';
            $vCanTryToLogin = false; 
            break;

        case 'Category':
            require_once DIR_MODELS.'Articles.php';

            $controller = 'CategoryController';
            break;
        
        case 'Search':
            require_once DIR_MODELS.'Articles.php';
            
            $controller = 'SearchController';
            break;
        
        case 'Ruler': 
            require_once DIR_MODELS.'Ruler.php';
            
            $controller = 'RulerController';
            break;

        case 'Admincp': 
            Redirect("./index.php");
            break;
    }

    // Логин пользователя у которого была отметка, (Запомнить меня на сайте)
    if ($vCanTryToLogin) { TryToLoginUsingCookie(); }
    
    // Start
    /* Шапка, тело и подвал сайта находятся в ..\app\views\layouts\main.php, в этом же модуле в
     * div контейнер с id=body_center будет вклеен файл объявленный в переменной $view (объявляется при создании $controller)
     * (например для ArticleController будет вклеен ..\app\views\article\index.php).
     * $controller - какой модуль следует загружать (например для статей ..\app\controllers\ArticleController.php)
     * $action - какое действие\функцию следует выполнять контролеру (например indexAction - метод по умолчанию)
    */

    //AddDebugLog('Start block 5');
    $context->path['module'] = $controller;

    require_once DIR_CONTROLLERS.$controller.'.php';
    $controller = new $controller($context);
    if (method_exists($controller, $action)) {
        $controller->$action($value);
    } else {
        Redirect('/404');
    }
    
    ShowDebugLog();
} catch (Exception $e) {
    echo 'Ошибка: ', $e->getMessage();
}