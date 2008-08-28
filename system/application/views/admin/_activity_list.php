<ul class="activity_list">
<?php foreach($items as $item): ?>
<li id="item_<?php echo $item->ID?>" class="item <?php echo $item->item_status?>">
		<ul class="item_tools">
			<li class="expand"><a href="#expand">Expand</a></li>
			<li><a href="<?php echo $this->config->item('base_url')?>admin/write/edit/<?php echo $item->ID?>">Edit</a></li>
			<li class="unpublish_this"><a href="#unpublish">Unpublish</a></li>
			<li class="publish_this"><a href="#publish">Publish</a></li>
			<li class="item_delete"><a class="confirm_first" href="<?php echo $this->config->item('base_url')?>admin/items/delete/<?php echo $item->ID?>">x</a></li>
		</ul>
		<p class="icon" style="background-image: url(<?php echo $item->get_feed_icon()?>)"><?php echo $item->get_feed_domain()?> &#8212; <?php echo $item->get_human_date()?></p>
		<div class="item_container">
		<p class="title"><?php echo $item->get_title()?></p>
		<?php if ($item->has_original_permalink()): ?>
		<p class="permalink"><a href="<?php echo $item->get_original_permalink()?>" rel="external"><?php echo $item->get_original_permalink()?></a></p>
		<?php endif; ?>
		<div class="content"><?php echo $item->get_content()?></div>
		<?php if ($item->has_image()): ?>
		<div class="image"><img src="<?php echo $item->get_image()?>" alt="" /></div>
		<?php endif; ?>

		<div class="hideshow"><?php if ($item->has_video()): ?>
		<div class="movie"><?php echo $item->get_video()?></div>
		<?php endif; ?></div>
		
		<?php if ($item->has_tags()): ?>
		<ul class="tags">
		<li class="title">Tags:</li>
		<?php foreach ($item->get_tags() as $tag): ?>
		<li><a href="<?php echo $this->config->item('base_url')?>admin/items/tag/<?php echo $tag->slug?>"><?php echo $tag->name?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>		
		<div class="disabled"></div>
		</div>
</li>
<?php endforeach; ?>
</ul>