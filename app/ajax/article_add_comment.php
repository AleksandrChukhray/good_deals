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
        if (empty($id)) { $ErrorText = 'Неизвестная статья.'; }

        $Comment = strip_tags(POSTStrAsSQLStr('CommentEdt'));
        if (empty($Comment)) { $ErrorText = 'Поле комментарий должно быть заполнено.'; }
        
        if (empty($ErrorText)) {
            if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['firstname'])) {
                $UserName = strip_tags(POSTStrAsSQLStr('UserNameEdt'));
            } else {
                $UserName = $_SESSION['auth']['firstname'];
            }

            if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['email'])) {
                $OwnerEmail = "";
            } else {
                $OwnerEmail = $_SESSION['auth']['email'];
            }
            
            $IsNotifyRecipientActive = POSTBoolAsSQLStr('IsNotifyRecipientActiveEdt');
            
            unset($_POST['ajax_AddCommentBtn']);
            unset($_POST['UserNameEdt']);
            unset($_POST['IsRecipientEdt']);
            unset($_POST['CommentEdt']);

            if (isset($_SESSION['auth'])) {
                $vUserID = $_SESSION['auth']['id'];
                $vUnknownUserGUID = "";
            } else {
                $vUserID = "0";
                $vUnknownUserGUID = (string)GetUnknownUserGUID();
            }

            $sql = "select ID ".
                   "from ArticleComments ".
                   "where (ArticleID = $id) ".
                   "and (UnknownUserGUID = '$vUnknownUserGUID') ".
                   "and (UserID = $vUserID) ".
                   "and (Comment = '$Comment');";
            $rec = GetMainConnection()->query($sql)->fetch();

            if (empty($rec['ID'])) {
                $sql = "insert into ArticleComments(ArticleID, UnknownUserGUID, UserID, UserName, CommentDate, Comment) ".
                       "values($id, '$vUnknownUserGUID', $vUserID, '$UserName', '".GetLocalDateTimeAsSQLStr()."', '$Comment');";
                GetMainConnection()->exec($sql);   
                
            /*$sql = "insert into CatalogRatings(CatalogItemID, UserID, UnknownUserGUID, Rating1, Rating2, Rating3) ".
                   "select * from (select $id as C1, $vUserIDForIns as C2, $vUnknownUserGUIDForIns as C3, $Rating1 as C4, $Rating2 as C5, $Rating3 as C6) AS tmp ".
                   "where not exists ( ".
                      "select ID ".
                      "from CatalogRatings ".
                      "where (CatalogItemID = $id) ".
                      "and (UserID $vUserID) ".
                      "and (UnknownUserGUID $vUnknownUserGUID) ".
                   ") limit 1;";*/
                
                
                if (POSTBoolAsSQLStr('PriorNotifyStateEdt') != $IsNotifyRecipientActive) {
                    if ($IsNotifyRecipientActive == '1') {
                        Emails_AddNotifyRecipient(1, $id, $OwnerEmail);
                    } else {
                        Emails_DeleteNotifyRecipient(1, $id, $OwnerEmail);
                    }
                }
                
                
                $sql = "select CategoryID, Name from Articles where (ID = $id);";
                $rec = GetMainConnection()->query($sql)->fetch();
                
                $vBodyParam = array('[year]' => date("Y",time()), 
                                    '[name]' => $UserName, 
                                    '[msgbody]' => $Comment,
                                    '[doclink]' => URL.'articles/c-'.$rec['CategoryID'].'/a-'.$id,
                                    '[unsubscribe]' => URL.'email/unsubscribe/'.Encrypt_Blowfish('1||'.$id, EMAIL_BLOWFISHGUID)
                                   );                        
                $vBody = Emails_PrepareBodyUseTemplate('article_comment', $vBodyParam);
                Emails_AddNotify(1, $id, 'Новый комментарий к статье: "'.$rec['Name'].'"', $vBody, $OwnerEmail);
                
                /*$content = array(
                    'title' => 'Re: '.$rec['MessageSubject'],
                    'template' => 'answercontactus',
                    'data' => array('[year]' => date("Y",time()), 
                                    '[name]' => $rec['UserName'], 
                                    '[msgbody]' => $vAnswerText
                                   )
                );
                $vSendResult = SendEmailSMTP('contactus@karapuz.life', $name, 'contactus@karapuz.life', $content, null, SMTP_CC);*/
                
                /*AddTaskForSendEmail_UseBody('contactus@karapuz.life', 
                                    'name 1',
                                    'karapuzlife@gmail.com', 
                                    'name 2',
                                    'Test subject',
                                    'Test body'
                                   );*/
                /*$vParams = array('[year]' => date("Y",time()), 
                                 '[name]' => 'NMV USER NAME', 
                                 '[msgbody]' => 'nmv Answer nmv'
                                );
                AddTaskForSendEmail_UseTemplate('contactus@karapuz.life', 
                                    'name 1',
                                    'karapuzlife@gmail.com', 
                                    'name 2',
                                    'Test new subject',
                                    'answercontactus',
                                    $vParams,
                                    110, 
                                    'MiranGroupInfo@gmail.com'
                                   );*/
            } else {
                $ErrorText = 'Такой комментарий уже существует.';
            }
        }
        
        // Формат ответа: 1 позиция текст ошибки, 2 позиция кол-во комментов, 3-html для перезаполнения таблицы комментов
        if (empty($ErrorText)) {
            $sql = "select CommentDate, UserID, UserName, Comment ".
                   "from ArticleComments ".
                   "where (ArticleID = $id) ".
                   "and (IsDeleted = 0) ".
                   "order by CommentDate desc;";
            $ArticleComments = GetMainConnection()->query($sql)->fetchAll();
            
            echo '||'.count($ArticleComments).'||'.GetArticleCommentsHTML($ArticleComments, POSTStrAsSQLStr('AuthorIDEdt'));
        } else {
            echo $ErrorText.'||||';
        }
    }
?>