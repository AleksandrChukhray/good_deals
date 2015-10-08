<?php

/**
 * @author Димон
 */
class Market extends Model {

    public function __construct($Acontext, $id = null) {
        parent::__construct($Acontext, $id);
        //переносим старые объявления в архив
        $end_of_time = time();
        $query = "SELECT * FROM tovar_market WHERE `active`=1 and `dataf` < '$end_of_time'";
        $q = $this->db->query($query)->fetchAll();

        foreach ($q as $v => $f) {
            $w = "update `tovar_market` set `active`='0' where `id`=$f[id]";
            $this->db->query($w);
        }
    }

    //выборка подкатегорий
    public function getCategoryComm() {
        $query = 'SELECT c.id_p, c.name_cat, c.image_cat
			FROM podcategory_market c WHERE c.active=1
			ORDER BY c.id_p ASC';

        return $this->db->query($query)->fetchAll();
    }

     //выборка подкатегории по id 
    public function getCategoryCommId($id) {
        $query = 'SELECT c.id_p, c.name_cat, c.image_cat
			FROM podcategory_market c WHERE c.active=1 and ';
	$query .= (preg_match('/\=/', $id)) ? $id : 'c.id_p = ' . (int) $id;

        return $this->db->query($query)->fetchAll();
    }
    
    // выборка последних товаров на странице комиссионки
    public function getLastTovar($num_page = NULL) {
        if ($num_page == NULL)
            $num_page = 0;
        
        $query = "SELECT *  FROM tovar_market tm left join `tovar_image_market` tim on `tm`.`id`=`tim`.`tovar_id`
			WHERE `tm`.`active`=1 GROUP by `tim`.`tovar_id` ORDER BY `tm`.`datas`   DESC limit $num_page,6";

        return $this->db->query($query)->fetchAll();
    }

    // выборка последних товаров на странице GS
    public function getLastTovarGS($p = 1, $count = false) {

        $query = "SELECT *
			FROM tovar_market tm left join `tovar_image_market` tim on `tm`.`id`=`tim`.`tovar_id`
			WHERE `tm`.`active`=1 GROUP by `tim`.`tovar_id`  ORDER BY `tm`.`datas`  desc  limit 4";

        if ($count !== false)
            return $this->db->query($query)->rowCount();

        return $this->db->query($query)->fetchAll();
    }

    public function getCat($id = null) {
        $query = 'SELECT * FROM podcategory_market pk WHERE ';
        $query .= (preg_match('/\=/', $id)) ? $id : 'pk.id_p = ' . (int) $id;
        return $this->db->query($query)->fetch();
    }

    // выборка товара в зависимости от подкатегории
    public function getCatTovar($id, $num_page = NULL) {
        if ($num_page == NULL)
            $num_page = 0;
       
        $query = "SELECT *
			FROM tovar_market tm left join `tovar_image_market` tim on `tm`.`id`=`tim`.`tovar_id`
			WHERE tm.id_podcat = '$id' and `tm`.`wish`='s' and tm.active=1 GROUP by `tim`.`tovar_id`
			ORDER BY tm.datas DESC limit $num_page,6";

        return $this->db->query($query)->fetchAll();
    }

   // выборка товара в зависимости от подкатегории и подкатегории дарю/меняю
    public function getGiftTovar($id=null, $num_page = NULL) {
        if ($num_page == NULL)
            $num_page = 0;
       
        if($id!=null){
            $a = "chm.id_podcat = '$id'  and ";
        }
        else {
            $a = "";
        }
   
        $query_ch = "SELECT *
			FROM change_market chm left join `change_image_market` cim on `chm`.`id`=`cim`.`tovar_id`
			WHERE $a chm.active=1 GROUP by `cim`.`tovar_id`
			ORDER BY chm.datas DESC limit $num_page,6";

        return $this->db->query($query_ch)->fetchAll();
    
    }  
    
    public function getUnionTovarHTML($id, $num_page = NULL) {
    
        $cat = $this->getCategoryCommId($id);
        
        $tree .="<div style='background-color:#c1e3f6; width:98%;  margin: 1% 0 1% 1.5%;border-radius:6px; '>"
                . "<img src='".$cat[0][image_cat]."' style='margin-left:25px;vertical-align:middle'>&nbsp;&nbsp;"
                . "<h4 style='display:inline-block;color:#5c355c;margin-left:15px;'>".$cat[0][name_cat]."</h4></div>";
        
        
      $tov_arr = $this->getGiftTovar($id, $num_page);
        $tree .= "<div style='clear:both;background-color:#d4f1a4;display:inline-block;width:98%; margin-left:1.5%;border-radius:6px;padding-bottom: 15px;'>"
                . "<h4 style='color:#5c355c;margin-left:15px;  margin-top: 5px;'>ДАРЮ / МЕНЯЮ</h4>";
        foreach ($tov_arr as $k => $d) {

            $datas = date('d-m-Y', $d['datas']);
 if ($d['wish'] == 'g') {
                $f = "<img src='" . URL . "public/images/comiss/gift.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/change.png'>";
            }
            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovargift-' . $d['id'] . '">';
            $tree .= '<div class="tovar_ch">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span><div id="price">' . $d['price'] . '&nbsp; грн.</div>
                                <br>
                                <span>' . $d['name_tovar'] . '</span><br>
                                &nbsp;<span class="m_desc" style="white-space:nowrap">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
            $counter++;
        }
$tree .="</div>";
 
 $tov_arrr = $this->getCatTovar($id, $num_page);
             $tree .= "<div style='clear:both;display:inline-block;margin:20px 0px 0px 15px;width:100%'><h4 <h4 style='color:#5c355c;'>КОМИССИОНКА</h4></div>";
             foreach ($tov_arrr as $k => $d) {

            $datas = date('d-m-Y', $d['datas']);

            if ($d['wish'] == 's') {
                $f = "<img src='" . URL . "public/images/comiss/prodam.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/kuplu.png'>";
            }
           
            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovar-' . $d['id'] . '">';
            $tree .= '<div class="tovar">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span><div id="price">' . $d['price'] . '&nbsp; грн.</div>
                                <br>
                                <span>' . $d['name_tovar'] . '</span><br>
                                &nbsp;<span class="m_desc" style="white-space:nowrap">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
            $counter++;
        }

        return $tree;
    }
    //выборка товара дарю/меняю
    public function getGiftTovarHTML($id, $num_page=0){
         
        $tov_arr = $this->getGiftTovar($id,$num_page);
       
        foreach ($tov_arr as $k => $d) {

            $datas = date('d-m-Y', $d['datas']);
 if ($d['wish'] == 'g') {
                $f = "<img src='" . URL . "public/images/comiss/gift.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/change.png'>";
            }
            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovargift-' . $d['id'] . '">';
            $tree .= '<div class="tovar_ch">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span>
                                <br>
                                <span>' . $d['name_tovar'] . '</span><br>
                                &nbsp;<span class="m_desc" style="white-space:nowrap">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
            $counter++;
        }
         return $tree;
    }




    // выборка товара для карточки
    public function getFullTovar($id, $count = false) {

        $query = 'SELECT * FROM tovar_market tm  WHERE ';
        $query .= (preg_match('/\=/', $id)) ? $id : 'tm.id = ' . (int) $id;
        if ($count !== false)
            return $this->db->query($query)->rowCount();
        return $this->db->query($query)->fetchAll();
    }
  // выборка товара для карточки дарю/меняю 
    public function getFullTovarGift($id, $count = false) {

        $query = 'SELECT * FROM change_market tm  WHERE ';
        $query .= (preg_match('/\=/', $id)) ? $id : 'tm.id = ' . (int) $id;
        if ($count !== false)
            return $this->db->query($query)->rowCount();
        return $this->db->query($query)->fetchAll();
    }
    //выборка картинок товара для карточки
    public function getImageFullTovar($id, $count = false) {
        $query = 'SELECT * FROM tovar_image_market tm  WHERE ';
        $query .= (preg_match('/\=/', $id)) ? $id : 'tm.tovar_id = ' . (int) $id;
        if ($count !== false)
            return $this->db->query($query)->rowCount();
        return $this->db->query($query)->fetchAll();
    }
 //выборка картинок товара дарю меняю для карточки
    public function getImageFullTovarGift($id, $count = false) {
        $query = 'SELECT * FROM change_image_market tm  WHERE ';
        $query .= (preg_match('/\=/', $id)) ? $id : 'tm.tovar_id = ' . (int) $id;
        if ($count !== false)
            return $this->db->query($query)->rowCount();
        return $this->db->query($query)->fetchAll();
    }
// показ подкатегорий
    public function getCategoriesCommTreeHTML($data = null, $level = 1) {
        if ($data == null)
            $data = $this->getCategoryComm();
        $counter = 1;
        $tree = '<ul class="level' . $level . ' acordion">';
        foreach ($data as $k => $d) {
            $tree .= '<li class="line"></li>';
            $tree .= '<li ' . (($counter == count($data)) ? 'class="last"' : '') . '>';
            $tree .= '<a href="/market/cat-' . $d['id_p'] . '" class="name"><img src=' . $d['image_cat'] . ' style="width:10%; vertical-align:middle">&nbsp;' . $d['name_cat'] . '</a>';
            $tree .= '</li>';
            $counter++;
        }
        $tree .= '</ul>';

        return $tree;
    }
// показ подкатегорий для дарю меняю
    public function getCategoriesChangeTreeHTML($data = null, $level = 1) {
        if ($data == null)
            $data = $this->getCategoryComm();
        $counter = 1;
        $tree = '<ul class="level' . $level . ' acordion">';
        foreach ($data as $k => $d) {
            $tree .= '<li class="line"></li>';
            $tree .= '<li ' . (($counter == count($data)) ? 'class="last"' : '') . '>';
            $tree .= '<a href="/market/change-' . $d['id_p'] . '" class="name"><img src=' . $d['image_cat'] . ' style="width:10%; vertical-align:middle">&nbsp;' . $d['name_cat'] . '</a>';
            $tree .= '</li>';
            $counter++;
        }
        $tree .= '</ul>';

        return $tree;
    }
// показ подкатегорий в списке для нового товара
    public function getCategoriesOptHTML($data = null, $level = 1) {
        if ($data == null)
            $data = $this->getCategoryComm();
        $counter = 1;

        foreach ($data as $k => $d) {
            $tree .= '<option value="' . $d['id_p'] . '">' . $d['name_cat'] . '</option>';
        }

        return $tree;
    }

    //подсчет общего количества товара для главной комиссионки
    public function GetCountTovar() {
        $query = "SELECT *  FROM tovar_market tm WHERE `tm`.`active`=1";
        return $this->db->query($query)->rowCount();
    }

    //подсчет общего количества товара для категорий комиссионки
    public function GetCountTovarCat($id) {
        $query = "SELECT *  FROM tovar_market tm WHERE `tm`.`active`=1 and `tm`.`id_podcat`=$id";
        return $this->db->query($query)->rowCount();
    }

    // показ последних товаров на странице комиссионки 
    public function getLastTovarHTML($level = 0) {
        $data = $this->getLastTovar($level); //все полностью
        foreach ($data as $k => $d) {
            $datas = date('d-m-Y', $d['datas']);
            if ($d['wish'] == 's') {
                $f = "<img src='" . URL . "public/images/comiss/prodam.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/kuplu.png'>";
            }
            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovar-' . $d['id'] . '">';
            $tree .= '<div class="tovar">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span><div id="price">' . $d['price'] . '&nbsp; грн.</div>
                                <br>
                                <span>' . $d['name_tovar'] . '</span><br>
                                &nbsp;<span class="m_desc" style="white-space:nowrap">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
        }

        return $tree;
    }

    // показ последних товаров на главной странице маркета 
    public function getLastTovarHTMLGS($data = null, $level = 1) {
        if ($data == null)
            $data = $this->getLastTovarGS();
        $counter = 1;

        foreach ($data as $k => $d) {
            $datas = date('d-m-Y', $d['datas']);
            if ($d['wish'] == 's') {
                $f = "<img src='" . URL . "public/images/comiss/prodam.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/kuplu.png'>";
            }
            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovar-' . $d['id'] . '">';
            $tree .= '<div class="tovar_gs">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span><div id="price">' . $d['price'] . '&nbsp; грн.</div>
                                <br>
                                <span>' . $d['name_tovar'] . '</span><br>
                                &nbsp;<span class="m_desc">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
            $counter++;
        }


        return $tree;
    }

    // показ товара в зависимости от категории
    public function getCatTovarHTML($data, $level = 0) {

        $tov_arr = $this->getCatTovar($data, $level);
        $counter = 1;

        foreach ($tov_arr as $k => $d) {

            $datas = date('d-m-Y', $d['datas']);

            if ($d['wish'] == 's') {
                $f = "<img src='" . URL . "public/images/comiss/prodam.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/kuplu.png'>";
            }
            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovar-' . $d['id'] . '">';
            $tree .= '<div class="tovar">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span><div id="price">' . $d['price'] . '&nbsp; грн.</div>
                                <br>
                                <span>' . $d['name_tovar'] . '</span><br>
                                &nbsp;<span class="m_desc" style="white-space:nowrap">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
            $counter++;
        }


        return $tree;
    }

    // выборка имени и аватарки юзера
    public function getByer($id) {

        // $context = Tools::getContext();
        $query = 'SELECT FirstName,Avatar  FROM UserData WHERE UserID=' . (int) $id;

        $row = $this->db->query($query)->fetch();

        return $row;
    }

    // выборка телефона и мыла юзера
    public function getByerAddr($id) {
        //$context = Tools::getContext();
       $query = 'SELECT Email, PhoneNumber  FROM Users WHERE ID=' . (int) $id;
       $row = $this->db->query($query)->fetch();

        return $row;
    }

    //выбор комментариев в зависимости от товара
    public function showKomm($id, $count = false) {
        $sql = "select * from komm_tovar_market where id_tov = '$id'";
        if ($count !== false)
            return $this->db->query($sql)->rowCount();
        return $this->db->query($sql)->fetchAll();
    }

    // показ товара в зависимости от категории
    public function getFullTovarHTML($data, $level = 1) {

        $tov_arr = $this->getFullTovar($data);
        $show_komm = $this->showKomm($data);

        foreach ($tov_arr as $k => $d) {
            $tov_img = $this->getImageFullTovar($d['id']);
            $datas = explode(" ", $d['datas']);
            $datas = explode("-", $datas[0]);
            $user = $this->getByer($d['user']);

            $addr = $this->getByerAddr($d['user']);

            if (empty($addr['FirstName'])) {
                $userName = $d['user'];
            }
            if (empty($addr['Avatar'])) {
                $userAvatar = URL . 'public/images/market/no_ava.png';
            }
            $tree .= '<div class="tovar_card">
                                <h2>' . $d['name_tovar'] . '</h2>
                                <span class="m_desc_full">' . $d['short_desc'] . '</span><div id="price_t">' . $d['price'] . '&nbsp; грн.</div>
                                <div id="left_block">';

            $tree .='<img src="' . URL . $tov_img[0]['img'] . '" id="bigimgtov" class="imgtov"></div><div id="small">';
            foreach ($tov_img as $a => $v) {
                $tree .= '<img src="' . URL . $v['img'] . '" class="smallimgtov" id="im_' . $v['tovar_id'] . '" onclick="ch(\'' . URL . $v['img'] . '\');"><br>';
            }
            $tree .= '</div>
                                <br> 
                               <span class="m_desc_f">' . $d['desc_tovar'] . '</span><br><br>
                                   <div class="hr"></div>
                                   <div id="user_info">
                                        <img src="' . $userAvatar . '" id="avatar">
                                        <div class="username">' . $userName . ',<br>
                                            <span class="usercity">' . $d['city'] . '</span>
                                        </div>
                                        <div id="cont">
                                            <img src="/public/images/market/tel.png"><span class="phone">' . $d['user_ph'] . '</span><br>
                                            <img src="/public/images/market/mail.png"><span class="phone"><img src="/public/images/market/send.png"></span>
                                        </div>
                                    </div>
                                    <br> 
                                   <div class="hr"></div>
                                   <br>
                                  
                                  
                                   <span class="m_desc_k">Комментарии</span><span id="butt" data-toggle="modal"><img src="/public/images/comiss/add_k.png"></span>
<div id="modal" > 
<form name="komms"  id="komms" method="post" action="' . URL . 'market/new_comment/">
<input type="hidden" value="' . $d['id'] . '" name="id_tov">
   <span class="of_komm">Ваш комментарий <span style="color:red">*</span></span><span class="of_komm_slow"> осталось <span id="count">500</span>&nbsp;знаков </span><br> 
    <textarea name="new_komm" id="new_komm"></textarea><br><br>
        <span class="of_komm">Личная информация </span><br><br>
        <span class="of_komm_m">Имя или Псевдоним <span style="color:red">*</span></span><br>
        <input type="text" value="" placeholder="Имя или псевдоним" name="t_k" class="t_k" required><br><br>
        <span class="of_komm_m">Email (будет скрыт) <span style="color:red">*</span></span><br>
        <input type="text" value="" placeholder="email" name="e_k" class="t_k" required><br><br>
        <div class="footik"><input type="image"  src="/public/images/comiss/go_komm.png" style="margin-left:67%"><br></div>
</form>	
</div>                                   
<br><br>
   <div id="show_komments"> ';

            foreach ($show_komm as $km => $v) {
                $tree.= "<span style='color:#a7c55a'>" . $v['id_k'] . "</span>&nbsp;<span class='of_komm_slow'>" . $v['data'] . "</span><br>";
                $tree.="<span class='of_komm_slow'>" . $v['text'] . "</span><br><div class='hr'></div><br>";
            }
            $tree.= '</div>                                

</div>
                                   
</div>
</div>';
        }

        return $tree;
    }
 public function getFullTovarGiftHTML($data, $level = 1) {

        $tov_arr = $this->getFullTovarGift($data);
        $show_komm = $this->showKomm($data);//поменять!!!! 

        foreach ($tov_arr as $k => $d) {
            $tov_img = $this->getImageFullTovarGift($d['id']);
            $datas = explode(" ", $d['datas']);
            $datas = explode("-", $datas[0]);
            $user = $this->getByer($d['user']);

            $addr = $this->getByerAddr($d['user']);

            if (empty($addr['FirstName'])) {
                $userName = $d['user'];
            }
            if (empty($addr['Avatar'])) {
                $userAvatar = URL . 'public/images/market/no_ava.png';
            }
            $tree .= '<div class="tovar_card">
                                <h2>' . $d['name_tovar'] . '</h2>
                                <span class="m_desc_full">' . $d['short_desc'] . '</span>
                                <div id="left_block">';

            $tree .='<img src="' . URL . $tov_img[0]['img'] . '" id="bigimgtov" class="imgtov"></div><div id="small">';
            foreach ($tov_img as $a => $v) {
                $tree .= '<img src="' . URL . $v['img'] . '" class="smallimgtov" id="im_' . $v['tovar_id'] . '" onclick="ch(\'' . URL . $v['img'] . '\');"><br>';
            }
            $tree .= '</div>
                                <br> 
                                <br> 
                                <h4 style="clear: both;"><i>ВАРИАНТЫ ОБМЕНА</i></h4>
                               <span class="m_desc_f">' . $d['desc_tovar'] . '</span><br><br>
                               <h4 style="clear: both;"><i>УСЛОВИЯ ОБМЕНА</i></h4>
                               <span class="m_desc_f">' . $d['price'] . '</span><br><br>    
                                   <div class="hr"></div>
                                   <div id="user_info">
                                        <img src="' . $userAvatar . '" id="avatar">
                                        <div class="username">' . $userName . ',<br>
                                            <span class="usercity">' . $d['city'] . '</span>
                                        </div>
                                        <div id="cont">
                                            <img src="/public/images/market/tel.png"><span class="phone">' . $d['user_ph'] . '</span><br>
                                            <img src="/public/images/market/mail.png"><span class="phone"><img src="/public/images/market/send.png"></span>
                                        </div>
                                    </div>
                                    <br> 
                                   <div class="hr"></div>
                                   <br>
                                  
                                  
                                   <span class="m_desc_k">Комментарии</span><span id="butt" data-toggle="modal"><img src="/public/images/comiss/add_k.png"></span>
<div id="modal" > 
<form name="komms"  id="komms" method="post" action="' . URL . 'market/new_comment_gift/">
<input type="hidden" value="' . $d['id'] . '" name="id_tov">
   <span class="of_komm">Ваш комментарий <span style="color:red">*</span></span><span class="of_komm_slow"> осталось <span id="count">500</span>&nbsp;знаков </span><br> 
    <textarea name="new_komm" id="new_komm"></textarea><br><br>
        <span class="of_komm">Личная информация </span><br><br>
        <span class="of_komm_m">Имя или Псевдоним <span style="color:red">*</span></span><br>
        <input type="text" value="" placeholder="Имя или псевдоним" name="t_k" class="t_k" required><br><br>
        <span class="of_komm_m">Email (будет скрыт) <span style="color:red">*</span></span><br>
        <input type="text" value="" placeholder="email" name="e_k" class="t_k" required><br><br>
        <div class="footik"><input type="image"  src="/public/images/comiss/go_komm.png" style="margin-left:67%"><br></div>
</form>	
</div>                                   
<br><br>
   <div id="show_komments"> ';

            foreach ($show_komm as $km => $v) {
                $tree.= "<span style='color:#a7c55a'>" . $v['id_k'] . "</span>&nbsp;<span class='of_komm_slow'>" . $v['data'] . "</span><br>";
                $tree.="<span class='of_komm_slow'>" . $v['text'] . "</span><br><div class='hr'></div><br>";
            }
            $tree.= '</div>                                

</div>
                                   
</div>
</div>';
        }

        return $tree;
    }
    // фильтр
    public function getFiltrQuery($p = 1, $count = false) {

        $bye = $_POST['bye_sale'];
        $ot = $_POST['ot'];
        $do = $_POST['do'];
        $stan = $_POST['stan'];
        $sex = $_POST['sex'];
        $id = $_POST['id_cat'];
        $query = "SELECT *
			FROM tovar_market tm left join `tovar_image_market` tim on `tm`.`id`=`tim`.`tovar_id`
			WHERE tm.id_podcat = '$id' and `tm`.`wish`='$bye' and `tm`.`gender`='$sex' and `tm`.`kind`='$stan' and (`tm`.`price` >'$ot' or `tm`.`price`<'$do') GROUP by `tim`.`tovar_id`
			ORDER BY tm.datas DESC ";

        if ($count !== false)
            return $this->db->query($query)->rowCount();

        return $this->db->query($query)->fetchAll();
    }

    public function getFiltr($level = 1) {

        $tov_arr = $this->getFiltrQuery();
        $counter = 1;
        foreach ($tov_arr as $k => $d) {

            $datas = date('d-m-Y', $d['datas']);
            if ($d['wish'] == 's') {
                $f = "<img src='" . URL . "public/images/comiss/prodam.png'>";
            } else {
                $f = "<img src='" . URL . "public/images/comiss/kuplu.png'>";
            }

            $tree .= '<a href="/market/cat-' . $d['id_podcat'] . '/cardtovar-' . $d['id'] . '">';
            $tree .= '<div class="tovar">
                                <div id="dt"><img src="' . URL . $d['img'] . '" ></div><br><span style="margin-top:-7px !important;">' . $f . '</span><div id="price">' . $d['price'] . '&nbsp; грн.</div>
                                <br>
                                <span>' . $d['name_tovar'] . '</span>
                                    <br>
                                &nbsp;<span class="m_desc" style="white-space:nowrap">' . $d['short_desc'] . '</span>
                                <br> 
                                &nbsp;<span class="sity">' . $d['city'] . ',</span>&nbsp;&nbsp;<span class="mdesc_1">' . $datas . '</span></div>';
            $tree .= '</a>';
            $counter++;
        }

        return $tree;
    }

}
