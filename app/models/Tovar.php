<?php

/**
 * @author Димон
 */
class Tovar extends Model {

    public function SaveNewTovar() {

        //берем поля из формы
        $sale_bye = $_POST['sel_bay']; //купить или продать s- продать b- купить
        $zag = $_POST['zag']; //заголовок
        $cat = $_POST['for_cat'];       //выбор категории для товара
        $s_desc = $_POST['desc_sh'];    //короткое описание
        $l_desc = $_POST['full_sh'];    // длинное описание
        $cost = $_POST['cost'];         // цена
        if ($sale_bye === 's' or $sale_bye === 'b') {
            $taba = '`tovar_market`';
            $taba2 = 'tovar_image_market';
        }
        if ($sale_bye === 'g' or $sale_bye === 'c') {
            $taba = '`change_market`';
            $taba2 = 'change_image_market';
        }

        $condition = $_POST['face'];    //состояние товара
        $gender = $_POST['sex'];     //пол ребенка
        $cont_face = $_POST['cont_face']; //контактное лицо
        $cont_email = $_POST['cont_email']; //контактное email
        $cont_tel = $_POST['cont_tel'];        //контактный телефон
        $cont_skype = $_POST['cont_skype']; // skype
        $cont_city = $_POST['cont_city']; //город 
        $datas = time();
        $reg = $_POST['termin'];
        if ($reg == 'unreg') {
            $dataf = $datas + 2629743;
            $for_adv = "1 месяц";
        }
        if ($reg == 'reg') {
            $dataf = $datas + 15778463;
            $for_adv = "6 месяцев";
        }
        if ($reg == '2reg') {
            $dataf = $datas + 5259486;
            $for_adv = "2 месяца";
        }
        if ($reg == '3reg') {
            $dataf = $datas + 7889229;
            $for_adv = "3 месяца";
        }


        $RecordID = GETAsStrOrDef("id", "0");

        $vWaterMarkPosition = "";
        $hash = time();
        $APath = DIR_DBIMAGES . 'comission/' . $hash . '/';

        $query = "insert into $taba
            (`id_podcat`, `name_tovar`,
            `gender`, `wish`, `price`, `desc_tovar`, `short_desc`, `user`, `user_ph`,`user_em`, `user_sk`, `kind`, `city`, `datas`, `dataf` ) 
            values ('$cat','$zag', '$gender', '$sale_bye', '$cost', '$l_desc', '$s_desc', '$cont_face', '$cont_tel', '$cont_email', '$cont_skype', '$condition' , '$cont_city', '$datas', '$dataf')";

        $this->db->exec($query);
        $id_t = array($this->db->lastInsertId());
        $vWaterMarkPosition = 'BL';
        $vWaterMarkSubDir = '../admincp/public/img/watermarks/';

        if (!empty($_FILES)) {
            if ($_FILES['AddImageEdt'] !== NULL) {
                $numer = 0;
                // Проверяем массив, т.к. в некоторых случаях можем грузить больше чем одно фото (для универсальности)
                foreach ($_FILES["AddImageEdt"]["error"] as $key => $error) {
                    $numer++;
                    $ArticleImageID = $numer; // DBInsertRecord($context->db, "ArticleImages");

                    $tmp_name = $_FILES["AddImageEdt"]["tmp_name"][$key];
                    $file_info = new SplFileInfo($_FILES["AddImageEdt"]["name"][$key]);
                    $file_ext = $file_info->getExtension(); // получить расширение файла

                    list($ErrorMsg, $FileRelativeURL) = UploadTovarImage('', $APath, // SubDir - папка для загрузки файла (относительно корня сайта)
                            'ai_' . $ArticleImageID, // Имя файла без расширения (с этим именем файл будет сохранен в папке)
                            600, // Max_image_x - максимальная ширина картинки (меньше можно, иначе будет уменьшено с сохранением пропорции картинки)
                            600, // Max_image_y - максимальная высота картинки (меньше можно, иначе будет уменьшено с сохранением пропорции картинки)
                            $vWaterMarkPosition, $vWaterMarkSubDir . 'l_' . $vWaterMarkPosition . '.png', // 'l_ - размер водяного знака'
                            $tmp_name, $file_ext
                    );




                    $img = $APath . 'ai_' . $ArticleImageID . "." . $file_ext;

                    $query_img = "insert into $taba2 (`tovar_id`, `img`) values ('$id_t[0]', '$img')";
                    
                   
                    $this->db->exec($query_img);
                }
            } else {
                $test = " Нет файла";
            }
        } else {
            $test = " Какой то не такой файл";
        }



        return $id_t;
    }

}
