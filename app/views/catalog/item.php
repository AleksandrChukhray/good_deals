<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
<script src="/public/js/catalogitem.js"></script>

<div id="body_center_content" itemscope itemtype="http://schema.org/LocalBusiness" itemref="_image2 _telephone3 _address4 _review13">
    <input type="hidden" id="IDEdt" name="IDEdt" value="<?php echo $id; ?>" />
    <input type="hidden" id="TokenEdt" name="TokenEdt" value="<?php echo $_SESSION['token']; ?>" />

    <div class="twocolumnslayout_right_fixed_column">
        <?php EchoAdvertisementBlockHTML(); ?>
        <?php EchoInterestingBlockHTML(); ?>
        <?php EchoAdvertisementBlockHTML(); ?>
    </div>
    
    <div class="twocolumnslayout_left_relative_column">
        <div class="catalog_block margin_bottom_15" style="padding-left: 15px;">
            <div class="text_rounded_background" style="position: relative; border-radius: 24px; background-color: rgba(255, 255, 255, 1); padding: 15px; margin-bottom: 25px;">
                <div itemprop="name" class="header" style="margin-right: 150px"><?php echo $item["name"];?></div>
                <div id="catalog_total_raiting">
                    <div style="width:100%; font-size: 12px;">Оценок: <?php echo $item['CountRatings']; ?></div>
                    <?php GetRaitingHTML($item['TotalRating']); ?>
                </div>
                
                <div class="display_none" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" >
                    <span itemprop="ratingValue"><?php echo intval($item["TotalRating"]); ?></span>
                    <span itemprop="ratingCount"><?php echo $item["CountRatings"]; ?></span>
                    <span itemprop="worstRating">0</span>
                    <span itemprop="bestRating">5</span>
                </div>

                <div class="info_block">
                    <div class="img">
                        <img id="_image2" itemprop="image" style="width: 250px; max-height: 188px; <?php echo empty($item["foto"]) ? 'height: 138px;' : '';?>" src="<?php echo empty($item["foto"]) ? '/public/images/house.png' : URL.$item["foto"];?>">                          
                    </div>

                    <div class="info_text">
                        <div class="info_line special2"><span>Тип:</span>&nbsp;<?php echo $item['SubCategoryName'];?></div>
                        <div class="info_line" id="_address4" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <img src="/public/images/rod_ico1.png">
                            <?php echo $item["LocalityName"].", ".$item["adress"]; ?>
                            
                            <div class="display_none">
                                <span itemprop="addressRegion"><?php echo $item["RegionName"]; ?></span>., 
                                <span itemprop="addressLocality"><?php echo $item["OriginalLocalityName"]; ?></span>, 
                                <span itemprop="streetAddress"><?php echo $item["adress"]; ?></span>
                            </div>                           
                        </div>

                        <div class="info_line" id="_telephone3" itemprop="telephone">
                            <img src="/public/images/rod_ico2.png">
                            <?php echo $item["kont_tell"]; ?>
                        </div>

                        <div class="info_line">
                            <img src="/public/images/rod_ico3.png">
                            <?php echo empty($item["site_url"]) ? "" : '<a href="http://'.$item["site_url"].'" target="_blank">'.$item["site_url"].'</a>'; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab_line">
                <div class="tab <?php echo ($ActiveTab == 'uslugi') ? 'pur' : ''; ?>" style="z-index: 6;">Услуги</div>
                <div class="tab" style="z-index: 5;">Персонал</div>
                <div class="tab" style="z-index: 4;">Фото</div>
                <div class="tab <?php echo ($ActiveTab == 'comments') ? 'gr' : ''; ?>" style="z-index: 3;">Отзывы</div>
                <div class="tab <?php echo ($ActiveTab == 'raiting') ? 'pur' : ''; ?>" style="z-index: 2;">Рейтинг</div>
                <div id="MapBox" class="tab" style="z-index: 1;">Карта</div>
            </div>
                
            <div class="theme_block tabs">
                <!-- Блок Услуги -->
                <div id="_review13" itemprop="review" itemscope itemtype="http://schema.org/Review" class="tab_item" style="<?php echo ($ActiveTab == 'uslugi') ? 'display: block; opacity:1;' : 'display: none; opacity:0;'; ?>">
                    <span itemprop="reviewBody"><?php echo empty($item["uslugi"]) ? "На данный момент нет данных!" : $item["uslugi"]; ?></span>
                    <div class="display_none">
                        <span itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="Карапуз"></span>
                    </div>                    
                </div>
                
                <!-- Блок Персонал -->
                <div class="tab_item" style="display: none; opacity: 0;">
                    <?php 
                        if (count($personal) > 0) {
                            foreach ($personal as $k=>$a) { ?>
                                <div class="block_row">
                                    <img src="<?php echo empty($a['foto']) ? "/public/images/user_frame.png" : URL.$a['foto']; ?>">
                                    <span class="icon-user"></span><span><?php echo $a["Name"]; ?></span><br>
                                    <span class="icon-suitcase2"></span><?php echo $a["JobTitleName"]; ?><br>
                                    <span class="icon-phone42"></span><?php echo $a["tell_kont"];?><br>
                                    <span class="icon-chronometer"></span><?php echo $a["rabot_graf"];?>
                                </div>
                            <?php }
                        } else {
                            echo "На данный момент нет данных!";
                        } 
                    ?>
                </div>
                    
                <!-- Блок Фото -->
                <div class="tab_item" style="display: none; opacity: 0;">
                    <?php
                        if (count($photos) > 0) {
                            foreach ($photos as $k=>$a) {
                                echo '<img class="foto_dop" src="'.URL.$a['Photo'].'">';
                            }
                        } else {
                            echo "На данный момент нет данных!";
                        }
                    ?>
                </div>
                
                <!-- Блок Отзывы -->
                <div class="tab_item" style="<?php echo ($ActiveTab == 'comments') ? 'display: block; opacity:1;' : 'display: none; opacity:0;'; ?>">
                    <div class="middle_content_article_read_header_h4 margin_bottom_10">Отзывов <span id="TotalCommentCount" class="header_h4_span">(<?php echo count($comments); ?>)</span></div>
                    
                    <?php if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['firstname'])) { ?>
                        <div class="enter_field" style="margin-bottom: 10px;">
                            <input type="text" placeholder="Ваше имя" id="UserNameEdt" name="UserNameEdt" class="required enter_input" style="float: left; width: 550px;" />
                        </div>
                    <?php } ?>
                    
                    <div class="enter_field">
                        <label id="CommentLengthLbl" class="CommentLength" style="width:550px;">Осталось 500 символов</label>
                        <textarea rows="4" maxlength="500" placeholder="Введите текст отзыва" id="CommentEdt" name="CommentEdt" class="required enter_input" style="float:left; width:550px; font-family: Arial, Helvetica, sans-serif;"></textarea>
                    </div>
                    <div class="enter_field" style="margin-bottom: 0px;">
                        <button id="ajax_AddCommentBtn" name="ajax_AddCommentBtn" type="submit" class="newbutton float_left" style="margin-right: 20px;"><?php echo GetLoaderImageHTML('loader3.gif'); ?>Добавить</button>
                    </div>

                    <div id="article_line" style="margin: 25px 0 20px 0;">
                        <div id="article_line_100_Percents">
                            <div id="article_line_left"></div>
                            <div id="article_line_middle"></div>
                            <div id="article_line_right"></div>
                        </div>
                    </div>                    
                    
                    <div id="comments_items">
                        <?php 
                            if (count($comments) > 0) {
                                echo GetCatalogCommentsHTML($comments);
                                /*foreach ($comments as $k=>$a) { ?>
                                    <div class="comment_item">
                                        <div class="comment_title">
                                            <label class="comment_user"><?php echo $a["UserName"]; ?></label>
                                            <label class="comment_time"><?php echo $UserDesc.date("d.m.Y H:i", strtotime($a['CreateDate'])); ?></label>
                                        </div>
                                        <div class="comment_text">
                                            <?php echo $a["Text"];?>
                                        </div>
                                    </div>
                                <?php }*/
                            } else {
                                echo "На данный момент нет данных!";
                            }
                        ?>
                    </div>
                </div>
                
                <!-- Блок Рейтинг -->
                <div class="tab_item" style="<?php echo ($ActiveTab == 'raiting') ? 'display: block; opacity:1;' : 'display: none; opacity:0;'; ?>">
                    <li class="rating-view">
                        <label class="rating-nam-lab">Условия:</label>
                        <div class="rating-view-rat">
                            <?php GetRaitingHTML($item['Rating1']); ?>
                        </div>
                    </li>
                    <li class="rating-view">
                        <label class="rating-nam-lab">Персонал:</label>
                        <div class="rating-view-rat">
                            <?php GetRaitingHTML($item['Rating2']); ?>
                        </div>
                    </li>
                    <li class="rating-view">
                        <label class="rating-nam-lab">Отношение:</label>
                        <div class="rating-view-rat">
                            <?php GetRaitingHTML($item['Rating3']); ?>
                        </div>
                    </li>
                    <li class="rating-view">
                        <label class="rating-nam-lab">Всего оценок:</label>
                        <div class="rating-view-rat">
                            <label class="rating-nam-lab"><?php echo $item['CountRatings']; ?></label>
                        </div>
                    </li>
                    
                    <?php if (empty($RaitingID)) { ?>
                        <div class="inline_form_body padding_15" style="margin: 40px 15px 15px 15px; width:560px;">
                            <div class="enter_h3">Дать свою оценку</div>

                            <form action="" method="post">
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                                <input type="hidden" name="uslovjEdt" id="uslovjEdt" value="" />
                                <input type="hidden" name="personalEdt" id="personalEdt" value="" />
                                <input type="hidden" name="uvagaEdt" id="uvagaEdt" value="" />

                                <div class="enter_field">
                                    <li class="rating-view">
                                        <label class="rating-nam-lab">Условия:</label>
                                        <div class="rating-view-rat">
                                            <?php GetRaitingHTML(0, 'uslovj'); ?>
                                        </div>
                                    </li>
                                    <li class="rating-view">
                                        <label class="rating-nam-lab">Персонал:</label>
                                        <div class="rating-view-rat">
                                            <?php GetRaitingHTML(0, 'personal'); ?>
                                        </div>
                                    </li>
                                    <li class="rating-view">
                                        <label class="rating-nam-lab">Отношение:</label>
                                        <div class="rating-view-rat">
                                            <?php GetRaitingHTML(0, 'uvaga'); ?>
                                        </div>
                                    </li>
                                </div>

                                <button name="AddRaitingBtn" type="submit" class="newbutton">Отправить</button>
                            </form>
                        </div>
                    <?php } else { ?>
                        <br>
                        <h3>Спасибо за Вашу оценку!</h3>
                    <?php } ?>
                </div>

                <!-- Блок Карта -->
                <div class="tab_item" style="display: none; opacity: 0;">
                    <input type="hidden" id="MapXEdt" name="MapXEdt" value="<?php echo $item['MapX']; ?>">
                    <input type="hidden" id="MapYEdt" name="MapYEdt" value="<?php echo $item['MapY']; ?>">
                    <input type="hidden" id="FullAddressEdt" name="FullAddressEdt" value="<?php echo $item['FullAddress']; ?>">
                    
                    <div id="map-canvas" class="info_map" style="height: 350px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#CommentEdt").on('input', function() {
            $("#CommentLengthLbl").text("Осталось "+(500-$(this).val().length).toString()+" символов");
        });
        
        $("#ajax_AddCommentBtn").click(function (event) {
            if (ValidateForm() === false) {
                event.preventDefault();            
                return false;
            }
            
            $("#ajax_AddCommentBtn").addClass("action_loader");
            //var form_data = new FormData($('form')[0]); // отправляет данные всей формы

            var form_data = new FormData();

            form_data.append("IDEdt", $('#IDEdt').val());
            form_data.append("token", $('#TokenEdt').val());
            form_data.append("CommentEdt", $('#CommentEdt').val());
            
            var vElement = $('#UserNameEdt');
            if (!$.isEmptyObject(vElement)) {
                form_data.append("UserNameEdt", vElement.val());
            }

            $url = window.location.protocol+'//'+window.location.host;
            $.ajax({
                type: "POST",
                url: $url+"/app/ajax/catalog_add_comment.php",  // point to server-side PHP script 
                dataType: 'text', // what to expect back from the PHP script, if anything
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    if (data !== '') {
                        var vAnswer = data.split("||");
                        
                        if (vAnswer[0] !== '') {
                            $.alert(vAnswer[0], {type:"danger", title:"Ошибка!"});
                        } else {                        
                            $('#CommentEdt').val('');
                            $("#CommentLengthLbl").text("Осталось 500 символов");

                            var vElement = $('#UserNameEdt');
                            if (!$.isEmptyObject(vElement)) {
                                vElement.val('');
                            }

                            $('.comment').html(+vAnswer[1]);
                            $('#TotalCommentCount').html('('+vAnswer[1]+')');
                            $('#comments_items').html(vAnswer[2]);
                            $.alert("Ваш отзыв был добавлен.", {type:"success", title:"Успех!"});
                        }
                    } else {
                        $.alert("Ошибка при добавлении отзыва.", {type:"danger", title:"Ошибка!"});
                    }
                    $("#ajax_AddCommentBtn").removeClass("action_loader");
                }
            });

            event.preventDefault();
            return false;
        });
    });
</script>