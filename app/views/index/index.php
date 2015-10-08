<div id="body_center_content">
	<div id="body_center_left_column">
		<?php EchoArticleCategoriesHTML(); ?>

		<div class="site_part">
            <div class="site_part_container" >
                <?php include './app/views/common/site_parts_menu.php'; ?>
            </div>
		</div>
		<div id="block_bottom_flower"></div>
		
        <?php EchoKarapuzRecommendedBlockHTML(); ?>
		<!--<a href="#" title="Добрые дела" class="dev"><div id="good_deal"></div></a>-->
	</div>	


	<div id="body_center_right_column">
		<?php EchoAdvertisementBlockHTML(); ?>		
		<div id="interview_block">
			<iframe src="https://docs.google.com/forms/d/1W1zRMHEMicLbGQlES0UTHxzRLk4oqhnq5C2TcwSTFfA/viewform?embedded=true" scrolling="no" width="296" height="520" frameborder="0" marginheight="0" marginwidth="0">Загрузка...</iframe>
		</div>
        <?php EchoInnovationBlockHTML(); ?>
        <?php EchoInterestingBlockHTML(); ?>
        <?php EchoAdvertisementBlockHTML(); ?>
	</div>
    
    
	<div id="body_center_middle_column">
		<div class="middle_content_slider"> 
			<div class="blueberry">
				<ul class="slides">
                    <?php
                        foreach ($NewsSlider as $r) {
                            echo '<li> '.
                                    '<a href="'.$r['URL'].'"> '.
                                        '<div class="middle_content_slider_slide"> '.
                                            '<img src="'.URL.$r['Photo'].'" style="width: 633px; height: 250px;" /> '.
                                        '</div> '.
                                    '</a> '.
                                '</li>';
                        }
                    ?>
				</ul>
			</div>
		</div>
		
		<div class="middle_content_article text_rounded_background margin_top_15">
			<div class="middle_content_inner_block">
				<div class="middle_content_article_header_h3">Новые статьи</div>
				
				<?php foreach ($lastArticles as $a) : ?>
				<div class="middle_content_article_block">
					<div class="a_div">
						<a href="/articles/c-<?php echo $a['CategoryID']; ?>/a-<?php echo $a['ID']; ?>">
							<div class="pre_article">
								<img src="<?php echo URL.DIR_DBIMAGES.'articles/'.$a['ID'].'/s_1.'.$a['MainImageExt']; ?>" align="left"/>
								
								<div class="comment_like float_right">
									<div class="like"><?php echo $a['count_likes']; ?></div>
									<div class="comment"><?php echo $a['CountComments']; ?></div>
								</div>

								<div class="article_title">
									<span><?php echo $a['Name']; ?></span>
								</div>

								<?php echo $a['ShortDescription']; ?>
							</div>
						</a>
					</div>
				</div>
				<?php endforeach; ?>	

				<div id="article_line">
					<div id="article_line_90_Percents">
						<div id="article_line_left"></div>
						<div id="article_line_middle"></div>
						<div id="article_line_right"></div>
					</div>
				</div>
				
				<a href="/category/"><div class="newbutton float_right">Больше статей</div></a>
			</div>
		</div>
	</div>	
</div>