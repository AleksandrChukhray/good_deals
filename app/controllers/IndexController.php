<?php
class IndexController extends Controller {
	function indexAction(){
        $sql = "select ID, CategoryID, Name, ShortDescription, count_likes, CountComments, CreateDate, 0 as SortingOrder, MainImageExt ".
               "from Articles ".
               "where mainPageDate >= CURDATE() and isActive = 1 and IsDeleted=0 ".
               "union ".
               "select ID, CategoryID, Name, ShortDescription, count_likes, CountComments, CreateDate, 1 as SortingOrder, MainImageExt ".
               "from Articles ".
               "where mainPageDate < CURDATE() and isActive = 1 and IsDeleted=0 ".
               "order by SortingOrder, CreateDate DESC, ID DESC ".
               "limit 0,".ARTICLES_COUNT_LAST.";";
		$lastArticles = $this->db->query($sql)->fetchAll();
        
        $NewsSlider = $this->db->query('select Photo, URL from NewsSlider where (coalesce(Photo, "") <> "") order by Position limit 7')->fetchAll();
		 
        $this->view->setVars(array(
			'lastArticles' => $lastArticles,
			'NewsSlider' => $NewsSlider
		));

        //$this->view->breadcrumbs = array(array('url' => '/', 'title' => 'Главная'));

        $this->view->meta = array(
            'meta_title' => 'Карапуз - сообщество родителей Украины',
            'meta_description' => 'Информация для семьи, детей и родителей',
            'meta_keywords' => 'статьи, акции, благотворительность, волонтеры, организации, коммисионка, куплю/продам, мастер-класы, садики, школы, афиша, города, эко-акции, зеленая Украина, помощь, интервью, даунята, дцп',
        );
                
		$this->view->generate();
    }
}