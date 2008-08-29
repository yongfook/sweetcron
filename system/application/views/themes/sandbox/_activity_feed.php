<div id="main_container">

	<?php if (isset($tag)): ?>
	<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>">Home</a> &rsaquo; Items tagged with <?php echo $tag?></p>
	<?php endif; ?>

	<?php if (isset($query)): ?>
	<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>">Home</a> &rsaquo; Search for <?php echo $query?></p>
	<?php endif; ?>

	<?php if (isset($site)): ?>
	<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>">Home</a> &rsaquo; Items from <?php echo $site?></p>
	<?php endif; ?>
		
    <ul id="activity_list">
        <?php if ($items): $i = 1; foreach ($items as $item): ?>
            <!-- begin conditional content -->
            
            <li class="item <?php echo $item->get_feed_class()?>">
            	<?php if ($item->get_feed_domain() == $this->config->item('base_url')): ?>
            	<p class="site_info" style="background: transparent url(<?php echo $item->get_feed_icon()?>) 0 center no-repeat">I posted a <a href="<?php echo $this->config->item('base_url')?>items/site/sweetcron">blog entry</a></p>
            	<?php else: ?>
            	<p class="site_info" style="background: transparent url(<?php echo $item->get_feed_icon()?>) 0 center no-repeat">I posted to <a href="<?php echo $this->config->item('base_url')?>items/site/<?php echo $item->get_feed_domain()?>"><?php echo $item->get_feed_domain()?></a></p>
            	<?php endif; ?>
            	<h2><?php echo $item->get_title()?></h2>
            	<p class="original_link"><a href="<?php echo $item->get_original_permalink()?>"><?php echo $item->get_original_permalink()?></a></p>
            	<?php if ($item->has_content()): ?>
            	<div class="content"><?php echo $item->get_content()?></div>
            	<?php endif; ?>
            	<?php if ($item->has_image() && !$item->has_video()): ?>
            	<p><a href="<?php echo $item->get_permalink()?>"><img src="<?php echo $item->get_image()?>" alt="" /></a></p>
            	<?php endif; ?>
            	<?php if ($item->has_video()): ?>
            	<div><?php echo $item->get_video()?></div>
            	<?php endif; ?>
            	<?php if ($item->has_tags()): ?>
            	<ul class="item_tag_list">
            		<li>Tags: </li>
            		<?php foreach ($item->get_tags() as $tag): ?>
            		<li><a href="<?php echo $this->config->item('base_url')?>items/tag/<?php echo $tag->slug?>"><?php echo $tag->name?></a></li>
            		<?php endforeach; ?>
            	</ul>
            	<?php endif; ?>
            	<p class="date"><?php echo $item->get_human_date()?> | <a href="<?php echo $item->get_permalink()?>">Comments &raquo;</a></p>
            </li>

        <?php $i++; endforeach; endif; ?>
    </ul>

    <p id="pagination"><?php echo $pages?></p>

</div>