<?php

class MarketController extends Controller {
  
    public function indexAction($id = null) {
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
            array('url' => '../market', 'title' => 'Маркет'),
        );
$vMarket = new Market($this->context);
        $this->view->setVar('block_last_GS', $vMarket->getLastTovarHTMLGS());
        $this->view->generate();
    }
        
    public function comissionkaAction($level=0) {
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());
        $this->view->setVar('block_last_tovar', $vMarket->getLastTovarHTML($level));
        $this->view->setVar('block_count_tovar', $vMarket->GetCountTovar());
         $this->view->setVar('block_cat_option', $vMarket->getCategoriesOptHTML());   
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/comissionka', 'title' => 'Комиссионка')			
		);
		
		$this->view->generate();
	}
    
    //переход и выборка товаров из дарю/меняю и комиссионки    
    public function Union_ProductAction($id) {
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());
        $this->view->setVar('block_gift_tovar', $vMarket->getUnionTovarHTML($id));
        //$this->view->setVar('block_count_tovar', $vMarket->GetCountTovar());
         $this->view->setVar('block_cat_option', $vMarket->getCategoriesOptHTML());   
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/comissionka', 'title' =>'Дарю/Меняю + Комиссионка')			
		);
		
		$this->view->generate();
    }
        
       
    //переход и выборка последних товаров из дарю/меняю   
    public function Gift_ProductAction() {
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesChangeTreeHTML());
        $this->view->setVar('block_gift_change_tovar', $vMarket->getGiftTovarHTML(null,0));
        //$this->view->setVar('block_count_tovar', $vMarket->GetCountTovar());
         $this->view->setVar('block_cat_option', $vMarket->getCategoriesOptHTML());   
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/gift', 'title' =>'Дарю/Меняю')			
		);
		
		$this->view->generate();
    }
    
    //переход и выборка последних товаров из дарю/меняю   
    public function Gift_ProductAllAction($id) {
        if (empty($id))
			return AddAlertMessage('danger', 'Категории не существует.', '/');
        
        $cat = new Market($this->context);
        $cat_name = $cat->getCat($id[0]);
       
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesChangeTreeHTML());
        $this->view->setVar('block_gift_change_tovar', $vMarket->getGiftTovarHTML($id[0],$id[1]));
        //$this->view->setVar('block_count_tovar', $vMarket->GetCountTovar());
         $this->view->setVar('block_cat_option', $vMarket->getCategoriesOptHTML());   
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
                        array('url' => '../market/gift_product', 'title' => 'Дарю/Меняю'),
			array('url' => '../market/gift_product/cat-'.$id[0], 'title' =>$cat_name['name_cat'])			
		);
		
		$this->view->generate();
    }
    
    //****************************************************************************************************************//
    
    // показ товаров при переходе из категорий комиссионки
    public function categoryAction($id){
		if (empty($id))
			return AddAlertMessage('danger', 'Категории не существует.', '/');
      
        $cat = new Market($this->context);
        $cat_name = $cat->getCat($id[0]);

        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/comissionka', 'title' => 'Комиссионка'),
			array('url' => '../market/comissionka/cat-'.$id[0],'title' => $cat_name['name_cat'])
                        
		);
		
                
        $vMarket = new Market($this->context);
       
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());
        $this->view->setVar('block_tovar_of_cat', $vMarket->getCatTovarHTML($id[0], $id[1]));
        $this->view->setVar('block_count_tovar_cat', $vMarket->GetCountTovarCat($id[0]));
        $_SESSION['fil_id']= $id[0];
         
		$this->view->generate();        
    }
    
    public function cardtovarAction($id) {
        if (empty($id))
			return AddAlertMessage('danger', 'Товара не существует.', '/');
                
        $cat = new Market($this->context);               
        $cat_item = $cat->getFullTovar($id);
        $sid = $cat_item[0]['id_podcat'];
        $cat_name = $cat->getCat($sid);	

        $tovar = new Komments($this->context);
        $this->view->setVar('text', $tovar->addKomm()); //проба 
        
        
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../comissionka/', 'title' => 'Комиссионка'),
            array('url' => '../comissionka/cat-'.$cat_name['id_p'],'title' => $cat_name['name_cat']),
			array('url' => '../comissionka/cat-'.$cat_name['id_p'].'/cardtovar-'.$cat_item[0]['id'], 'title' => $cat_item[0]['name_tovar'])
		);
		
        $this->view->setVar('block_cat_comission', $cat->getCategoriesCommTreeHTML());
        $this->view->setVar('block_full_tovar', $cat->getFullTovarHTML($id));
		$this->view->generate();
	}
     public function cardtovargiftAction($id) {
        if (empty($id))
			return AddAlertMessage('danger', 'Товара не существует.', '/');
                
        $cat = new Market($this->context);               
        $cat_item = $cat->getFullTovarGift($id);
        $sid = $cat_item[0]['id_podcat'];
        $cat_name = $cat->getCat($sid);	

        $tovar = new Komments($this->context);
        $this->view->setVar('text', $tovar->addKomm()); //проба 
        
        
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../gift_product/', 'title' => 'Дарю/Меняю'),
            array('url' => '../gift_product/cat-'.$cat_name['id_p'],'title' => $cat_name['name_cat']),
			array('url' => '../gift_product/cat-'.$cat_name['id_p'].'/cardtovargift-'.$cat_item[0]['id'], 'title' => $cat_item[0]['name_tovar'])
		);
		
        $this->view->setVar('block_cat_comission', $cat->getCategoriesChangeTreeHTML());
        $this->view->setVar('block_full_tovar', $cat->getFullTovarGiftHTML($id));
		$this->view->generate();
	}   
    public function addTovarAction(){
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '', 'title' => 'Новое объявление')
		);
             
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_option', $vMarket->getCategoriesOptHTML());             
		
		$this->view->generate();
    }
          public function addTovarGiftAction(){
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '', 'title' => 'Новое объявление')
		);
             
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_option', $vMarket->getCategoriesOptHTML());             
		
		$this->view->generate();
    }  
    public function new_tovarAction(){
        $tovar = new Tovar($this->context);
        
        $this->view->setVar('proba', $tovar->SaveNewTovar());   
       
           
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());
 
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/comissionka', 'title' => 'Комиссионка')
		);                  
		
        $this->view->generate();
    }
     public function new_tovar_giftAction(){
        $tovar = new Tovar($this->context);
        
        $this->view->setVar('proba', $tovar->SaveNewTovar());   
       
           
        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());
 
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/comissionka', 'title' => 'Дарю/Меняю')
		);                  
		
        $this->view->generate();
    }
    public function new_commentAction(){
        $tovar = new Komments($this->context);
        $this->view->setVar('text', $tovar->addKomm());   

        $vMarket = new Market($this->context);
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());

        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
			array('url' => '/market/', 'title' => 'Маркет'),
			array('url' => '../market/comissionka', 'title' => 'Комиссионка'),
			array('url' => '../market/comissionka', 'title' => 'Добавление коментария')
		);                  
		
        $this->view->generate();
    }
        
    public function filtrAction($id) {        
        $vMarket = new Market($this->context);
        $this->view->setVar('block_filtr_tovar', $vMarket->getFiltr());
        $this->view->setVar('block_cat_comission', $vMarket->getCategoriesCommTreeHTML());
        $this->view->setVar('block_id', $id);
        
        $this->view->breadcrumbs = array(
            array('url' => '/', 'title' => 'Главная'),
            array('url' => '/market/', 'title' => 'Маркет'),
            array('url' => '../market/comissionka', 'title' => 'Комиссионка')
        );

        $this->view->generate();
    }
}