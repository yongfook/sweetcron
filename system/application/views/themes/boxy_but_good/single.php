<div id="main_container">

<!-- begin conditional content -->
            <?php if ($item->get_feed_domain() == $this->config->item('base_url')): ?>
    <div id="single_container" class="blog">
            <div class="activity_list_inner">
            <span class="type_label blog"></span>
                <div class="regular_container blog">
                    <h2><a href="<?php echo $item->get_permalink()?>"><?php echo $item->get_title()?></a></h2>
                    <div>
                    <?php echo $item->get_content()?>
                    </div>
                </div>
                
            <!-- this item came from twitter.com -->
            <?php elseif ($item->get_feed_domain() == 'twitter.com'): ?>
    <div id="single_container" class="regular">
            <div class="activity_list_inner">
            <span class="type_label regular"></span>
                <div class="regular_container">
                    <cite class="avatar"><img src="<?php echo $this->config->item('theme_folder')?>images/me.jpg" alt="" /></cite>
                    <div class="speech_tail"></div>
                    <div class="speech_bubble">
                    <?=php echo $item->get_title()?>
                    </div>                
                </div>

            <?php elseif ($item->has_video()): ?>
    <div id="single_container" class="video">
            <div class="activity_list_inner">
            <span class="type_label video"></span>
	        <?php //inline php hackery FTW!
	        $video = str_replace('width="212"', 'width="500"', $item->get_video());
            $video = str_replace('height="159"', 'height="375"', $video);
            $video = str_replace('height="178"', 'height="415"', $video);
            echo $video?>
            <div>
            <?php echo $item->get_content()?>
            </div>
            
            <!-- this item has a photo -->
            <?php elseif ($item->has_image() && !$item->has_video()): ?>
    <div id="single_container" class="photo">
            <div class="activity_list_inner">
            <span class="type_label photo"></span>
            <img src="<?php echo $item->get_image()?>" class="activity_photo" />
            <div>
            <?php echo $item->get_content()?>
            </div>
    
            <!-- this item came from digg.com -->
            <?php elseif ($item->get_feed_domain() == 'digg.com'): ?>
    <div id="single_container" class="link">
            <div class="activity_list_inner">
            <span class="type_label link"></span>
            <div class="link_container">
                <a href="<?php echo $item->get_original_permalink()?>"><?php echo $item->get_title()?></a>
                <div>
                <?php echo $item->get_content()?>
                </div>
                <cite>via <a href="<?php echo $item->get_original_permalink()?>">digg.com</a></cite>
            </div>
            
            <?php else: ?>

    <div id="single_container" class="blog">
            <div class="activity_list_inner">
                <div class="regular_container blog">
                    <h2><a href="<?php echo $item->get_permalink()?>"><?php echo $item->get_title()?></a></h2>
                    <div>
                    <?php echo $item->get_content()?>
                    </div>
                </div>
            
            <?php endif; ?>
	            </div></div>
            <p class="date single"><?php echo $item->get_human_date()?> | <a href="<?php echo $item->get_original_permalink()?>">Original Link &raquo;</a></p>
            
            <?php if ($item->has_tags()): ?>
            <div id="tags_container">
            <ul class="tag_list">
	        <li>Tags: </li>
            <?php foreach ($item->get_tags() as $tag): ?>
            	<li><a href="<?php echo $this->config->item('base_url')?>items/tag/<?php echo $tag->slug?>"><?php echo $tag->name?></a></li>
            <?php endforeach; ?>
            </ul>
            </div>
            <?php endif; ?>
            
            <div id="comments_container">
	        Your favourite external commenting service goes here!  I recommend <a href="http://www.disqus.com">http://www.disqus.com</a>
            </div>

</div>
<?php $this->load->view('themes/'.$this->config->item('theme').'/_sidebar')?>