<?php
class CategoryController extends Controller {
	function indexAction($id = null){
		$p = Tools::getValue('page', 1);
        
		if (!empty($id)){
            $sql = "select ID, Name, MetaKeywords, MetaRobots, Description from ArticleCategories where (ID = $id) and (IsDeleted = 0);";
            $category = GetMainConnection()->query($sql)->fetch();
            
			if (empty($category['ID'])) {
                return AddAlertMessage('danger', 'Категории статей не существует.', '/'); 
            }

            $CategoryName = $category['Name'];
            
            $sql =  "SELECT count(*) as RecordCount ".
                    "FROM Articles a ".
                    "WHERE a.CategoryID = $id ".
                    "AND a.isActive = 1 ".
                    "AND a.IsDeleted = 0";
            $rec = GetMainConnection()->query($sql)->fetch();
			$total = ceil($rec['RecordCount']/ARTICLES_PER_PAGE);
            
            $sql =  "SELECT a.ID, a.CategoryID, a.Name, a.ShortDescription, a.count_likes, a.CountComments, MainImageExt ".
                    "FROM Articles a ".
                    "WHERE a.CategoryID = $id ".
                    "AND a.isActive = 1 ". 
                    "AND a.IsDeleted = 0 ".
                    "ORDER BY a.CreateDate DESC, a.ID DESC ".
                    "LIMIT ".(($p > 0) ? $p-1 : 0)*ARTICLES_PER_PAGE.", ".ARTICLES_PER_PAGE;
            $articles = GetMainConnection()->query($sql)->fetchAll();
		} else {
            $category = null;
            $CategoryName = 'Все статьи';
    		$article = new Articles($this->context);
			$total = ceil($article->getArticles($p, null, true)/ARTICLES_PER_PAGE);
			$articles = $article->getArticles($p);
		}

		$this->view->setVars(array(
			'CategoryName' => $CategoryName,
			'articles' => $articles,
			'pagination' => array(
                'total_pages' => $total,
                'current' => $p
			)
		));
                
		$this->view->breadcrumbs = array(array('url' => '/category', 'title' => 'Все статьи'));
		
        if (isset($category)) {
			$this->view->breadcrumbs[] = array('url' => '/articles/c-'.$id, 'title' => $CategoryName);
        
            $this->view->meta = array(
                'meta_title' => $CategoryName,
                'meta_description' => $category['Description'],
                'meta_keywords' => $category['MetaKeywords']
            );
        } else {
            $this->view->meta = array(
                'meta_title' => $CategoryName,
                'meta_description' => $CategoryName,
                'meta_keywords' => $CategoryName
            );
        }
        
		$this->view->generate();
	}
}