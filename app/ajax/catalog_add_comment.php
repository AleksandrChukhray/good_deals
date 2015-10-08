<?php
    header("Content-Type:text/html;charset=UTF-8");
    session_start();
    
    define('PATH_SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');

    require_once "../../config.php";
    require_once '../../connection.php';
    require_once '../../core/global.php';

    if (isset($_POST)) {
        $ErrorText = '';
        $id = POSTStrAsSQLStr('IDEdt');   
        if (empty($id)) { $ErrorText = 'Неизвестная организация.'; }

        $Comment = strip_tags(POSTStrAsSQLStr('CommentEdt'));
        if (empty($Comment)) { $ErrorText = 'Поле отзыва должно быть заполнено.'; }
        
        if (empty($ErrorText)) {
            if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['firstname'])) {
                $UserName = strip_tags(POSTStrAsSQLStr('UserNameEdt'));
            } else {
                $UserName = $_SESSION['auth']['firstname'];
            }

            unset($_POST['ajax_AddCommentBtn']);
            unset($_POST['UserNameEdt']);
            unset($_POST['CommentEdt']);

            if (isset($_SESSION['auth'])) {
                $vUserID = $_SESSION['auth']['id'];
                $vUnknownUserGUID = "";
            } else {
                $vUserID = "0";
                $vUnknownUserGUID = (string)GetUnknownUserGUID();
            }

            $sql = "select ID ".
                   "from CatalogComments ".
                   "where (CatalogItemID = $id) ".
                   "and (UnknownUserGUID = '$vUnknownUserGUID') ".
                   "and (UserID = $vUserID) ".
                   "and (Text = '$Comment');";
            $rec = GetMainConnection()->query($sql)->fetch();

            if (empty($rec['ID'])) {
                $sql = "insert into CatalogComments(CatalogItemID, UserID, UnknownUserGUID, CreateDate, UserName, Text) ".
                       "values($id, $vUserID, '$vUnknownUserGUID', '".GetLocalDateTimeAsSQLStr()."', '$UserName', '$Comment');";
                GetMainConnection()->exec($sql);   
            } else {
                $ErrorText = 'Такой отзыв уже существует.';
            }
        }
        
        // Формат ответа: 1 позиция текст ошибки, 2 позиция кол-во комментов, 3-html для перезаполнения таблицы комментов
        if (empty($ErrorText)) {
            $sql = "select CreateDate, UserID, UserName, Text ".
                   "from CatalogComments ".
                   "where (CatalogItemID = $id) ".
                   "and (IsDeleted = 0) ".
                   "order by CreateDate desc;";
            $Comments = GetMainConnection()->query($sql)->fetchAll();
            
            echo '||'.count($Comments).'||'.  GetCatalogCommentsHTML($Comments);
        } else {
            echo $ErrorText.'||||';
        }
    }
?>