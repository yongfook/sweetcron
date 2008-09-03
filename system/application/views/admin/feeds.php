<div id="main_content">

<?php if ($feeds):?>

<ul class="feed_list">
<?php foreach($feeds as $feed): ?>
<li class="feed">
		<ul class="feed_tools">
			<li class="feed_delete"><a class="confirm_first" href="<?php echo $this->config->item('base_url')?>admin/feeds/delete/<?php echo $feed->feed_id?>">x</a></li>
		</ul>
		<p class="title" style="background-image: url(<?php echo $feed->feed_icon?>)"><?php echo $feed->feed_title?></p>
		<p class="permalink"><a href="<?php echo $feed->feed_url?>" rel="external"><?php echo $feed->feed_url?></a></p>

		<p class="item_count"><?php echo $feed->item_count?> items</p>	
	
</li>
<?php endforeach; ?>
</ul>

<?php else: ?>

<div class="error">You have no feeds :/</div>

<?php endif; ?>

</div>

<div id="side_content">

<div class="buttons">
    <a href="<?php echo $this->config->item('base_url')?>admin/feeds/add" class="positive">
    <img src="<?php echo $this->config->item('base_url')?>public/images/system/icons/silk/add.png" alt="" />
    Add New Feed
    </a>
</div>

</div>