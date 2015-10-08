<?php
class SearchController extends Controller {
	
	function indexAction(){	
		$p = Tools::getValue('page', 1);
		$q = Tools::getValue('q', '');
		$AuthorID = Tools::getValue('author', null);
		
		$articles = new Articles($this->context);
		if ($AuthorID != null){
    		$sql = "SELECT ID, Name FROM Authors WHERE ID = $AuthorID;";
        	$author = GetMainConnection()->query($sql)->fetch();
            
            if (empty($author['ID'])) { 
                return AddAlertMessage('danger', 'Такого автора не существует.', '/'); 
            }
            $AuthorName = $author['Name'];
            
			$total = ceil($articles->getArticles($p, 'AuthorID = '.$AuthorID, true)/ARTICLES_PER_PAGE);
			$articles = ($total > 0) ? $articles->getArticles($p, 'AuthorID = '.$AuthorID) : null;
		} else {
            $AuthorName = '';
            $AddWhere = (empty($q) ? '' : '(Name LIKE "%'.$q.'%" OR Description LIKE "%'.$q.'%")');
            $total = ceil($articles->getArticles($p, $AddWhere, true)/ARTICLES_PER_PAGE);
            $articles = ($total > 0) ? $articles->getArticles($p, $AddWhere) : null;
		}
		
		$this->view->setVars(array(
			'q' => $q,
			'AuthorName' => $AuthorName,
			'articles' => $articles,
			'pagination' => array(
                'total_pages' => $total,
                'current' => $p
			)
		));
                
		$this->view->breadcrumbs = array(array('url' => '/search', 'title' => 'Поиск'));
		$this->view->generate();
	}
}