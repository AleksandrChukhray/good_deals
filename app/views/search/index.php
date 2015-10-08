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
		<?php EchoInnovationBlockHTML(); ?>
		<?php EchoInterestingBlockHTML(); ?>
		<?php EchoAdvertisementBlockHTML(); ?>		
		<div id="girl_with_tulips"></div>
	</div>
    
	
	<div id="body_center_middle_column">
		<div class="middle_content_article text_rounded_background">
			<div class="middle_content_inner_block">
				<div class="middle_content_article_header_h3">
                    <div style="margin-right: 180px;">
                        <?php echo empty($AuthorName) ? 'Поиск: "'.$q.'"' : 'Автор: '.$AuthorName; ?>
                    </div>

                    <div id="body_search">
                        <form action="/search/" method="GET">
                            <input name="q" placeholder="Поиск..." value="<?php echo Tools::getValue('q', ''); ?>">
                            <button type="submit"><img src="<?php echo URL; ?>public/images/cat_search_but.png" alt="" /></button>
                        </form>
                    </div>
                </div>
                            
				<?php if (count($articles) > 0) : ?>
					<?php foreach($articles as $a) : ?>
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
				<?php else : ?>
					<p class="clear">По запросу "<?php echo Tools::getValue('q'); ?>" ничего не найдено.</p>
				<?php endif; ?>

				<div id="article_line">
					<div id="article_line_90_Percents">
						<div id="article_line_left"></div>
						<div id="article_line_middle"></div>
						<div id="article_line_right"></div>
					</div>
				</div>

				<?php include("./core/pagination.php"); ?>
			</div>
		</div>
	</div>
</div>