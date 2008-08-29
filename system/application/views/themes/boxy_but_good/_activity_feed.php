<div id="main_container">

    <ul id="activity_list">
        <?php if ($items): $i = 1; foreach ($items as $item): ?>
            <!-- begin conditional content -->
            
            <!-- this item is a blog post -->
            <?php if ($item->get_feed_domain() == $this->config->item('base_url')): ?>
            <li class="activity_item blog<?php if ($i % 3 == 0):?> last<?php endif;?>">
            <div class="activity_list_inner">
            <span class="type_label blog"></span>
                <div class="regular_container blog">
                    <h2><a href="<?php echo $item->get_permalink()?>"><?php echo $item->get_title()?></a></h2>
                    <div>
                    <?php echo word_limiter(strip_tags($item->get_content()), 25)?> [<a href="<?php echo $item->get_permalink()?>">continue</a>]
                    </div>
                </div>
                
            <!-- this item came from twitter.com -->
            <?php elseif ($item->get_feed_domain() == 'twitter.com'): ?>
            <li class="activity_item regular<?php if ($i % 3 == 0):?> last<?php endif;?>">
            <div class="activity_list_inner">
            <span class="type_label regular"></span>
                <div class="regular_container">
                    <cite class="avatar"><img src="<?php echo $this->config->item('theme_folder')?>images/me.jpg" alt="" /></cite>
                    <div class="speech_tail"></div>
                    <div class="speech_bubble">
                    <?php echo $item->get_title()?>
                    </div>                
                </div>

            <?php elseif ($item->has_video()): ?>
            <li class="activity_item video<?php if ($i % 3 == 0):?> last<?php endif;?>">
            <div class="activity_list_inner">
            <span class="type_label video"></span>
            <?php echo $item->get_video()?>
            <div>
            <?php echo word_limiter(strip_tags($item->get_content()), 5)?> [<a href="<?php echo $item->get_permalink()?>">View Large</a>]
            </div>
            
            <!-- this item has a photo -->
            <?php elseif ($item->has_image() && !$item->has_video()): ?>
            <li class="activity_item photo<?php if ($i % 3 == 0):?> last<?php endif;?>">
            <div class="activity_list_inner">
            <span class="type_label photo"></span>
            <a class="activity_photo_container" href="<?php echo $item->get_permalink()?>" style="background: white url(<?php echo $item->get_image()?>) center center no-repeat;"></a>
            <div>
            <?php echo word_limiter(strip_tags($item->get_content()), 20)?>
            </div>
    
            <!-- this item came from digg.com -->
            <?php elseif ($item->get_feed_domain() == 'digg.com'): ?>
            <li class="activity_item link<?php if ($i % 3 == 0):?> last<?php endif;?>">
            <div class="activity_list_inner">
            <span class="type_label link"></span>
            <div class="link_container">
                <a href="<?php echo $item->get_original_permalink()?>"><?php echo $item->get_title()?></a>
                <div>
                <?php echo word_limiter(strip_tags($item->get_content()), 25)?>
                </div>
                <cite>via <a href="<?php echo $item->get_original_permalink()?>">digg.com</a></cite>
            </div>
            
	        <!-- generic box for when the theme doesn't know what to do -->
            <?php else: ?>
            <li class="activity_item <?php if ($i % 3 == 0):?> last<?php endif;?>">
            <div class="activity_list_inner">
                <div class="regular_container blog">
                    <h2><a href="<?php echo $item->get_permalink()?>"><?php echo $item->get_title()?></a></h2>
                    <div>
                    <?php echo word_limiter(strip_tags($item->get_content()), 25)?> [<a href="<?php echo $item->get_permalink()?>">continue</a>]
                    </div>
                </div>
            
            <?php endif; ?>
    
            <!-- /end conditional content -->
            </div>
            <p class="date"><?php echo $item->get_human_date()?> | <a href="<?php echo $item->get_permalink()?>">Comments</a></p>
            </li>

            <?php if ($i % 3 == 0):?>
            <li class="rowclear"></li>
            <?php endif;?>

        <?php $i++; endforeach; endif; ?>
    </ul>

    <p id="pagination"><?php echo $pages?></p>

</div>