<?php
class Model {
    protected $db;

    public function __construct($Acontext, $id = null){
        $context = $Acontext;
        $this->db = $context->db;

        if ($id !== null){
            $query = 'SELECT * FROM '.$this->table.' WHERE ';
            $query .= (preg_match('/\=/', $id)) ? $id : 'ID = '.(int)$id;

            $data = $this->db->query($query)->fetch();

            if ($data !== false) {
                foreach ($data as $k=>$p) {
                    $this->$k = $p;
                }
            }
        }
    }
}