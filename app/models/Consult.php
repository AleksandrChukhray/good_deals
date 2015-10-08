<?php
class ConsultUser extends Model {
	protected $table = 'consult_user_category';
}

class ConsQue extends Model {
	protected $table = 'consult_questions';
}

class Consult extends Model {
	
	public function getCatId($id=null){
    	$query = 'SELECT * FROM consult_category cc WHERE ';
    	$query .= (preg_match('/\=/', $id)) ? $id : 'cc.id = '.(int)$id;
		return $this->db->query($query)->fetch();
    }
    
    public function getCatUsers($id, $count = false){
        $query = 'SELECT cc.id, cc.name, cc.alt_name, ud.UserID, ud.FirstName, ud.LastName, ud.Description, ud.Avatar
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid and cc.id='.$id;
	
		if ($count !== false)
			return $this->db->query($query)->rowCount();
		
		return $this->db->query($query)->fetchAll();
    }
    
	public function getUrl($from) {
		$string = substr($_SERVER['REQUEST_URI'], $from);
			$cid = (int)substr($string, 0, 1);
			$uid = (int)substr($string, 2);
			return $cid = Array(
				'cid' => $cid,
				'uid' => $uid
			);
	}
	
    public function getQuestion($id, $cid){
        $query = 'select id, cid, title, body, answer, name, isAnswer, pid, answerdate, questiondate from consult_questions  where isActive = 1 and uid ='.$id.' and cid = '.$cid;
		return $this->db->query($query)->fetchAll();
    }
    
    public function getAllQuestion($cid){
        $query = 'select id, uid, cid, title, body, answer, name, isAnswer, pid, answerdate, questiondate from consult_questions  where isActive = 1 and cid = '.$cid;
		return $this->db->query($query)->fetchAll();
    }
    
    public function getCount($id, $cid){
        $query = 'select count(*) cqu, sum(isAnswer = 0) ans from consult_questions where isActive = 1 and uid ='.$id.' and cid = '.$cid;
		$query = $this->db->query($query)->fetch();
		$query1 = 'select sum(isAnswer = 1) cns from consult_questions where isActive = 1 and uid = 0 and cid = '.$cid;
		$query1 = $this->db->query($query1)->fetch();
		return   $count = Array (
			'cqu' => $query['cqu'],
			'ans' => $query['ans'],
			'cns' => $query1['cns'],

		);  
	}
    
    public function getUser($id, $cid){
        
        $query = 'select ud.UserID, ud.FirstName, ud.LastName, ud.Description, ud.Avatar, cc.name, cc.id
		from consult_user_category cuc
		left outer join UserData ud on (ud.UserID = cuc.uid)
		left outer join consult_category cc on (cc.id = cuc.cid)
		where (ud.UserID ='.$id.') and (cuc.cid = '.$cid.')';
		
		return $this->db->query($query)->fetch();
    }
    
	public function pluralForm($n, $form1, $form2, $form5) {
	    $n = abs($n) % 100;
	    $n1 = $n % 10;
	    if ($n > 10 && $n < 20) return $form5;
	    if ($n1 > 1 && $n1 < 5) return $form2;
	    if ($n1 == 1) return $form1;
	    return $form5;
	}
	
	// for categories
	public function getSearch($where = ''){
           
		$query = 'SELECT cq.id, cq.title, cq.body, cq.answer, cq.name, cq.questiondate, cq.uid, cq.cid, cq.isAnswer FROM consult_questions cq where cq.isActive = 1 AND cq.isDeleted<>1 ';
		$query .= (empty($where) ? '' : 'AND '.$where.' ');
		$query .= 'ORDER BY cq.questiondate DESC, cq.answerdate DESC';
		$searchpage = $this->db->query($query)->fetchAll();

		return $searchpage;
	}
	
	public function getPOST() {
		if (!empty($_POST)) {
			if (!empty($_POST['subSpec'])) {
	        		
				if (!empty($_POST['setExpName'])) { 
					if ($_POST['srcSpec'] == 0) { 
							echo '<meta http-equiv="refresh" content="0; url= /consult/cn-'.$_POST['setCatName'].'/ex-'.$_POST['setExpName'].'" />';
					}
					else { // ответы
						echo '<meta http-equiv="refresh" content="0; url= /consult/cn-'.$_POST['setCatName'].'/ex-'.$_POST['setExpName'].'/?a=1" />';
					}
				}
	        	
				elseif (!empty($_POST['setCatName'])) { 
					echo '<meta http-equiv="refresh" content="0; url= /consult/cn-'.$_POST['setCatName'].'" />';
				}
			}
			elseif (!empty($_POST['subQue'])) {
				if (!empty($_POST['setCatName']) || !empty($_POST['searchText'])) {
					$str=trim($_POST['searchText']);
					$str=stripslashes($str);
					$str=htmlspecialchars($str);
					$str=str_replace(' ', '+', $str);
					$str=preg_replace('/([?!@#$%&()*:^~,.;:\'\"\/])/', "",$str);
		        		
					(!empty($_POST['searchText']))?($txt='?w='.$str):($txt=''); 
					(!empty($_POST['setCatName']))?($cat='&c='.$_POST['setCatName']):($cat=''); 
					($_POST['srcQue']==1)?($ans='&a=1'):($ans=''); 
						
					echo '<meta http-equiv="refresh" content="0; url= /consult/questions/'.$txt.$cat.$ans.'"';
				}
			}
		}
	}
}