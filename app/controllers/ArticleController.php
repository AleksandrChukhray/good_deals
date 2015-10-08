<?php

class ArticleController extends Controller {
    public function indexAction($id = null) {
        if (empty($id)) {
            return AddAlertMessage('danger', 'Статьи не существует.', '/');
        }

        $vUserID = GetUserID();        
        $UnknownUserGUID = GetUnknownUserGUID();
        $IsNotifyRecipientActive = false;
        if ($vUserID != 0) {
            $vAddWhere = "((UserID = $vUserID) or (UnknownUserGUID = '$UnknownUserGUID'))";
            $UnknownUserGUIDForViewed = "";
            $IsNotifyRecipientActive = Emails_IsNotifyRecipientActive(1, $id, $_SESSION['auth']['email']);
        } else {
            $vAddWhere = "(UnknownUserGUID = '$UnknownUserGUID')";
            $UnknownUserGUIDForViewed = $UnknownUserGUID;
        }
        
        // Регистрация просмотра статьи пользователем
        $sql = "insert into ArticleViewed(ArticleID, UnknownUserGUID, UserID, LastView) ".
               "values($id, '$UnknownUserGUIDForViewed', $vUserID, '".GetLocalDateTimeAsSQLStr()."') ".
               "on duplicate key update ".
               "LastView = '".GetLocalDateTimeAsSQLStr()."';";
        $this->db->exec($sql);

        
        $article = new Articles($this->context, 'ID = "' . $id . '"');

        if (!isset($article->ID) || $article->ID == null) {
            return AddAlertMessage('danger', 'Статьи не существует.', '/');
        }
        if ($article->IsActive != '1' && !Tools::getValue('preview')) {
            return AddAlertMessage('danger', 'Статья в черновике.', '/');
        }

        $article->PhotoL = URL.DIR_DBIMAGES.'articles/'.$id.'/l_1.'.$article->MainImageExt;
        
        $sql = "select Name from ArticleCategories where ID = ".(int)$article->CategoryID;
        $category = GetMainConnection()->query($sql)->fetch();
        
        $vArticleLike = $this->db->query("select ID from ArticleLikes where (ArticleID = $id) and $vAddWhere limit 1;")->fetch();
        $vAlreadyLiked = (!empty($vArticleLike['ID']));
            
        $sql = "select CommentDate, UserID, UserName, Comment ".
               "from ArticleComments ".
               "where (ArticleID = $id) ".
               "and (IsDeleted = 0) ".
               "order by CommentDate desc;";
        $ArticleComments = $this->db->query($sql)->fetchAll();

        if (!isset($article->AuthorID) || $article->AuthorID == null) {
            $ArticleAuthor['Name'] = '';
            $ArticleAuthor['ShortDescription'] = '';            
            $ArticleAuthor['Photo'] = '';
        } else {
    		$sql = 'SELECT Name, ShortDescription, Photo FROM Authors WHERE ID='.(int)$article->AuthorID;
        	$ArticleAuthor = $this->db->query($sql)->fetch();
        }
        
        $this->view->setVars(array(
            'id' => $id,
            'article' => $article,
            'similar' => $article->getSimilar($id),
            'discused' => $article->getMostDiscussed(),
            'alreadyLiked' => $vAlreadyLiked,
            'ArticleAuthor' => $ArticleAuthor,
            'ArticleDocuments' => $article->getArticleDocuments($id),
            'ArticleComments' => $ArticleComments,
            'ArticleCategory' => $category['Name'],
            'IsNotifyRecipientActive' => $IsNotifyRecipientActive
            /*'ArticleURL' => URL.'articles/c-'.$article->CategoryID.'/a-'.$article->ID*/
        ));

        $this->view->breadcrumbs = array(
            array('url' => '/category', 'title' => 'Все статьи'),
            array('url' => '/articles/c-' . $article->CategoryID, 'title' => $category['Name']),
            array('url' => '/articles/c-' . $article->CategoryID . '/a-' . $article->ID, 'title' => $article->Name),
        );

        $this->view->meta = array(
            'meta_title' => $article->Name,
            'meta_description' => $article->ShortDescription,
            'meta_keywords' => $article->MetaKeywords,
        );

        SetTokenForPreventDoubleSubmit();
        $this->view->generate();
    }

    public function likeAction($id) {
        $article = new Articles($this->context, $id);

        if (!isset($article->ID) || $article->ID == null) {
            $error = 'Статьи не существует.';
        } elseif ($article->IsActive != '1') {
            $error = 'Статья в черновике.';
        } else {
            $userid = GetUserID();
            $UnknownUserGUID = GetUnknownUserGUID();
                    
            $query = "INSERT INTO ArticleLikes (UserID, ArticleID, UnknownUserGUID) ".
                     "VALUES ($userid, $article->ID, '$UnknownUserGUID');";
            $this->db->exec($query);
            
            die(1);
        }
        
        die(json_encode(array('error' => $error)));
    }
}

