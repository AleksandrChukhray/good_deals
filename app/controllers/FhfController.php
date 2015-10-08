<?php
class FhfController extends Controller {
	
	function indexAction(){
		$this->view->breadcrumbs = array(array('url' => '/404', 'title' => 'Страница не найдена'));
		$this->view->generate();
	}
}
