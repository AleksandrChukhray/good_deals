<?php

class ConsultController extends Controller {
    
    public function indexAction($id = null) {
        $category = $this->db->query('select id, name, alt_name, img from consult_category')->fetchall();
        
        $expert = $this->db->query('
        	SELECT cc.id, cc.name, cc.alt_name, ud.FirstName, ud.LastName, ud.UserID 
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid
        	')->fetchall();
    	
    	$questions = $this->db->query('select id, title, body, answer from consult_questions where isActive = 1 ORDER BY id DESC ')->fetchall();

        $consult = new Consult($this->context);

		$consult->getPOST();

        $this->view->breadcrumbs = array(
        array('url' => '/', 'title' => 'Главная'),
		array('url' => '../consult', 'title' => 'Консультации'),
		);
		$this->view->setVars(array(
			'category' => $category,
			'expert' => $expert,
			'questions' => $questions,
		));
		$this->view->generate();
    }
    
    public function AskAction() {
    	$ask = new Consult($this->context);
        $expert = $this->db->query('
        	SELECT cc.id, cc.name, cc.alt_name, ud.FirstName, ud.LastName, ud.UserID 
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid')->fetchall();
        $category = $this->db->query('select id, name, alt_name, img from consult_category order by id')->fetchall();
        
    	$time = date("Y-m-d H:i:s", time());
    	$email = Tools::getValue('mail');
		$name = Tools::getValue('name');
		(empty(Tools::getValue('q', '')))?($uri = 0):($uri = Tools::getValue('q', ''));
	    
	    if(!empty ($_POST['sendRequ'])) $send_r = 1;
			if (Tools::isPost()){
				$content = array(
					'title' => 'Вы задали вопрос',
					'template' => 'send_to_back',
					'data' => array('[year]' => date("Y",time()), '[name]' => $name, '[email]' => $email, '[question]' => Tools::getValue('body'))
				);
			}
		if (!empty($_POST)) {
		    $image = new Securimage();
		    if ($image->check($_POST['captcha_code']) == true) {
		        
		        $this->db->prepare('insert into consult_questions (title, body, name, email, uid, cid, isRequest, isAnswer, pid, questiondate) 
		        					values ("'.Tools::getValue('title').'","'.Tools::getValue('body').'","'.$name.'","'.$email.'","'.Tools::getValue('setExpName').'","'.Tools::getValue('setCatName').'","'.$send_r.'","1","'.$uri.'","'.$time.'")')->execute();
				if(!empty ($_POST['copyTo'])) Mailer::send($email, $content, 'Вы задали вопрос', null, null);
				
				return AddAlertMessage('success', 'Ваш вопрос был сохранен, ожидайте ответа.!', '/consult/');
			
		    } else {
		      return AddAlertMessage('warning', 'Вы неверно указали код с картинки', $_SERVER['REQUEST_URI']);
		    }
		
		}
    	
    	$this->view->breadcrumbs = array(
         	array('url' => '/', 'title' => 'Главная'),
			array('url' => '/consult/', 'title' => 'Консультации'),
			array('url' => '../consult/ask', 'title' => 'Задать вопрос')
			
		);
		$this->view->setVars(array(
			'category' => $category,
			'expert' => $expert,
		));
		
		unset($_SESSION['captcha_keystring']);
		
		$this->view->generate();
	}
	
    public function QuestionsAction() {
    	$word = Tools::getValue('w','');
    	$cat = Tools::getValue('c','');
    	$answ = Tools::getValue('a','');
    	$quest = new Consult($this->context);
    	
    	if (!empty($cat)) { 
    		if (!empty($word)) { 
				$AddWhere = 'cid = "'.$cat.'" AND (title LIKE "%'.$word.'%" OR body LIKE "%'.$word.'%")';
				$search = $quest->getSearch($AddWhere);
				$total=array_column($search, 'isAnswer');
				$total=array_count_values($total);
    		}
    		else {
				$AddWhere = 'cid = "'.$cat.'"';
				$search = $quest->getSearch($AddWhere);
				$total=array_column($search, 'isAnswer');
				$total=array_count_values($total);
    		}
    	} 
    	else {
			$AddWhere = '(title LIKE "%'.$word.'%" OR body LIKE "%'.$word.'%")';
			$search = $quest->getSearch($AddWhere);
			$total=array_column($search, 'isAnswer');
			$total=array_count_values($total);
    	}
    	
    	$post = $quest->getPOST();
    	$questions = $this->db->query('select id, title, body, answer from consult_questions where isActive = 1 ORDER BY id DESC ')->fetchall();
        $category = $this->db->query('select id, name, alt_name, small_img from consult_category')->fetchall();
        $expert = $this->db->query('
        	SELECT cc.id, cc.name, cc.alt_name, ud.FirstName, ud.LastName, ud.UserID 
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid
        	')->fetchall();

    	$this->view->breadcrumbs = array(
        	array('url' => '/', 'title' => 'Главная'),
			array('url' => '/consult/', 'title' => 'Консультации'),
			array('url' => '../consult/questions', 'title' => 'Вопросы')
			
		);
		
		$this->view->setVars(array(
			'category' => $category,
			'expert' => $expert,
			'questions' => $questions,
			'search' => $search,
			'total' => $total,
		));
		
		$this->view->generate();
	}
    
    public function CategoryAction($id) {
    	
    	$experts = new Consult($this->context);
    	
    	$exp_id = $experts->getCatId($id);
    	$exp_user = $experts->getCatUsers($id);
    	
    	$experts->getPOST();
    	$questions = $this->db->query('select id, title, body, answer from consult_questions where isActive = 1 ORDER BY id DESC ')->fetchall();
    	
        $category = $this->db->query('select id, name, alt_name, small_img from consult_category')->fetchall();
        $expert = $this->db->query('
        	SELECT cc.id, cc.name, cc.alt_name, ud.FirstName, ud.LastName, ud.UserID 
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid limit 5
        	')->fetchall();

    	$this->view->breadcrumbs = array(
	    	array('url' => '/', 'title' => 'Главная'),
			array('url' => '/consult/', 'title' => 'Консультации'),
			array('url' => '../consult/cn-'.$id, 'title' => $exp_id['name'])
		);
		$this->view->setVars(array(
			'category' => $category,
			'experts' => $exp_id,
			'expert' => $expert,
			'category' => $category,
			'expertsuser' => $exp_user,
			'questions' => $questions,

		));
		$this->view->generate();
	}
	
    public function ExpertAction($id) {
    	$exper = new Consult($this->context);
		$cat = new ConsultUser($this->context, 'uid = "'.$id.'"');
		if (!empty($_SESSION['auth']['id'])) {
			if (isset($_GET['del'])) {
			        $this->db->prepare('update consult_questions set answer = "", isAnswer = 1, answerdate = "" where id ='.$_GET['del'])->execute();
			        echo '<meta http-equiv="refresh" content="0; url= /consult/cn-'.$_SESSION['auth']['cat'].'/ex-'.$_SESSION['auth']['id'].'" />';
			}
		}
		$expr_user = $exper->getUser($id, $cat->cid);
		if (empty($expr_user)) AddAlertMessage('warning', 'Указанного эксперта в категории не существует', '/consult/cn-'.$cid.'/');

    	$questions = $exper->getQuestion($id, $cat->cid);    	
    	$getall = $exper->getAllQuestion($cat->cid);  	
    	$exper->getPOST();
    	
    	$count = $exper->getCount($id, $cat->cid);

    	$wAns = $count['cqu'] - $count['ans'];
    	$wAns = $wAns.' '.$exper->pluralForm($wAns, 'вопрос', 'вопроса', 'вопросов');
    	($count['cns'] == NULL)?($cns = 0):($cns = $count['cns']); 
    	$cns = $cns.' '.$exper->pluralForm($cns, 'вопрос', 'вопроса', 'вопросов');
    	$plQuest = $count['cqu'].' '.$exper->pluralForm($count['cqu'], 'вопрос', 'вопроса', 'вопросов');
    	$counta = (!empty($count['ans']))?($count['ans']):('0');
    	$plAnsw = $counta.' '.$exper->pluralForm($counta, 'ответ', 'ответа', 'ответов');
    	
        $expert = $this->db->query('
        	SELECT cc.id, cc.name, cc.alt_name, ud.FirstName, ud.LastName, ud.UserID 
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid
        	')->fetchall();
        $category = $this->db->query('select id, name, alt_name, small_img from consult_category')->fetchall();

   	
    	$this->view->breadcrumbs = array(
        	array('url' => '/', 'title' => 'Главная'),
			array('url' => '/consult/', 'title' => 'Консультации'),
			array('url' => '../cn-'.$expr_user['id'], 'title' => $expr_user['name']),
			array('url' => '../cn-'.$expr_user['id'].'/ex-'.$expr_user['UserID'].'/', 'title' => $expr_user['FirstName'].' '.$expr_user['LastName'])
			
		);
		$this->view->setVars(array(
			'category' => $category,
			'expert' => $expert,
			'expertuser' => $expr_user,
			'questions' => $questions,
			'getAllQuest' => $getall,
			'count' => array(
				'plq' => $plQuest,
				'pla' => $plAnsw,
				'cqu' => $count['cqu'],
				'can' => $counta,
				'waw' => $wAns,
				'cns' => $cns,
			),	
		));
		$this->view->generate();
	}
	
	public function QpageAction($id) {
    	$qpage = new Consult($this->context);
    	$qdate = new ConsQue($this->context, 'id = "'.$id.'"');
		$cat = new ConsultUser($this->context, 'uid = "'.$_SESSION['auth']['id'].'"');
    	
    	
       	if (!empty($_SESSION['auth']['id'])) {
       	
       		if ($qdate->uid == 0 && $qdate->cid == $cat->cid) {
       			$this->db->prepare('update consult_questions set uid ='.$_SESSION['auth']['id'].' where id ='.$id)->execute();
				return AddAlertMessage('success', 'Вопрос стал личным.', '/consult/q-'.$qdate->id);
       		}	
       		
			if (!empty($_POST)) {
				$ans_wer = Tools::getValue('body');
				$time = date("Y-m-d H:i:s", time());
				$email = $qdate->email;
				$uri = ($qdate->pid == 0)?($uri = 'http://dev.karapuz/consult/ask/?cn='.$qdate->cid.'&ex='.$qdate->uid.'&q='.$qdate->id):($uri = 'http://dev.karapuz/consult/ask/?cn='.$qdate->cid.'&ex='.$qdate->uid.'&q='.$qdate->pid);

					$content = array(
						'title' => 'Вы получили ответ',
						'template' => 'expert_answer',
						'data' => array('year' => date("Y,M,D",time()), 'name' => $qdate->name, 'answer' => $ans_wer, 'uri' => $uri)
					);
					
				
			        $this->db->prepare('update consult_questions set answer = "'.$ans_wer.'", isAnswer = 0, answerdate = "'.$time.'" where id ='.$id)->execute();
			        
				//$sendAnswer = SendEmailSMTP('', '', $email, $content);
				//if ($sendAnswer !== true) return AddAlertMessage('danger', 'Ошибка при отправке письма!', '/');
				return AddAlertMessage('success', 'Ответ был успешно добавлен.', '/consult/cn-'.$qdate->cid.'/ex-'.$qdate->uid);
			}
       		
    	}

		($qdate->pid != 0)?($pid = $qdate->pid):($pid = $qdate->id);
		($qdate->uid > 0)?($qpuid = $qdate->uid):($qpuid = 1);
		$disquz = $this->db->query('
    		select CQ.id, CQ.title, CQ.body, CQ.name, CQ.answer, CQ.isAnswer, CQ.pid pid, CQ.questiondate, CQ.answerdate, CQ.uid, CQ.cid, UD.FirstName, UD.LastName, CC.name cname
    		from consult_questions CQ, userdata UD, consult_category CC
    		where CQ.isActive = 1 and CQ.pid='.$pid.' and UD.UserID = '.$qpuid.' and CQ.cid=CC.id
    		order by CQ.id ASC 
    		')->fetchall();
    	$disquzz = $this->db->query('
    		select CQ.id, CQ.title, CQ.body, CQ.name, CQ.answer, CQ.isAnswer, CQ.pid pid, CQ.questiondate, CQ.answerdate, CQ.uid, CQ.cid, UD.FirstName, UD.LastName, CC.name cname
    		from consult_questions CQ, userdata UD, consult_category CC
    		where CQ.isActive = 1 and CQ.id='.$pid.' and UD.UserID = '.$qpuid.' and CQ.cid=CC.id
    		order by CQ.id ASC 
    		')->fetch();
		$qpage->getPOST();
    	
        $category = $this->db->query('select id, name, alt_name, small_img from consult_category')->fetchall();
    	$questions = $this->db->query('select id, title, body, answer from consult_questions where isActive = 1 ORDER BY id DESC ')->fetchall();
        $expert = $this->db->query('
        	SELECT cc.id, cc.name, cc.alt_name, ud.FirstName, ud.LastName, ud.UserID 
        	FROM UserData as ud, consult_category as cc, consult_user_category as cu  
        	WHERE ud.UserID=cu.uid and cc.id=cu.cid
        	')->fetchall();

		$this->view->breadcrumbs = array(
	    	array('url' => '/', 'title' => 'Главная'),
			array('url' => '/consult/', 'title' => 'Консультации'),
			array('url' => '../consult/q-'.$id, 'title' => $qdate->title)
		);
		
		$this->view->setVars(array(
			'category' => $category,
			'expert' => $expert,
			'questions' => $questions,
			'qdate' => $qdate,
			'disquz' => $disquz,
			'disquzz' => $disquzz
			
		));
        	
		$this->view->generate();
	}

}