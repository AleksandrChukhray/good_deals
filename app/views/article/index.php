<div id="body_center_content" itemscope itemtype="http://schema.org/Article">
    <div class="twocolumnslayout_right_fixed_column">
        <?php EchoArticleCategoriesHTML(); ?>
        <?php EchoAdvertisementBlockHTML(); ?>
        <?php EchoInterestingBlockHTML(); ?>
    </div>

    <div class="twocolumnslayout_left_relative_column">
        <div class="text_rounded_background padding_15 margin_bottom_15">
            <div class="middle_content_article_read_header">
                <div class="comment_like float_right">
                    <div title="Лайкнуть статью" class="<?php echo ($alreadyLiked) ? 'liked' : 'like_active'; ?>" <?php if (!$alreadyLiked) echo 'attr-url="/articles/like/' . $article->ID . '"'; ?>><?php echo $article->count_likes; ?></div>
                    <a href="#AddCommentBox"><div title="Добавить комментарий" class="comment"><?php echo $article->CountComments; ?></div></a>
                    <div title="Количество просмотров" class="viewed"><?php echo $article->CountViews; ?></div>
                </div>
                <div itemprop="headline" class="middle_content_article_read_header_h3"><?php echo $article->Name; ?></div>
                <span style="font-size: 14px; color: #777; margin-left: 5px;"><?php echo date('d.m.Y', strtotime($article->CreateDate)); ?></span>
            </div>

            <div class="display_none">
                <span itemprop="articleSection"><?php echo $ArticleCategory; ?></span>
                <!--<span itemprop="url">< ?php echo $ArticleURL; ?></span>-->
                <span itemprop="datePublished"><?php echo date('Y-m-d', strtotime($article->CreateDate)); ?></span>
                <span itemprop="publisher" itemscope itemtype="http://schema.org/Organization"><meta itemprop="name" content="Карапуз"></span>
            </div>
            
            <div class="middle_content_article_read_block">
                <div class="article_gallery">
                    <img itemprop="image" src="<?php echo $article->PhotoL; ?>" />
                </div>

                <div class="article_contents">
                    <span itemprop="articleBody"><?php echo $article->Description; ?></span>
                    <br>
                    <?php
                        if (!empty($ArticleDocuments) && is_array($ArticleDocuments)) {
                            echo '<i>Файлы, доступные для загрузки:</i>';
                            foreach ($ArticleDocuments as $key => $val) {
                                echo '<p><a href="'.URL.$val['FilePath'].$val['FileName'].'" target="blank">'.
                                        (empty($val['Comment']) ? $val['FileName'] : $val['Comment'].' ('.$val['FileName'].')'). 
                                     '</a></p>';
                            }
                            echo '<br>';
                        }
                    ?>

                    <?php if (!empty($article->AuthorID)) { ?>
                        <div class="author_box">
                            <div class="author_ramka"><img src="<?php echo empty($ArticleAuthor['Photo']) ? URL.'public/images/avatar1.jpg' : GetFileURLWithFileDate($ArticleAuthor['Photo']); ?>" width="128px" height="170px" style="margin: 0px;"/></div>
                            <div class="author_info">
                                Автор: <strong><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo $ArticleAuthor['Name']; ?></span></span></strong>
                                <p style="margin-top: 10px;"><i><?php echo $ArticleAuthor['ShortDescription']; ?></i></p>
                            </div>
                            
                            <div class="author_articles">
                                <?php EchoAuthorArticleBlockHTML($article->AuthorID, $article->ID); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="middle_content_article_read_footer">
                    <div id="article_line">
                        <div id="article_line_100_Percents">
                            <div id="article_line_left"></div>
                            <div id="article_line_middle"></div>
                            <div id="article_line_right"></div>
                        </div>
                    </div>

                    <div class="comment_like float_left" style="margin-right: 10px;">
                        <div title="Лайкнуть статью" class="<?php echo ($alreadyLiked) ? 'liked' : 'like_active'; ?>" <?php if (!$alreadyLiked) echo 'attr-url="/articles/like/' . $article->ID . '"'; ?>><?php echo $article->count_likes; ?></div>
                        <a href="#AddCommentBox"><div title="Добавить комментарий" class="comment"><?php echo $article->CountComments; ?></div></a>
                        <div title="Количество просмотров" class="viewed" style="margin-right: 10px;"><?php echo $article->CountViews; ?></div>                        
                    </div>
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
                    <div class="yashare-auto-init" style="float: right;" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" data-yashareTheme="counter" data-yashareImage="<?php echo URL.$article->PhotoL; ?>"></div>
                </div>
            </div>
        </div>


        <?php if (count($similar) > 0) { ?>
            <div>
                <div class="similar_article_block">
                    <?php foreach ($similar as $k => $s) : ?>
                        <div class="similar_article_<?php echo ($k == 0) ? 'first' : (($k == 1) ? 'second' : 'third'); ?>">
                            <div><a href="/articles/c-<?php echo $s['CategoryID']; ?>/a-<?php echo $s['ID']; ?>"><img src="<?php echo URL.DIR_DBIMAGES.'articles/'.$s['ID'].'/s_1.'.$s['MainImageExt']; ?>" align="left"/><p class="article_similar_text"><?php echo $s['Name']; ?></p></a></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php } ?>

        <!--<div id="AddCommentBox" class="full_width" style="padding-top: 15px;">-->
        <div id="AddCommentBox" style="padding-top: 15px;">
            <div class="article_comments margin_bottom_15">
                <div class="middle_content_article_read_header_h4 margin_bottom_10">Комментариев <span id="TotalCommentCount" class="header_h4_span">(<?php echo $article->CountComments; ?>)</span></div>

                <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <input type="hidden" id="IDEdt" name="IDEdt" value="<?php echo $id; ?>" />
                <input type="hidden" id="AuthorIDEdt" name="AuthorIDEdt" value="<?php echo $article->AuthorID; ?>" />
                <input type="hidden" id="PriorNotifyStateEdt" name="PriorNotifyStateEdt" value="<?php echo ($IsNotifyRecipientActive===true)?'1':'0'; ?>" />

                <?php if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['firstname'])) { ?>
                    <div class="enter_field" style="margin-bottom: 10px;">
                        <input type="text" placeholder="Ваше имя" id="UserNameEdt" name="UserNameEdt" class="required enter_input" style="float: left; width: 550px;" />
                    </div>
                <?php } ?>

                <div class="enter_field">
                    <label id="CommentLengthLbl" class="CommentLength" style="width:550px;">Осталось 500 символов</label>
                    <textarea rows="4" maxlength="500" placeholder="Введите текст комментария" id="CommentEdt" name="CommentEdt" class="required enter_input" style="float:left; width:550px; font-family: Arial, Helvetica, sans-serif;"></textarea>
                </div>
                <div class="enter_field" style="margin-bottom: 0px;">
                    <button id="ajax_AddCommentBtn" name="ajax_AddCommentBtn" type="submit" class="newbutton float_left" style="margin-right: 20px;"><?php echo GetLoaderImageHTML('loader3.gif'); ?>Добавить</button>
                    <?php if (!isset($_SESSION['auth']) || empty($_SESSION['auth']['email'])) { ?>
                        <div class="MyCheckBox">
                            <input id="IsNotifyRecipientActiveEdt" name="IsNotifyRecipientActiveEdt" type="checkbox" disabled/>
                            <label for="IsNotifyRecipientActiveEdt" class="isdisabled" disabled>Уведомлять меня о новых комментариях на эл. почту</label><br/>
                            <label style="padding-left: 10px;">(Доступно только <a class="normal_link" href="/auth/registration">зарегистрированным</a> пользователям!)</label>

                        </div>
                    <?php } else { ?>
                        <div class="MyCheckBox">
                            <input id="IsNotifyRecipientActiveEdt" name="IsNotifyRecipientActiveEdt" type="checkbox" <?php echo ($IsNotifyRecipientActive===true)?'checked':''; ?> />
                            <label for="IsNotifyRecipientActiveEdt">Уведомлять меня о новых комментариях на эл. почту</label><br/>
                        </div>
                    <?php } ?>
                </div>

                <div id="article_line" style="margin: 25px 0 20px 0;">
                    <div id="article_line_100_Percents">
                        <div id="article_line_left"></div>
                        <div id="article_line_middle"></div>
                        <div id="article_line_right"></div>
                    </div>
                </div>

                <div id="article_comments_items"><?php echo GetArticleCommentsHTML($ArticleComments, $article->AuthorID); ?></div>
            </div>
        </div>
    
        
        <?php if (count($discused) > 0) { ?>
            <div>
                <div class="disscused_articles_block margin_bottom_15">
                    <div class="middle_content_article_read_header">
                        <div class="middle_content_article_read_header_h4">Самые обсуждаемые статьи</div>
                    </div>

                    <div class="carousel_down">
                        <div class="carousel-wrapper_down">
                            <div class="carousel-items_down">
                                <?php foreach ($discused as $k => $d) : ?>
                                    <div class="carousel-block_down">
                                        <div class="disscused_article_item">
                                            <a href="/articles/c-<?php echo $d['CategoryID']; ?>/a-<?php echo $d['ID']; ?>">
                                                <img src="<?php echo URL.DIR_DBIMAGES.'articles/'.$d['ID'].'/s_1.'.$d['MainImageExt']; ?>" width="204px" height="153px"/>
                                                <div class="hidden_text_down"><h4><?php echo $d['Name']; ?></h4><?php echo $d['ShortDescription']; ?></div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-button-left_down"><a href="#"></a></div>
                    <div class="carousel-button-right_down"><a href="#"></a></div>
                </div>
            </div>
        <?php } ?>
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
            form_data.append("AuthorIDEdt", $('#AuthorIDEdt').val());   
            form_data.append("token", $('#token').val());
            form_data.append("CommentEdt", $('#CommentEdt').val());
            
            if ($('#IsNotifyRecipientActiveEdt').prop('checked')) {
                form_data.append("IsNotifyRecipientActiveEdt", '1');              
            }            
            
            var vElement = $('#UserNameEdt');
            if (!$.isEmptyObject(vElement)) {
                form_data.append("UserNameEdt", vElement.val());
            }

            $url = window.location.protocol+'//'+window.location.host;
            $.ajax({
                type: "POST",
                url: $url+"/app/ajax/article_add_comment.php",  // point to server-side PHP script 
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
                            $('#article_comments_items').html(vAnswer[2]);
                            $.alert("Ваш комментарий был добавлен.", {type:"success", title:"Успех!"});

                            /*var vPos = data.indexOf("||");
                            var CommentCount = data.substring(0, vPos);
                            var CommentHTML = data.substring(vPos+2, data.length);

                            $('#ArticleCommentCount').html('('+CommentCount+')');                        
                            $('#article_comments_items').html(CommentHTML);*/
                        }
                    } else {
                        $.alert("Ошибка при добавлении комментария.", {type:"danger", title:"Ошибка!"});
                    }
                    $("#ajax_AddCommentBtn").removeClass("action_loader");
                }
            });

            event.preventDefault();
            return false;
        });
    });
</script>    