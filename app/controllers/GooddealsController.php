<?php
class GooddealsController extends Controller {
    public function indexAction() {
        /*$cat_pod = $this->db->query('select ID, id_cat, name, CountItems from Catalog_pod where IsDeleted=0 order by id_cat')->fetchAll();

        $this->view->setVars(array(
            'cat_pod' => $cat_pod
        ));*/
        
        $this->view->breadcrumbs = array(
            array('url' => '/gooddeals/', 'title' => 'Хорошие дела')
        );
        
        $this->view->meta = array(
            'meta_title' => 'Хорошие дела',
            'meta_description' => 'Хорошие дела',
            'meta_keywords' => 'Хорошие дела'
        );

        $this->view->generate();
    }
}