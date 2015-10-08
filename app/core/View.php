<?php
class View {
	protected $layout;
	protected $view;
	public $path;
	public $meta;
	public $breadcrumbs;
	public $data;
	
	
	public function __construct($context){
		$this->path = $context->path;
	}
	
	/*
	* Set array variables to View data
	* @data - variables (array)
	*/
	public function setVars($array){
		if (!is_array($array))
			return false;
		
		foreach ($array as $k=>$v)
			$this->data[$k] = $v;
		return true;
	}
	
	/*
	* Set one variable to View data
	* @key - (string)
	* @value - (string)
	*/
	public function setVar($key, $value){
		$this->data[$key] = $value;
		return true;
	}
	
	/*
	* Get variable form View data
	* @name - variable name (string)
	*/
	public function getVar($name){
		return (!isset($this->data[$k])) ? null : $this->data[$k];
	}
	
	
	/*
	* Set layout(tempalte) of page
	* @name - layout name (string)
	*/
	public function setLayout($name){
		$this->layout = DIR_LAYOUTS.$name.'.php';
	}
	
	/*
	* Set layout(tempalte) of page
	* @name - layout name (string)
	*/
	public function setView($name){
		$this->view = $name.'.php';
	}
	
	/*
	* Generate view
	* @data - data for view (array)
	*/
	public function generate(){
		if (is_array($this->data))
			extract($this->data);
			
		$view = DIR_VIEWS.(($this->view != null) ? $this->view : Tools::getViewPath(debug_backtrace()));
		require_once $this->layout;
	}


}
