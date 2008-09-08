<div id="main_container">
<?php if (isset($_SERVER['HTTP_REFERER'])):
$backlink = $_SERVER['HTTP_REFERER'];
else:
$backlink = $this->config->item('base_url');
endif; ?>
<p id="breadcrumb"><a href="<?php echo $backlink?>">&laquo; Back To Lifestream</a></p>

<div id="single_container">
	<div id="single_header">
	<h2><?php echo $item->get_title()?></h2>
	<p><?php echo $item->get_human_date()?></p>
	</div>
	
	<?php if ($item->has_content()): ?>
	<div id="single_content"><?php echo $item->get_content()?></div>
	<?php endif; ?>
	<?php if ($item->has_image() && !$item->has_video()): ?>
	<?php if (isset($item->item_data[$item->get_feed_class()]['image']['m']) && !empty($item->item_data[$item->get_feed_class()]['image']['m'])): ?>
	<p><img src="<?php echo $item->item_data[$item->get_feed_class()]['image']['m']?>" alt="" /></p>
	<?php else: ?>
	<p><img src="<?php echo $item->get_image()?>" alt="" /></p>
	<?php endif; ?>
	<?php endif; ?>
	<?php if ($item->has_video()): ?>
	<p>
	        <?php //inline php hackery FTW!
	        $video = str_replace('width="212"', 'width="500"', $item->get_video());
            $video = str_replace('height="159"', 'height="375"', $video);
            $video = str_replace('height="178"', 'height="415"', $video);
            echo $video?>
	</p>
	<?php endif; ?>
	<?php if ($item->has_tags()): ?>
	<ul class="item_tag_list">
		<li>Tags:</li>
		<?php foreach ($item->get_tags() as $tag): ?>
		<li><a href="<?php echo $this->config->item('base_url')?>items/tag/<?php echo $tag->slug?>"><?php echo $tag->name?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	<?php if ($item->get_original_permalink()): ?>
	<p id="original_permalink">Via: <span><a href="<?php echo $item->get_original_permalink()?>"><?php echo $item->get_original_permalink()?></a></span></p>
	<?php endif; ?>
</div>

            <div id="comments_container">
	        Your favourite external commenting service goes here!  I recommend <a href="http://www.disqus.com">http://www.disqus.com</a>
            </div>

</div>
<?php $this->load->view('themes/'.$this->config->item('theme').'/_sidebar')?>