<?php
class Controller {
    public $path;
    public $db;
    public $view;
    public $context;

    public function __construct($context){
        $this->context = $context;
        $this->path = $context->path;
        $this->db = $context->db;

        $this->view = new View($context);
        $this->view->setLayout('main');
    }
}