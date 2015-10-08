<?php
class CatalogController extends Controller {
    public function indexAction($id = null) {
        $cat_pod = $this->db->query('select ID, id_cat, name, CountItems from Catalog_pod where IsDeleted=0 order by id_cat')->fetchAll();

        $this->view->setVars(array(
            'cat_pod' => $cat_pod
        ));
        
        $this->view->breadcrumbs = array(
            array('url' => '/catalog/', 'title' => 'Каталог организаций')
        );
        
        $this->view->meta = array(
            'meta_title' => 'Каталог организаций',
            'meta_description' => 'Каталог организаций',
            'meta_keywords' => 'организации, спорт, образование, праздник, здоровье, школа, детский сад, роддом, танцы, кружки, досуг, молочные кухни'
        );

        $this->view->generate();
    }
    
    public function itemsAction($id = null) {
        if (empty($id)) {
            return AddAlertMessage('danger', 'Категории не существует.', '/');
        }
        
        $current_page = GETAsStrOrDef('page', 1);
        $SearchText = ""; 
        if (filter_input(INPUT_POST, 'SearchText') !== NULL) {
            $SearchText = filter_input(INPUT_POST, 'SearchText');
        }
        
        $regions = $this->db->query("select ID, ShortName as Name from Regions order by Name;")->fetchAll();
        $SelectedRegionID = GETAsStrOrDef('reg', '0');
        
        $sql = "select ID, Name from view_Localities where (RegionID = ".(empty($SelectedRegionID) ? "0" : $SelectedRegionID).") order by Name;";
        $localities = $this->db->query($sql)->fetchAll();
        $SelectedLocalityID = GETAsStrOrDef('loc', '0');
        
        $pod = $this->db->query('select name from Catalog_pod where ID='.$id)->fetch();
        $SubCategoryName = $pod["name"];

        $WhereStr = "where CI.id_pod_cat = $id ".
                    "and CI.IsDeleted=0 ".
                    "and CI.IsActive=1 ";
        if ($SelectedRegionID != '0') {
            $WhereStr .= "and CI.RegionID = ".$SelectedRegionID." ";
        }
        if ($SelectedLocalityID != '0') {
            $WhereStr .= "and CI.LocalityID = ".$SelectedLocalityID." ";
        }
        if (!empty($SearchText)) {
            $WhereStr .= "and ((CI.name like '%$SearchText%') or (CI.adress like '%$SearchText%')) ";
        }
        
        $sql = "select count(*) as RecordCount ".
               "from Catalog_item as CI ".
               $WhereStr;
        $rec = $this->db->query($sql)->fetch();
        $RecordsPerPage = 15;
        $total_pages = ceil($rec["RecordCount"]/$RecordsPerPage);
        
        $sql = "select CI.ID, CI.name, CI.adress, CI.kont_tell, CI.CountComments, L.Name as Locality, R.ShortName as RegionName, ".
               "(CI.Rating1+CI.Rating2+CI.Rating3)/3 as TotalRating ".
               "from Catalog_item as CI ".
               "left outer join view_ShortLocalities as L on (CI.LocalityID = L.ID) ".
               "left outer join Regions as R on (CI.RegionID = R.ID) ".
               $WhereStr.
               "order by CI.name ".
               "limit ".($current_page-1)*$RecordsPerPage.", ".$RecordsPerPage;
        $items = $this->db->query($sql)->fetchAll();
        
        $this->view->setVars(array(
            'id' => $id,
            'items' => $items,
            'pagination' => array(
                'total_pages' => $total_pages,
                'current' => $current_page,
                'perpage' => $RecordsPerPage
            ),
            'SubCategoryName' => $SubCategoryName,
            'regions' => $regions,
            'SelectedRegionID' => $SelectedRegionID,
            'localities' => $localities,
            'SelectedLocalityID' => $SelectedLocalityID,
            'SearchText' => $SearchText
        ));
        
        $this->view->breadcrumbs = array(
            array('url' => '/catalog/', 'title' => 'Каталог организаций'),
            array('url' => '/catalog/p-'.$id, 'title' => $SubCategoryName),
        );
        
        $this->view->meta = array(
            'meta_title' => 'Каталог организаций: '.$SubCategoryName,
            'meta_description' => 'Каталог организаций: '.$SubCategoryName,
            'meta_keywords' => 'организации, спорт, образование, праздник, здоровье, школа, детский сад, роддом, танцы, кружки, досуг, молочные кухни'
        );

        $this->view->generate();
    }

    public function itemAction($id = null) {
        if (empty($id)) {
            return AddAlertMessage('danger', 'Организации не существует.', '/');
        }

        $CanSubmit = CanSubmit_CheckTokenForPreventDoubleSubmit();
        $ActiveTab = "uslugi";
        /*if (($CanSubmit == true) && (filter_input(INPUT_POST, 'AddCommentBtn') !== NULL)) {
            $ActiveTab = "comments";
            $MsgUserName = POSTStrAsSQLStr('MsgUserName');
            $MsgText = POSTStrAsSQLStr('MsgText');
            unset($_POST['AddCommentBtn']);
            unset($_POST['MsgUserName']);
            unset($_POST['MsgText']);
            
            if (!empty($MsgUserName) && !empty($MsgText)) {
                if (isset($_SESSION['auth'])) {
                    $vUserID = $_SESSION['auth']['id'];
                    $vUnknownUserGUID = "null";
                } else {
                    $vUserID = "null";
                    $vUnknownUserGUID = "'".(string)GetUnknownUserGUID()."'";
                }
                
                $sql = "insert into CatalogComments(CatalogItemID, UserID, UnknownUserGUID, CreateDate, UserName, Text) ".
                       "values($id, $vUserID, $vUnknownUserGUID, '".GetLocalDateTimeAsSQLStr()."', '$MsgUserName', '$MsgText');";
                $this->db->exec($sql);
            }
        }*/
        
        if (($CanSubmit == true) && (filter_input(INPUT_POST, 'AddRaitingBtn') !== NULL)) {
            $ActiveTab = "raiting";
            $Rating1 = POSTStrAsSQLStr('uslovjEdt');
            $Rating2 = POSTStrAsSQLStr('personalEdt');
            $Rating3 = POSTStrAsSQLStr('uvagaEdt');
            unset($_POST['AddRaitingBtn']);
            unset($_POST['uslovjEdt']);
            unset($_POST['personalEdt']);
            unset($_POST['uvagaEdt']);
            
            if (isset($_SESSION['auth'])) {
                $vUserIDForIns = $_SESSION['auth']['id'];
                $vUnknownUserGUIDForIns = "null";
                $vUserID = "=".$_SESSION['auth']['id'];
                $vUnknownUserGUID = "is null";
            } else {
                $vUserIDForIns = "null";
                $vUnknownUserGUIDForIns = "'".(string)GetUnknownUserGUID()."'";
                $vUserID = "is null";
                $vUnknownUserGUID = "='".(string)GetUnknownUserGUID()."'";
            }

            $sql = "insert into CatalogRatings(CatalogItemID, UserID, UnknownUserGUID, Rating1, Rating2, Rating3) ".
                   "select * from (select $id as C1, $vUserIDForIns as C2, $vUnknownUserGUIDForIns as C3, $Rating1 as C4, $Rating2 as C5, $Rating3 as C6) AS tmp ".
                   "where not exists ( ".
                      "select ID ".
                      "from CatalogRatings ".
                      "where (CatalogItemID = $id) ".
                      "and (UserID $vUserID) ".
                      "and (UnknownUserGUID $vUnknownUserGUID) ".
                   ") limit 1;";
            $this->db->exec($sql);
        }
        
        
        $sql = "select CI.ID, CI.id_pod_cat, CI.name, L.ShortName as LocalityName, CONCAT_WS(', ', L.Name, CI.adress) as FullAddress, CI.adress, CI.foto, CI.kont_tell, ".
                      "CI.site_url, CP.name as SubCategoryName, CI.MetaKeywords, (CI.Rating1+CI.Rating2+CI.Rating3)/3 as TotalRating, ".
                      "CI.Rating1, CI.Rating2, CI.Rating3, CI.CountRatings, CI.uslugi, CI.MapX, CI.MapY, L.RegionName, L.LocalityName as OriginalLocalityName ".
               "from Catalog_item as CI ".
               "left outer join view_LocalitiesWithRegion as L on ((CI.LocalityID = L.ID) and (CI.RegionID = L.RegionID)) ".
               "left outer join Catalog_pod as CP on (CI.id_pod_cat = CP.ID) ".
               "where CI.ID = $id ".
               "and CI.IsDeleted = 0";
        $item = $this->db->query($sql)->fetch();
        
        $sql = "select PI.foto, CONCAT_WS(' ', PI.famil, PI.name, PI.othestvo) as Name, PP.Name as JobTitleName, PI.tell_kont, PI.rabot_graf ".
               "from Personal_item as PI ".
               "left outer join Personal_pod as PP on (PI.id_pod_cat = PP.ID) ".
               "where (PI.CatalogItemID = $id) ".
               "and (PI.IsDeleted = 0) ".
               "order by PI.famil, PI.name, PI.othestvo;";
        $personal = $this->db->query($sql)->fetchAll();
        
        $sql = "select Photo ".
               "from Catalog_item_images ".
               "where (CatalogItemID = $id) ".
               "order by ID;";
        $photos = $this->db->query($sql)->fetchAll();

        $sql = "select CreateDate, UserID, UserName, Text ".
               "from CatalogComments ".
               "where (CatalogItemID = $id) ".
               "and (IsDeleted = 0) ".
               "order by CreateDate desc;";
        $comments = $this->db->query($sql)->fetchAll();
        
        
        if (isset($_SESSION['auth'])) {
            $vUserID = "=".$_SESSION['auth']['id'];
            $vUnknownUserGUID = "is null";
        } else {
            $vUserID = "is null";
            $vUnknownUserGUID = "='".(string)GetUnknownUserGUID()."'";
        }

        $sql = "select ID ".
               "from CatalogRatings ".
               "where (CatalogItemID = $id) ".
               "and (UserID $vUserID) ".
               "and (UnknownUserGUID $vUnknownUserGUID) ".
               "limit 1;";
        $rec = $this->db->query($sql)->fetch();
        $RaitingID = $rec['ID'];
        
        $this->view->setVars(array(
            'id' => $id,
            'item' => $item,
            'personal' => $personal,
            'photos' => $photos,
            'comments' => $comments,
            'ActiveTab' => $ActiveTab,
            'RaitingID' => $RaitingID
        ));
        
        $this->view->breadcrumbs = array(
            array('url' => '/catalog/', 'title' => 'Каталог организаций'),
            array('url' => '/catalog/p-'.$item['id_pod_cat'], 'title' => $item['SubCategoryName']),
            array('url' => '/catalog/i-'.$id, 'title' => $item['name']),
        );
        
        $this->view->meta = array(
            'meta_title' => 'Организация: '.$item['name'],
            'meta_description' => 'Организация: '.$item['name'],
            'meta_keywords' => $item['MetaKeywords']
        );

        SetTokenForPreventDoubleSubmit();
        $this->view->generate();
    }
}