<?php
class RulerController extends Controller {
 
	public function indexAction(){
		$images = array();
		$dir = DIR_DBIMAGES.'ruler/templates/';
		$files = scandir($dir);
		
		foreach($files as $file)
			if ($file != '.' && $file != '..')
				$images[(preg_match('/r/', $file) ? 'ruler' : 'slider')][] = $file;
		
		$this->view->setVars(array(
			'images' => $images,
		));
		$this->view->breadcrumbs = array(array('url' => '/ruler', 'title' => 'Линейки для форума'));
		$this->view->generate();
	}
	
	public function addAction(){
		if (!Tools::isPost()){
			header('Location: /ruler', true, 302);
			exit();
		}
		
        $ruler = new Ruler($this->context);
		$id = $ruler->add(Tools::getValue('background'), Tools::getValue('slider'), Tools::getValue('length'), Tools::getValue('counter'), Tools::getValue('text'), Tools::getValue('color'), Tools::getValue('date_start'));
		$ruler->updateImage($id);

		$this->view->breadcrumbs = array(array('url' => '/ruler', 'title' => 'Линейки для форума'));
		$this->view->setVar('id', $id);
		$this->view->generate();
	}
	
	public function updateAction(){
		$ruler = new Ruler($this->context);
		$rulers = $ruler->fetchAll();

		if (count($rulers) > 0)
			foreach ($rulers as $r) {
				echo 'Обновление линейки ID = '.$r['ID'].'<br>';
				$ruler->updateImage($r['ID']);
			}
		
		echo '<p>Обновление линеек для форума завеншено</p>';
	}
	
}