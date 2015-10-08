<?php

/**
 * @author Димон
 */

class Komments extends Model {
    
     public function addKomm(){
         if(!empty(POSTStrAsSQLStr('t_k')) and !empty(POSTStrAsSQLStr('new_komm'))){
         $vName = POSTStrAsSQLStr('t_k');
         $vKomms = POSTStrAsSQLStr('new_komm');
          $vEmail = POSTStrAsSQLStr('e_k');
          $idTov = POSTStrAsSQLStr('id_tov');
		$sql = "insert into komm_tovar_market (id_k, email ,text, id_tov) values ('$vName', '$vEmail', '$vKomms', '$idTov')";
		$this->db->exec($sql);
	
         echo "<script> alert(' Комментарий добавлен! ');</script>";

Redirect("/market/cat-1/cardtovar-$idTov");        
         }
         
                
     }
   
    
    
}