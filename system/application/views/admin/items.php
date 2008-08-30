<div id="main_content">

<?php if (isset($query)): ?>
<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>admin/items">Items Top</a> &rsaquo; Search for <span class="highlight"><?php echo $query?></span></p>
<?php endif; ?>

<?php if (isset($tag)): ?>
<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>admin/items">Items Top</a> &rsaquo; Tagged with <span class="highlight"><?php echo $tag?></span></p>
<?php endif; ?>

<?php if (isset($site)): ?>
<p id="breadcrumb"><a href="<?php echo $this->config->item('base_url')?>admin/items">Items Top</a> &rsaquo; Added via <span class="highlight"><?php echo $site?></span></p>
<?php endif; ?>

<?php if ($items):?>

<?php $this->load->view('admin/_activity_list')?>

<?php else: ?>

<div class="error">You have no items :/</div>

<?php endif; ?>
<p id="pagination"><?php echo $pages?></p>
</div>

<div id="side_content">

<div class="buttons">
    <a href="<?php echo $this->config->item('base_url')?>admin/items/fetch" class="positive">
    <img src="<?php echo $this->config->item('base_url')?>public/images/system/icons/silk/add.png" alt="" />
    Fetch New Items Now
    </a>
</div>

<?php if ($this->config->item('last_fetch')): ?>
<p id="button_explain">Last fetch <span class="highlight"><?php echo timespan($this->config->item('last_fetch'))?></span> ago</p>
<?php else: ?>
<p id="button_explain">Pending first fetch...</p>
<?php endif; ?>

<h4 class="side_title">Search</h4>
<form class="item_search" action="<?php echo $this->config->item('base_url')?>admin/process/search" method="post">
	<p><input type="text" name="keywords" value="<?php if (isset($query)): echo $query; endif; ?>"/></p>
	<input type="submit" style="display: none;"/>
</form>

<h4 class="side_title">Your Feeds</h4>
<ul class="generic active_feeds">
	<li style="background: transparent url(/favicon.ico) 0 center no-repeat;">
	<?php if (isset($site) && $site == 'sweetcron'): ?>
	Sweetcron
	<?php else: ?>
	<a href="<?php echo $this->config->item('base_url')?>admin/items/site/sweetcron">Sweetcron</a>
	<?php endif; ?>
	</li>
	<?php foreach ($active_feeds as $feed): ?>
	<li style="background: transparent url(<?php echo $feed->feed_icon?>) 0 center no-repeat;">
	<?php if (isset($site) && $site == $feed->feed_domain): ?>
	<?php echo $feed->feed_domain?>
	<?php else: ?>
	<a href="<?php echo $this->config->item('base_url')?>admin/items/site/<?php echo $feed->feed_domain?>"><?php echo $feed->feed_domain?></a>
	<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>

<h4 class="side_title">Tags</h4>
<?php if (isset($popular_tags[0])): ?>
<ul class="tag_list some">
<?php foreach ($popular_tags as $single_tag): ?>
	<li>
	<?php if (isset($tag) && $tag == $single_tag->slug): ?>
	<?php echo $single_tag->name?>
	<?php else: ?>
	<a href="<?php echo $this->config->item('base_url')?>admin/items/tag/<?php echo $single_tag->slug?>"><?php echo $single_tag->name?></a>
	<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>

<ul class="tag_list all">
<?php foreach ($all_tags as $single_tag): ?>
	<li>
	<?php if (isset($tag) && $tag == $single_tag->slug): ?>
	<?php echo $single_tag->name?>
	<?php else: ?>
	<a href="<?php echo $this->config->item('base_url')?>admin/items/tag/<?php echo $single_tag->slug?>"><?php echo $single_tag->name?></a>
	<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
<p id="button_explain"><a class="show_all_tags" href="#tag_toggle">Show All Tags</a></p>
<?php else: ?>
No tags
<?php endif; ?>
</div>